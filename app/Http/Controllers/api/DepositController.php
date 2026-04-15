<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Lelang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Midtrans\Snap;

class DepositController extends Controller
{
    // app/Http/Controllers/Api/DepositApiController.php

    public function createDeposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_lelang' => 'required|exists:lelangs,kode_lelang',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $lelang = Lelang::with('barang')->where('kode_lelang', $request->kode_lelang)->first();
        $user = auth('sanctum')->user();

        // 1. Cek apakah sudah ada deposit aktif/pending
        $existing = Deposit::where('id_lelang', $lelang->id)
                    ->where('id_user', $user->id)
                    ->whereIn('status', ['berhasil', 'pending', 'belum dibayar'])
                    ->first();

        if ($existing) {
            // Jika token hilang, generate ulang
            if (!$existing->snap_token) {
                try {
                    $snapToken = $this->generateDepositSnapToken($existing);
                    $existing->update(['snap_token' => $snapToken]);
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Gagal refresh token payment'], 500);
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Lanjutkan pembayaran deposit Anda.',
                'data' => $existing
            ]);
        }

        // 2. Buat Data Deposit Baru
        $nominal = $lelang->barang->harga * 0.30;
        $kodeDeposit = 'DEP-' . strtoupper(Str::random(8)) . '-' . time();

        try {
            $deposit = Deposit::create([
                'id_lelang'    => $lelang->id,
                'id_user'      => $user->id,
                'total'        => $nominal,
                'status'       => 'belum dibayar',
                'kode_deposit' => $kodeDeposit,
                'order_id'     => $kodeDeposit,
                'tgl_trx'      => now(),
            ]);

            $snapToken = $this->generateDepositSnapToken($deposit);
            $deposit->update(['snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'message' => 'Sesi pembayaran berhasil dibuat.',
                'data' => $deposit
            ], 201);

        } catch (\Exception $e) {
            if(isset($deposit)) $deposit->delete();
            return response()->json(['success' => false, 'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()], 500);
        }
    }

    public function showDeposit($kodeDeposit)
    {
        $deposit = Deposit::with(['lelang.barang'])
                    ->where('kode_deposit', $kodeDeposit)
                    ->where('id_user', auth('sanctum')->id())
                    ->first();

        if (!$deposit) {
            return response()->json(['success' => false, 'message' => 'Data deposit tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $deposit
        ]);
    }
    protected function generateDepositSnapToken(Deposit $deposit): string
    {
        $user = auth('sanctum')->user();
        $amount = $deposit->total;

        $params = [
            'transaction_details' => [
                'order_id'     => $deposit->kode_deposit,
                'gross_amount' => (int) $amount,
            ],
            'customer_details' => [
                'first_name' => $user->nama_lengkap,
                'email'      => $user->email,
            ],
            'item_details' => [
                [
                    'id'       => $deposit->kode_deposit,
                    'price'    => (int) $amount,
                    'quantity' => 1,
                    'name'     => 'Deposit Lelang: ' . $deposit->lelang->barang->nama,
                ],
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
