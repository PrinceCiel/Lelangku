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

class DepositController extends Controller
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

    public function create(Request $request)
    {
        $request->validate([
            'kode_lelang' => 'required|exists:lelangs,kode_lelang',
        ]);

        $lelang = Lelang::where('kode_lelang', $request->kode_lelang)->firstOrFail();

        // Cek sudah deposit belum
        $existing = Deposit::where('id_lelang', $lelang->id)
                            ->where('id_user', Auth::id())
                            ->whereIn('status', ['berhasil', 'pending'])
                            ->first();

        if ($existing) {
            return redirect()->back()->with('info', 'Anda sudah memiliki deposit aktif untuk lelang ini.');
        }

        $nominal    = $lelang->barang->harga * 0.10;
        $kodeDeposit = 'DEP-' . strtoupper(Str::random(8)) . '-' . time();
        $orderId     = $kodeDeposit; // prefix DEP- sudah cukup buat bedain di callback

        $deposit = Deposit::create([
            'id_lelang'    => $lelang->id,
            'id_user'      => Auth::id(),
            'total'        => $nominal,
            'status'       => 'belum dibayar',
            'kode_deposit' => $kodeDeposit,
            'order_id'     => $orderId,
            'tgl_trx'      => now(),
        ]);

        // Buat snap token Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $nominal,
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
                    'name'     => 'Deposit Lelang: ' . $lelang->barang->nama,
                ],
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $deposit->update(['snap_token' => $snapToken]);

        // Redirect ke halaman pembayaran deposit
        return redirect()->route('deposit.show', $kodeDeposit);
    }

    public function show($kodeDeposit)
    {
        $deposit = Deposit::with(['lelang.barang', 'user'])
                          ->where('kode_deposit', $kodeDeposit)
                          ->where('id_user', Auth::id())
                          ->firstOrFail();

        return view('deposit.show', compact('deposit'));
    }

    // Dipanggil Midtrans via webhook
    public function notificationHandler(Request $request)
    {
        \Log::info('Midtrans webhook:', $request->all());

        // Ambil langsung dari request, skip verify ke Midtrans API
        $orderId           = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus       = $request->input('fraud_status');

        // Verifikasi signature manual (opsional tapi recommended)
        $serverKey      = config('midtrans.server_key');
        $statusCode     = $request->input('status_code');
        $grossAmount    = $request->input('gross_amount');
        $expectedSig    = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $expectedSig = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        \Log::info('Signature check', [
            'order_id'      => $orderId,
            'status_code'   => $statusCode,
            'gross_amount'  => $grossAmount,
            'server_key'    => substr($serverKey, 0, 5) . '...',  // jangan log full
            'received_sig'  => $request->input('signature_key'),
            'expected_sig'  => $expectedSig,
        ]);

        // if ($request->input('signature_key') !== $expectedSig) {
        //     return response()->json(['message' => 'invalid signature'], 403);
        // }

        if (!str_starts_with($orderId, 'DEP-')) {
            return response()->json(['message' => 'bukan deposit'], 200);
        }

        $deposit = Deposit::where('order_id', $orderId)->first();
        if (!$deposit) {
            return response()->json(['message' => 'deposit not found'], 404);
        }

        if ($transactionStatus === 'settlement' ||
        ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            $deposit->update(['status' => 'berhasil', 'paid_at' => now()]);
        } elseif ($transactionStatus === 'pending') {
            $deposit->update(['status' => 'pending']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $deposit->update(['status' => 'gagal']);
        }

        return response()->json(['message' => 'ok'], 200);
    }
}
