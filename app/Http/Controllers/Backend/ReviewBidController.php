<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Lelang;
use App\Models\User;
use App\Models\Bid;
use Illuminate\Http\Request;

class ReviewBidController extends Controller
{
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

    /**
     * Detail user — foto, nama, bid dia di lelang ini, deteksi IP sama
     */
    public function userDetail($userId, $lelangId)
    {
        $user = User::findOrFail($userId);

        // Semua bid user ini di lelang yang sedang dilihat, urutkan terbaru
        $bidDiLelangIni = Bid::where('id_user', $userId)
            ->where('id_lelang', $lelangId)
            ->latest()
            ->get();

        // Deteksi IP duplikat — user lain di lelang yang sama dengan IP sama
        $ipDuplikat = collect();
        if (\Schema::hasColumn('bids', 'ip_address')) {
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

    /**
     * Blokir user permanent
     */
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

    /**
     * Unban user
     */
    public function unbanUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_banned' => false, 'banned_at' => null, 'banned_reason' => null]);
        return response()->json(['message' => "User {$user->nama_lengkap} berhasil di-unban."]);
    }

    /**
     * Toggle suspicious
     */
    public function markSuspicious($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_suspicious' => !$user->is_suspicious]);
        $status = $user->is_suspicious ? 'ditandai suspicious' : 'dihapus tanda suspicious';
        return response()->json(['message' => "User {$user->nama_lengkap} berhasil {$status}.", 'is_suspicious' => $user->is_suspicious]);
    }

    /**
     * Keluarkan user dari lelang + refund deposit
     */
    public function removeFromLelang($userId, $lelangId)
    {
        $lelang = Lelang::findOrFail($lelangId);
        $user   = User::findOrFail($userId);

        // Hapus semua bid user di lelang ini
        Bid::where('id_user', $userId)->where('id_lelang', $lelangId)->delete();

        // Refund deposit — sesuaikan dengan model Deposit lo
        $deposit = \App\Models\Deposit::where('id_user', $userId)
            ->where('id_lelang', $lelangId)
            ->first();
        if ($deposit) {
            $deposit->update(['status' => 'refunded']);
        }

        return response()->json([
            'message' => "User {$user->nama_lengkap} berhasil dikeluarkan dari lelang {$lelang->kode_lelang} dan deposit di-refund.",
        ]);
    }

    /**
     * Batalkan satu bid
     */
    public function cancelBid($bidId)
    {
        $bid = Bid::with('users')->findOrFail($bidId);
        $bid->update(['status' => 'cancelled']);
        return response()->json([
            'message' => "Bid Rp " . number_format($bid->bid, 0, ',', '.') . " oleh {$bid->users->nama_lengkap} berhasil dibatalkan.",
        ]);
    }
}
