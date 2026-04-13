<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Deposit;
use App\Models\KickActivity;
use App\Models\Lelang;
use App\Models\RefundRequest;
use App\Models\User;
use App\Services\LelangService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Midtrans\Config;
use Midtrans\Transaction;

class ReviewBidController extends Controller
{
    protected LelangService $lelangService;

    public function __construct(LelangService $lelangService)
    {
        $this->lelangService = $lelangService;

        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    // =========================================================================
    // Index — daftar lelang aktif + bid
    // =========================================================================
    public function index()
    {
        $lelang = Lelang::with([
            'barang',
            'bid' => fn($q) => $q->with('users')->latest('bid'),
        ])
        ->where('status', 'dibuka')
        ->get()
        ->map(function ($l) {
            $l->bid_tertinggi = $l->bid->max('bid') ?? $l->barang->harga;
            return $l;
        });

        return view('bid.index', compact('lelang'));
    }

    // =========================================================================
    // Detail user — bid, deteksi IP duplikat
    // =========================================================================
    public function userDetail($userId, $lelangId)
    {
        $user = User::findOrFail($userId);

        $bidDiLelangIni = Bid::where('id_user', $userId)
            ->where('id_lelang', $lelangId)
            ->latest()
            ->get();

        $ipDuplikat = collect();
        if (Schema::hasColumn('bids', 'ip_address')) {
            $ipsUser = Bid::where('id_user', $userId)
                ->where('id_lelang', $lelangId)
                ->whereNotNull('ip_address')
                ->pluck('ip_address')
                ->unique();

            if ($ipsUser->isNotEmpty()) {
                $ipDuplikat = Bid::with('users')
                    ->where('id_lelang', $lelangId)
                    ->where('id_user', '!=', $userId)
                    ->whereIn('ip_address', $ipsUser)
                    ->get()
                    ->unique('id_user')
                    ->values();
            }
        }

        return response()->json([
            'user'          => $user,
            'bid_di_lelang' => $bidDiLelangIni,
            'ip_duplikat'   => $ipDuplikat,
        ]);
    }

    // =========================================================================
    // Ban user
    // =========================================================================
    public function banUser(Request $request, $userId)
    {
        $request->validate(['reason' => 'nullable|string|max:255']);

        $user = User::findOrFail($userId);
        $user->update([
            'is_banned'     => true,
            'banned_at'     => now(),
            'banned_reason' => $request->reason ?? 'Diblokir oleh admin',
        ]);

        return response()->json(['message' => "User {$user->nama_lengkap} berhasil diblokir."]);
    }

    // =========================================================================
    // Unban user
    // =========================================================================
    public function unbanUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'is_banned'     => false,
            'banned_at'     => null,
            'banned_reason' => null,
        ]);

        return response()->json(['message' => "User {$user->nama_lengkap} berhasil di-unban."]);
    }

    // =========================================================================
    // Toggle suspicious
    // =========================================================================
    public function markSuspicious($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_suspicious' => !$user->is_suspicious]);

        $status = $user->is_suspicious ? 'ditandai suspicious' : 'dihapus tanda suspicious';

        return response()->json([
            'message'       => "User {$user->nama_lengkap} berhasil {$status}.",
            'is_suspicious' => $user->is_suspicious,
        ]);
    }

    // =========================================================================
    // Keluarkan user dari lelang + catat kick + refund deposit
    // =========================================================================
    public function removeFromLelang($userId, $lelangId)
    {
        $lelang = Lelang::findOrFail($lelangId);
        $user   = User::findOrFail($userId);

        // Catat kick history
        KickActivity::create([
            'id_user'   => $userId,
            'id_lelang' => $lelangId,
            'alasan'    => request('alasan') ?? null,
        ]);

        // Hapus semua bid user di lelang ini
        Bid::where('id_user', $userId)
            ->where('id_lelang', $lelangId)
            ->delete();

        // Handle refund deposit — hanya yang sudah berhasil bayar
        $deposit = Deposit::where('id_user', $userId)
            ->where('id_lelang', $lelangId)
            ->where('status', 'berhasil')
            ->first();

        if ($deposit) {
            $this->prosesRefundKick($deposit);
        }

        return response()->json([
            'message' => "User {$user->nama_lengkap} berhasil dikeluarkan dari lelang {$lelang->kode_lelang}.",
        ]);
    }

    // =========================================================================
    // Batalkan satu bid
    // =========================================================================
    public function cancelBid($bidId)
    {
        $bid = Bid::with('users')->findOrFail($bidId);
        $bid->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Bid Rp ' . number_format($bid->bid, 0, ',', '.') . " oleh {$bid->users->nama_lengkap} berhasil dibatalkan.",
        ]);
    }

    // =========================================================================
    // Helper: Proses refund deposit saat user di-kick
    // Coba otomatis via Midtrans (gopay/shopeepay/qris)
    // Gagal atau VA → buat RefundRequest manual
    // =========================================================================
    private function prosesRefundKick(Deposit $deposit): void
    {
        $autoMethods = ['gopay', 'shopeepay', 'qris'];
        $isAuto      = in_array($deposit->payment_type, $autoMethods);

        if ($isAuto) {
            try {
                $response   = Transaction::refund($deposit->order_id, [
                    'refund_key' => 'REFUND-KICK-' . $deposit->id . '-' . time(),
                    'amount'     => (int) $deposit->jumlah,
                    'reason'     => 'Deposit dikembalikan — user dikeluarkan dari lelang',
                ]);
                $statusCode = $response->status_code ?? null;

                if ($statusCode == 200) {
                    $deposit->update([
                        'status'      => 'refunded',
                        'refunded_at' => now(),
                    ]);
                    Log::info('Refund otomatis berhasil (kick)', [
                        'order_id' => $deposit->order_id,
                    ]);
                    return;
                }

                // Status bukan 200 → fallback manual
                $this->buatRefundManual($deposit, 'refund_api_gagal_kick');

            } catch (\Exception $e) {
                Log::warning('Refund Midtrans gagal (kick)', [
                    'order_id' => $deposit->order_id,
                    'error'    => $e->getMessage(),
                ]);
                $this->buatRefundManual($deposit, 'refund_api_gagal_kick');
            }

            return;
        }

        // VA / transfer bank → langsung antrian manual
        $this->buatRefundManual($deposit, 'kick_dari_lelang');
    }

    // =========================================================================
    // Helper: Buat RefundRequest manual
    // Didelegasi dari LelangService untuk konsistensi
    // =========================================================================
    private function buatRefundManual(Deposit $deposit, string $alasan): void
    {
        // Delegate ke LelangService biar logic tidak duplikat
        $this->lelangService->buatRefundManual($deposit, $alasan);
    }
}
