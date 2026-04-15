<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Midtrans\Config;
use Midtrans\Transaction;

class RefundController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    // =========================================================================
    // Index — daftar semua refund request
    // =========================================================================
    public function index()
    {
        $refunds = RefundRequest::with(['user', 'deposit.lelang.barang'])
            ->latest()
            ->get();

        $totalPending   = $refunds->where('status', 'pending')->count();
        $totalDiproses  = $refunds->where('status', 'diproses')->count();
        $totalSelesai   = $refunds->where('status', 'selesai')->count();
        $totalNilai     = $refunds->whereIn('status', ['pending', 'diproses'])->sum('jumlah');

        return view('refund.admin.index', compact(
            'refunds', 'totalPending', 'totalDiproses', 'totalSelesai', 'totalNilai'
        ));
    }

    // =========================================================================
    // Proses refund manual — admin upload bukti + catatan
    // =========================================================================
    public function proses(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|max:2048',
            'catatan_admin'  => 'nullable|string|max:500',
        ]);

        $refund = RefundRequest::findOrFail($id);

        if (!in_array($refund->status, ['pending', 'diproses'])) {
            return back()->with('error', 'Refund ini sudah final.');
        }

        // Upload bukti transfer
        $path = $request->file('bukti_transfer')->store('refund/bukti', 'public');

        $refund->update([
            'status'         => 'selesai',
            'bukti_transfer' => $path,
            'catatan_admin'  => $request->catatan_admin,
            'processed_at'   => now(),
        ]);

        // Update status deposit juga
        $refund->deposit->update([
            'status'      => 'refunded',
            'refunded_at' => now(),
        ]);

        Log::info('Refund manual diproses oleh admin', ['id_refund' => $id]);

        return back()->with('success', 'Refund berhasil diproses.');
    }

    // =========================================================================
    // Set status jadi diproses (admin mulai transfer)
    // =========================================================================
    public function mulaiProses($id)
    {
        $refund = RefundRequest::findOrFail($id);

        if ($refund->status !== 'pending') {
            return back()->with('error', 'Refund ini sudah diproses.');
        }

        $refund->update(['status' => 'diproses']);

        return back()->with('success', 'Refund ditandai sedang diproses.');
    }

    // =========================================================================
    // Tolak refund
    // =========================================================================
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ]);

        $refund = RefundRequest::findOrFail($id);

        if (!in_array($refund->status, ['pending', 'diproses'])) {
            return back()->with('error', 'Refund ini sudah final.');
        }

        $refund->update([
            'status'        => 'gagal',
            'catatan_admin' => $request->catatan_admin,
            'processed_at'  => now(),
        ]);

        Log::info('Refund ditolak oleh admin', ['id_refund' => $id]);

        return back()->with('success', 'Refund berhasil ditolak.');
    }

    // =========================================================================
    // Coba refund ulang via Midtrans (untuk yang refund_api_gagal)
    // =========================================================================
    public function retryMidtrans($id)
    {
        $refund  = RefundRequest::with('deposit')->findOrFail($id);
        $deposit = $refund->deposit;

        if (!$deposit) {
            return back()->with('error', 'Deposit tidak ditemukan.');
        }

        try {
            $response = Transaction::refund($deposit->order_id, [
                'refund_key' => 'REFUND-RETRY-' . $deposit->id . '-' . time(),
                'amount'     => (int) $deposit->jumlah,
                'reason'     => 'Deposit dikembalikan',
            ]);

            $statusCode = $response->status_code ?? null;

            if ($statusCode == 200) {
                $deposit->update([
                    'status'      => 'refunded',
                    'refunded_at' => now(),
                ]);
                $refund->update([
                    'status'       => 'selesai',
                    'processed_at' => now(),
                    'catatan_admin' => 'Refund otomatis berhasil via Midtrans (retry)',
                ]);

                Log::info('Refund retry Midtrans berhasil', ['order_id' => $deposit->order_id]);
                return back()->with('success', 'Refund berhasil diproses via Midtrans.');
            }

            return back()->with('error', 'Midtrans menolak refund. Proses manual.');

        } catch (\Exception $e) {
            Log::warning('Refund retry Midtrans gagal', [
                'order_id' => $deposit->order_id,
                'error'    => $e->getMessage(),
            ]);
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
