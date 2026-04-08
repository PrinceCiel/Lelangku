<?php
namespace App\Http\Controllers;

use App\Models\Pemenang;
use App\Services\MidtransService;
use App\Models\Struk;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;

class StrukController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;

        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $struk = Struk::where('user_id', Auth::user()->id)
                     ->latest()
                     ->firstOrFail();

        return view('struk', compact('struk'));
    }

    /**
     * Check status pembayaran dari Midtrans
     */
    public function checkStatus(string $kode)
    {
        try {
            // Ambil struk berdasarkan kode
            $struk = Struk::where('kode_struk', $kode)->firstOrFail();

            // PENTING: Gunakan order_id, bukan kode_struk
            $order_id = $struk->order_id ?? $struk->kode_struk;

            // Memeriksa status transaksi dari Midtrans
            $status = (object) Transaction::status($order_id);

            // Memperbarui status struk berdasarkan status transaksi
            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $struk->status = 'berhasil';
                $struk->save();
                toast('Pembayaran berhasil dikonfirmasi!', 'success');

            } elseif ($status->transaction_status == 'pending') {
                $struk->status = 'pending';
                $struk->save();
                toast('Pembayaran masih pending, silakan selesaikan pembayaran.', 'info');

            } elseif (in_array($status->transaction_status, ['deny', 'expire', 'cancel'])) {
                $struk->status = 'gagal';
                $struk->save();
                toast('Pembayaran gagal/expired.', 'error');

            } else {
                toast('Status pembayaran: ' . $status->transaction_status, 'info');
            }

            return redirect()->back();

        } catch (\Exception $e) {
            toast('Gagal mengecek status: ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Tampilkan halaman struk dengan Snap Token
     */
    public function struk(string $kodestruk)
    {
        try {
            $struk = Struk::where('kode_struk', $kodestruk)->firstOrFail();
            $pemenang = Pemenang::findOrFail($struk->id_pemenang);

            // Hitung total
            $bidakhir = $pemenang->bid;
            $adminfee = $bidakhir * 0.05;
            $total = $adminfee + $bidakhir;

            // Generate order_id jika belum ada
            if (!$struk->order_id) {
                $struk->order_id = 'ORDER-' . time() . '-' . $struk->id;
                $struk->save();
            }

            // Bikin Snap Token jika belum ada atau regenerate jika sudah expired
            if (!$struk->snap_token || $this->isTokenExpired($struk)) {
                $params = [
                    'transaction_details' => [
                        'order_id' => $struk->order_id,
                        'gross_amount' => (int)$total,
                    ],
                    'customer_details' => [
                        'first_name' => $pemenang->user->nama_lengkap,
                        'email' => $pemenang->user->email,
                        'phone' => $pemenang->user->phone ?? '08123456789',
                    ],
                    'item_details' => [
                        [
                            'id' => $struk->id_barang,
                            'price' => (int)$bidakhir,
                            'quantity' => 1,
                            'name' => $struk->lelang->barang->nama ?? 'Produk Lelang',
                        ],
                        [
                            'id' => 'ADMIN_FEE',
                            'price' => (int)$adminfee,
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
                        'duration' => 24,
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                $struk->snap_token = $snapToken;
                $struk->save();
            } else {
                $snapToken = $struk->snap_token;
            }

            return view('struk', compact('struk', 'pemenang', 'snapToken'));

        } catch (\Exception $e) {
            throw $e;
            toast('Gagal memuat halaman pembayaran: ' . $e->getMessage(), 'error');
            return redirect()->route('home.user'); // UBAH DI SINI
        }
    }

    /**
     * Cek apakah token sudah expired (lebih dari 24 jam)
     */
    private function isTokenExpired($struk)
    {
        if (!$struk->updated_at) {
            return true;
        }

        $tokenAge = now()->diffInHours($struk->updated_at);
        return $tokenAge > 24;
    }

    /**
     * Create new transaction (dipanggil saat pemenang lelang ditentukan)
     */
    public function createTransaction($id_pemenang)
    {
        try {
            $pemenang = Pemenang::findOrFail($id_pemenang);

            // Cek apakah sudah ada struk untuk pemenang ini
            $existingStruk = Struk::where('id_pemenang', $id_pemenang)->first();
            if ($existingStruk) {
                return redirect()->route('frontend.struk.detail', ['kodestruk' => $existingStruk->kode_struk]);
            }

            // Hitung total
            $bidakhir = $pemenang->bid;
            $adminfee = $bidakhir * 0.05;
            $total = $adminfee + $bidakhir;

            // Generate kode unik
            $kode_unik = strtoupper(substr(md5(uniqid()), 0, 8));
            $kode_struk = 'STR-' . time() . '-' . $pemenang->id;
            $order_id = 'ORDER-' . time() . '-' . $pemenang->id;

            // Buat struk
            $struk = Struk::create([
                'id_lelang' => $pemenang->id_lelang,
                'id_barang' => $pemenang->lelang->id_barang,
                'id_pemenang' => $pemenang->id,
                'user_id' => $pemenang->id_user,
                'total' => $total,
                'status' => 'belum dibayar',
                'kode_unik' => $kode_unik,
                'kode_struk' => $kode_struk,
                'order_id' => $order_id,
                'tgl_trx' => now(),
            ]);

            toast('Transaksi berhasil dibuat! Silakan lanjutkan pembayaran.', 'success');
            return redirect()->route('frontend.struk.detail', ['kodestruk' => $struk->kode_struk]);

        } catch (\Exception $e) {
            toast('Gagal membuat transaksi: ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
    public function belumBayar()
    {
        $struks = Struk::with(['lelang.barang', 'pemenang.user'])
            ->whereIn('status', ['belum dibayar', 'pending'])
            ->latest()
            ->get();

        return view('struk.belum-bayar', compact('struks'));
    }
    public function konfirmasi($kode)
    {
        $struk = Struk::where('kode_struk', $kode)->firstOrFail();
        $struk->update(['status' => 'berhasil']);
        return back()->with('success', 'Pembayaran dikonfirmasi!');
    }

    public function batal($kode)
    {
        $struk = Struk::where('kode_struk', $kode)->firstOrFail();
        $struk->update(['status' => 'gagal']);
        return back()->with('success', 'Tagihan dibatalkan.');
    }
}
