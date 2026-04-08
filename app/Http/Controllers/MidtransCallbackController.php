<?php

namespace App\Http\Controllers;
use App\Models\Struk;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\MidtransService;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransCallbackController extends Controller
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
    public function notificationHandler(Request $request)
    {
        Log::info('Midtrans Notification (Struk):', $request->all());

        $orderId           = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus       = $request->input('fraud_status');
        $statusCode        = $request->input('status_code');
        $grossAmount       = $request->input('gross_amount');

        // ── Verifikasi Signature ───────────────────────────────────────────
        $serverKey   = config('services.midtrans.server_key');
        $expectedSig = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($request->input('signature_key') !== $expectedSig) {
            Log::warning('Midtrans: invalid signature', ['order_id' => $orderId]);
            return response()->json(['message' => 'invalid signature'], 403);
        }

        // ── Route ke handler yang sesuai berdasarkan prefix order_id ──────
        // Deposit pakai prefix DEP-, Struk pakai prefix ORDER-
        if (str_starts_with($orderId, 'DEP-')) {
            return $this->handleDeposit($orderId, $transactionStatus, $fraudStatus);
        }

        if (str_starts_with($orderId, 'ORDER-')) {
            return $this->handleStruk($orderId, $transactionStatus, $fraudStatus);
        }

        Log::warning('Midtrans: unknown order_id prefix', ['order_id' => $orderId]);
        return response()->json(['message' => 'unknown order type'], 200);
    }

    // =========================================================================
    // STRUK HANDLER
    // =========================================================================
    private function handleStruk(string $orderId, string $transactionStatus, ?string $fraudStatus)
    {
        $struk = Struk::where('order_id', $orderId)->first();

        if (!$struk) {
            Log::error('Midtrans Struk: not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'struk not found'], 404);
        }

        // Jangan proses ulang kalau sudah final
        if (in_array($struk->status, ['berhasil', 'batal', 'gagal'])) {
            Log::info('Midtrans Struk: already final, skip', [
                'order_id' => $orderId,
                'status'   => $struk->status,
            ]);
            return response()->json(['message' => 'already final'], 200);
        }

        if ($transactionStatus === 'settlement' ||
            ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            // ── Pembayaran lunas ──
            $kodeUnik = 'RCPT-' . Str::upper(Str::random(4)) . '-' . Str::upper(Str::random(4)) . '-' . Str::upper(Str::random(4));
            while (Struk::where('kode_unik', $kodeUnik)->exists()) {
                $kodeUnik = 'RCPT-' . Str::upper(Str::random(4)) . '-' . Str::upper(Str::random(4)) . '-' . Str::upper(Str::random(4));
            }

            $struk->update([
                'status'    => 'berhasil',
                'kode_unik' => $kodeUnik,
            ]);
            Log::info('Midtrans Struk: berhasil', ['order_id' => $orderId]);

        } elseif ($transactionStatus === 'pending') {
            // ── Menunggu pembayaran (VA/transfer belum masuk) ──
            $struk->update(['status' => 'pending']);
            Log::info('Midtrans Struk: pending', ['order_id' => $orderId]);

        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            // ── Gagal / kadaluarsa ──
            $struk->update(['status' => 'gagal']);
            Log::info('Midtrans Struk: gagal', [
                'order_id'   => $orderId,
                'tx_status'  => $transactionStatus,
            ]);
        }

        return response()->json(['message' => 'ok'], 200);
    }

    // =========================================================================
    // DEPOSIT HANDLER
    // (dipindah ke sini biar satu endpoint, DepositController bisa dihapus
    //  notificationHandler-nya atau tetap dibiarkan sebagai fallback)
    // =========================================================================
    private function handleDeposit(string $orderId, string $transactionStatus, ?string $fraudStatus)
    {
        $deposit = Deposit::where('order_id', $orderId)->first();

        if (!$deposit) {
            Log::error('Midtrans Deposit: not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'deposit not found'], 404);
        }

        if (in_array($deposit->status, ['berhasil', 'gagal'])) {
            return response()->json(['message' => 'already final'], 200);
        }

        if ($transactionStatus === 'settlement' ||
            ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            $deposit->update(['status' => 'berhasil', 'paid_at' => now()]);
            Log::info('Midtrans Deposit: berhasil', ['order_id' => $orderId]);

        } elseif ($transactionStatus === 'pending') {
            $deposit->update(['status' => 'pending']);
            Log::info('Midtrans Deposit: pending', ['order_id' => $orderId]);

        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $deposit->update(['status' => 'gagal']);
            Log::info('Midtrans Deposit: gagal', ['order_id' => $orderId]);
        }

        return response()->json(['message' => 'ok'], 200);
    }

    /**
     * Handle redirect dari Midtrans setelah user selesai di halaman payment.
     * URL: GET /midtrans/finish
     */
    public function handleRedirect(Request $request)
    {
        $orderId           = $request->query('order_id');
        $transactionStatus = $request->query('transaction_status');

        if (!$orderId) {
            return redirect()->route('home.user');
        }

        // ── Cari struk berdasarkan order_id ───────────────────────────────
        $struk = Struk::where('order_id', $orderId)->first();

        if ($struk) {
            // Sinkron status dari query param (fallback, harusnya webhook sudah update duluan)
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $struk->update(['status' => 'berhasil']);
            } elseif ($transactionStatus === 'pending') {
                $struk->update(['status' => 'pending']);
            }

            return redirect()
                ->route('struk.detail', ['kodestruk' => $struk->kode_struk])
                ->with('info', 'Status pembayaran: ' . $transactionStatus);
        }

        return redirect()->route('home.user');
    }
}
