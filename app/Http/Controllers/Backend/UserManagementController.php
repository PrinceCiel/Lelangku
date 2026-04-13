<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\StrikeActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with([
                'datadiri',
                'pemenang.lelang.barang',
                'bid',
                'strikes',
                'kicks.lelang.barang',
            ])
            ->where('IsAdmin', false)
            ->latest()
            ->get();

        return view('users.index', compact('users'));
    }

    // =========================================================================
    // Ban user
    // =========================================================================
    public function ban(Request $request, $id)
    {
        $request->validate(['reason' => 'nullable|string|max:255']);

        $user = User::findOrFail($id);
        $user->update([
            'is_banned'     => true,
            'banned_at'     => now(),
            'banned_reason' => $request->reason ?? 'Diblokir oleh admin',
        ]);

        Log::info('User di-ban oleh admin', ['id_user' => $id]);

        return redirect()->back()->with('success', "{$user->nama_lengkap} berhasil di-ban.");
    }

    // =========================================================================
    // Unban user
    // =========================================================================
    public function unban($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'is_banned'     => false,
            'banned_at'     => null,
            'banned_reason' => null,
        ]);

        Log::info('User di-unban oleh admin', ['id_user' => $id]);

        return response()->json(['message' => "{$user->nama_lengkap} berhasil di-unban."]);
    }

    // =========================================================================
    // Toggle suspicious
    // =========================================================================
    public function suspicious($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_suspicious' => !$user->is_suspicious]);

        $status = $user->is_suspicious ? 'ditandai suspicious' : 'dihapus tanda suspicious';

        return response()->json([
            'message'      => "{$user->nama_lengkap} berhasil {$status}.",
            'is_suspicious' => $user->is_suspicious,
        ]);
    }

    // =========================================================================
    // Tambah strike manual
    // =========================================================================
    public function tambahStrike(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|in:suspicious_activity,manual_admin',
        ]);

        $user     = User::findOrFail($id);
        $strikeKe = $user->strike_count + 1;

        StrikeActivity::create([
            'id_user'   => $user->id,
            'id_lelang' => null,
            'id_struk'  => null,
            'alasan'    => $request->alasan,
            'strike_ke' => $strikeKe,
        ]);

        $user->increment('strike_count');

        Log::info('Strike manual ditambahkan oleh admin', [
            'id_user'   => $id,
            'strike_ke' => $strikeKe,
            'alasan'    => $request->alasan,
        ]);

        return redirect()->back()->with('success', "Strike ke-{$strikeKe} berhasil ditambahkan untuk {$user->nama_lengkap}.");
    }
}
