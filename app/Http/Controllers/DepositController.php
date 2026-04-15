<?php
namespace App\Http\Controllers;
use App\Services\MidtransService;
use App\Models\Deposit;
use App\Models\Lelang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
class DepositController extends Controller
{
    protected $midtrans;
    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    public function create(Request $request)
    {
        $request->validate([
            'kode_lelang' => 'required|exists:lelangs,kode_lelang',
        ]);
        $lelang = Lelang::where('kode_lelang', $request->kode_lelang)->firstOrFail();
        // Cek sudah deposit belum (termasuk 'belum dibayar' supaya back+create ulang tidak duplikat)
        $existing = Deposit::where('id_lelang', $lelang->id)
                            ->where('id_user', Auth::id())
                            ->whereIn('status', ['berhasil', 'pending', 'belum dibayar'])
                            ->first();
        if ($existing) {
            // Refresh snap token kalau kosong
            if (!$existing->snap_token) {
                $params = [
                    'transaction_details' => [
                        'order_id'     => $existing->order_id,
                        'gross_amount' => (int) $existing->total,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->nama_lengkap,
                        'email'      => Auth::user()->email,
                    ],
                    'item_details' => [
                        [
                            'id'       => $existing->kode_deposit,
                            'price'    => (int) $existing->total,
                            'quantity' => 1,
                            'name'     => 'Deposit Lelang: ' . $lelang->barang->nama,
                        ],
                    ],
                ];
                try {
                    $snapToken = Snap::getSnapToken($params);
                    $existing->update(['snap_token' => $snapToken]);
                } catch (\Exception $e) {
                    Log::error('Snap token refresh failed: ' . $e->getMessage());
                }
            }
            return redirect()->route('deposit.show', $existing->kode_deposit)
                             ->with('info', 'Lanjutkan pembayaran deposit Anda.');
        }
        $nominal     = $lelang->barang->harga * 0.30;
        $kodeDeposit = 'DEP-' . strtoupper(Str::random(8)) . '-' . time();
        $orderId     = $kodeDeposit;
        $deposit = Deposit::create([
            'id_lelang'    => $lelang->id,
            'id_user'      => Auth::id(),
            'total'        => $nominal,
            'status'       => 'belum dibayar',
            'kode_deposit' => $kodeDeposit,
            'order_id'     => $orderId,
            'tgl_trx'      => now(),
        ]);
        try {
            $snapToken = $this->generateDepositSnapToken($deposit);
            $deposit->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Snap token creation failed: ' . $e->getMessage());
            $deposit->delete();
            return redirect()->back()->with('error', 'Gagal membuat sesi pembayaran. Silakan coba lagi.');
        }
        return redirect()->route('deposit.show', $kodeDeposit);
    }

    public function show($kodeDeposit)
    {
        $deposit = Deposit::with(['lelang.barang', 'user'])
                          ->where('kode_deposit', $kodeDeposit)
                          ->where('id_user', Auth::id())
                          ->firstOrFail();
        if($deposit->kode_deposit !== $kodeDeposit){
            return redirect()->back();
        }
        return view('deposit.show', compact('deposit'));
    }

    protected function generateDepositSnapToken(Deposit $deposit): string
    {
        $amount = $deposit->total;
        $nominal     = $deposit->lelang->barang->harga * 0.30;
        $kodeDeposit = $deposit->kode_deposit;
        $orderId     = $kodeDeposit;
        $safeMethods = [
            'bca_va', 'bni_va', 'bri_va', 'mandiri_va',
            'permata_va', 'other_va', 'credit_card',
        ];

        $smallMethods = ['gopay', 'shopeepay', 'qris', 'indomaret', 'alfamart'];

        // Di bawah 20jt: semua metode. 20jt ke atas: hanya yang aman
        $enabledPayments = $amount >= 20_000_000
            ? $safeMethods
            : array_merge($safeMethods, $smallMethods);

        $params = [
            'enabled_payments' => $enabledPayments,
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->nama_lengkap,
                'email'      => Auth::user()->email,
            ],
            'item_details' => [
                [
                    'id'       => $kodeDeposit,
                    'price'    => (int) $nominal,
                    'quantity' => 1,
                    'name'     => 'Deposit Lelang: ' . $deposit->lelang->barang->nama,
                ],
            ],
        ];
        Log::info('Generating Snap token for deposit', [
            'order_id' => $deposit->order_id,
            'amount' => $amount,
            'enabled_payments' => $enabledPayments,
        ]);
        return Snap::getSnapToken($params);
    }
}
