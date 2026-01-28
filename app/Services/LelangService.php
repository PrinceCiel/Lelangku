<?php
namespace App\Services;

use App\Models\Bid;
use App\Models\Lelang;
use App\Models\Pemenang;
use App\Models\Struk;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;

class LelangService
{
    public function __construct()
    {
        // Setup Midtrans Config
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function handle(): void
    {
        $now = now();
        $this->updateStatusLelang($now);
        $this->tentukanPemenang($now);
        $this->cekPembayaran($now);
        $this->ulangLelangGagal($now);
    }

    protected function updateStatusLelang($now)
    {
        Lelang::chunk(50, function ($lelangs) use ($now) {
            foreach ($lelangs as $lelang) {
                if ($now->lt($lelang->jadwal_mulai)) {
                    $status = 'ditutup';
                } elseif ($now->between($lelang->jadwal_mulai, $lelang->jadwal_berakhir)) {
                    $status = 'dibuka';
                } else {
                    $status = 'selesai';
                }
                
                if ($lelang->status !== $status) {
                    $lelang->update(['status' => $status]);
                }
            }
        });
    }

    protected function tentukanPemenang($now)
    {
        $lelangs = Lelang::where('jadwal_berakhir', '<=', $now)
            ->whereDoesntHave('pemenang')
            ->with('bid')
            ->get();

        foreach ($lelangs as $lelang) {
            $bidTertinggi = $lelang->bid()
                ->orderByDesc('bid')
                ->orderByDesc('created_at')
                ->first();

            if (!$bidTertinggi) continue;

            $pemenang = Pemenang::create([
                'id_lelang' => $lelang->id,
                'id_user'   => $bidTertinggi->id_user,
                'bid'       => $bidTertinggi->bid,
            ]);

            // Buat struk dengan Midtrans integration
            $this->buatStruk($lelang, $pemenang);
        }
    }

    protected function buatStruk($lelang, $pemenang)
    {
        // Generate kode unik
        do {
            $kodeStruk = 'STRL-' . Str::upper(Str::random(10));
        } while (Struk::where('kode_struk', $kodeStruk)->exists());

        // Generate order_id unik untuk Midtrans
        $orderId = 'ORDER-' . time() . '-' . $pemenang->id;

        // Hitung total
        $total = $pemenang->bid;
        $adminFee = $total * 0.05;
        $grandTotal = $total + $adminFee;

        // Buat struk
        $struk = Struk::create([
            'id_lelang'   => $lelang->id,
            'id_barang'   => $lelang->id_barang,
            'id_pemenang' => $pemenang->id,
            'user_id'     => $pemenang->id_user, // PENTING: Tambahkan user_id
            'total'       => $grandTotal,
            'status'      => 'belum dibayar',
            'tgl_trx'     => now(),
            'kode_struk'  => $kodeStruk,
            'order_id'    => $orderId, // PENTING: Tambahkan order_id
            'kode_unik'   => strtoupper(substr(md5(uniqid()), 0, 8)),
        ]);

        // Generate Snap Token
        try {
            $snapToken = $this->generateSnapToken($struk, $pemenang, $lelang, $total, $adminFee);
            $struk->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            \Log::error('Failed to generate Snap Token: ' . $e->getMessage());
        }
    }

    /**
     * Generate Snap Token untuk pembayaran
     */
    protected function generateSnapToken($struk, $pemenang, $lelang, $total, $adminFee)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $struk->order_id,
                'gross_amount' => (int)($total + $adminFee),
            ],
            'customer_details' => [
                'first_name' => $pemenang->user->nama_lengkap ?? 'Customer',
                'email' => $pemenang->user->email ?? 'customer@example.com',
                'phone' => $pemenang->user->phone ?? '08123456789',
            ],
            'item_details' => [
                [
                    'id' => $lelang->id_barang,
                    'price' => (int)$total,
                    'quantity' => 1,
                    'name' => $lelang->barang->nama ?? 'Produk Lelang',
                ],
                [
                    'id' => 'ADMIN_FEE',
                    'price' => (int)$adminFee,
                    'quantity' => 1,
                    'name' => 'Biaya Admin (5%)',
                ],
            ],
            'callbacks' => [
                'finish' => route('midtrans.finish'),
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24, // Token valid 24 jam
            ],
        ];

        return Snap::getSnapToken($params);
    }

    protected function cekPembayaran($now)
    {
        // Ubah status dari 'belum dibayar' ke 'pending' setelah 1 jam
        Struk::where('status', 'belum dibayar')
            ->where('tgl_trx', '<=', $now->copy()->subHour())
            ->chunk(50, function ($struks) {
                foreach ($struks as $struk) {
                    // Cek status dari Midtrans sebelum update
                    $this->checkMidtransStatus($struk);
                }
            });

        // Ubah status dari 'pending' ke 'gagal' setelah 24 jam
        Struk::where('status', 'pending')
            ->where('updated_at', '<=', $now->copy()->subDay())
            ->chunk(50, function ($struks) {
                foreach ($struks as $struk) {
                    // Cek status terakhir dari Midtrans
                    $this->checkMidtransStatus($struk);
                    
                    // Jika masih pending setelah 24 jam, set gagal
                    if ($struk->fresh()->status == 'pending') {
                        $struk->update(['status' => 'gagal']);
                    }
                }
            });
    }

    /**
     * Cek status pembayaran dari Midtrans
     */
    protected function checkMidtransStatus($struk)
    {
        try {
            if (!$struk->order_id) return;

            $status = \Midtrans\Transaction::status($struk->order_id);
            
            // Update status berdasarkan response Midtrans
            if (in_array($status->transaction_status, ['settlement', 'capture'])) {
                $struk->update(['status' => 'berhasil']);
            } elseif ($status->transaction_status == 'pending') {
                $struk->update(['status' => 'pending']);
            } elseif (in_array($status->transaction_status, ['deny', 'expire', 'cancel'])) {
                $struk->update(['status' => 'gagal']);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to check Midtrans status: ' . $e->getMessage());
        }
    }

    protected function ulangLelangGagal($now)
    {
        $struks = Struk::where('status', 'gagal')
            ->whereHas('lelang') // Pastikan lelang masih ada
            ->get();

        foreach ($struks as $struk) {
            $lelang = Lelang::find($struk->id_lelang);
            if (!$lelang) continue;

            // Hapus semua data terkait lelang yang gagal
            Struk::where('id_lelang', $lelang->id)->delete();
            Pemenang::where('id_lelang', $lelang->id)->delete();
            Bid::where('id_lelang', $lelang->id)->delete();

            // Reset jadwal lelang
            $lelang->update([
                'jadwal_mulai' => $now->copy()->addHour(),
                'jadwal_berakhir' => $now->copy()->addHours(4),
                'status' => 'ditutup',
            ]);

            \Log::info("Lelang {$lelang->kode_lelang} diulang karena pembayaran gagal");
        }
    }
}