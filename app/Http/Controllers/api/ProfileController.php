<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Pemenang;
use App\Models\Struk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // app/Http/Controllers/Api/ProfileApiController.php

    public function dashboard()
    {
        $user = auth('sanctum')->user();

        // 1. Ambil data Struk (Pembayaran Lelang yang Menang)
        $struk = Struk::with(['pemenang.lelang.barang'])
            ->whereHas('pemenang', function ($q) use ($user) {
                $q->where('id_user', $user->id);
            })
            ->latest()
            ->get();

        // 2. Statistik Simple
        $totalBidUser = Bid::where('id_user', $user->id)->count();

        // Total lelang yang dimenangkan
        $totalLelangMenang = Pemenang::where('id_user', $user->id)->where('status_kandidat', 'menang')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'nama' => $user->nama_lengkap,
                    'email' => $user->email,
                    'status_verifikasi' => $user->status,
                    'foto' => $user->foto ? url(Storage::url($user->foto)) : null,
                ],
                'stats' => [
                    'total_bid' => $totalBidUser,
                    'lelang_dimenangkan' => $totalLelangMenang,
                    'total_tagihan' => $struk->where('status', 'belum dibayar')->count(),
                ],
                'recent_struk' => $struk->map(function ($s) {
                    return [
                        'kode_struk' => $s->kode_struk,
                        'barang' => $s->pemenang->lelang->barang->nama_barang ?? 'Barang Lelang',
                        'total_bayar' => $s->total_bayar,
                        'status' => $s->status,
                        'tanggal' => $s->created_at->format('d M Y'),
                    ];
                })
            ]
        ]);
    }

    public function personal()
    {
        // Mengambil user yang sedang login via Sanctum
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        $userWithDetail = User::with('datadiri')->find($user->id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $userWithDetail->id,
                'nama_lengkap' => $userWithDetail->nama_lengkap,
                'email' => $userWithDetail->email,
                'status_akun' => $userWithDetail->status, // 'Terverifikasi', 'diajukan', dsb
                'foto_profile' => $userWithDetail->foto
                    ? url(Storage::url($userWithDetail->foto))
                    : url('/images/default-avatar.png'),

                // Data tambahan dari tabel datadiris (hasil submit verifikasi)
                'detail_fisik' => $userWithDetail->datadiri ? [
                    'no_telp' => $userWithDetail->datadiri->no_telp,
                    'alamat' => $userWithDetail->datadiri->alamat,
                    'tanggal_lahir' => $userWithDetail->datadiri->tanggal_lahir,
                ] : null,
            ]
        ]);
    }
}
