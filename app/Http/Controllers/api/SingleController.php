<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Deposit;
use App\Models\Lelang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SingleController extends Controller
{
    public function single(string $kode)
    {
        $lelang = Lelang::with(['barang.kategori', 'bid.user'])
            ->where('kode_lelang', $kode)
            ->first();

        if (!$lelang) {
            return response()->json([
                'success' => false,
                'message' => 'Lelang tidak ditemukan.'
            ], 404);
        }

        // 2. Logika Kalkulasi Bid
        $allBids = $lelang->bid()->latest()->get();
        $bidTertinggiUser = $lelang->bid()->max('bid') ?? 0;
        $hargaSekarang = $lelang->barang->harga + $bidTertinggiUser;

        // 3. Logika Deposit
        $sudahDeposit = false;
        $nominalDepositRequired = $lelang->barang->harga * 0.30; // 30% dari harga awal

        if (auth('sanctum')->check()) {
            $sudahDeposit = Deposit::where('id_lelang', $lelang->id)
                ->where('id_user', auth('sanctum')->id())
                ->where('status', 'berhasil')
                ->exists();
        }

        // 4. Mapping Response
        $data = [
            'id_lelang' => $lelang->id,
            'kode_lelang' => $lelang->kode_lelang,
            'status' => $lelang->status,
            'harga_awal' => $lelang->barang->harga,
            'harga_saat_ini' => $hargaSekarang,
            'bid_tertinggi' => $bidTertinggiUser,
            'total_partisipan' => $allBids->unique('id_user')->count(),
            'total_bid_masuk' => $allBids->count(),
            'info_deposit' => [
                'sudah_deposit' => $sudahDeposit,
                'nominal_wajib' => $nominalDepositRequired,
                'keterangan' => 'User harus membayar deposit 30% untuk ikut lelang ini.'
            ],
            'barang' => [
                'nama' => $lelang->barang->nama,
                'deskripsi' => $lelang->barang->deskripsi, // Pastikan ada di DB
                'foto' => $lelang->barang->foto ? url('storage/' . $lelang->barang->foto) : null,
                'kategori' => $lelang->barang->kategori->nama ?? null,
            ],
            'riwayat_bid' => $allBids->map(function ($b) {
                return [
                    'nama_user' => $b->user->name,
                    'nominal_bid' => $b->bid,
                    'waktu' => $b->created_at->diffForHumans(), // Lebih enak buat mobile
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail lelang berhasil dimuat',
            'data' => $data
        ]);
    }

    public function storeBid(Request $request)
    {
        if (!auth('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Login dulu bro buat ikutan lelang.'
            ], 401);
        }

        $user = auth('sanctum')->user();

        if ($user->status !== 'Terverifikasi') {
            return response()->json([
                'success' => false,
                'message' => 'Akun anda belum terverifikasi. Mohon Verifikasi terlebih dahulu sebelum memasukkan bid.',
                'error_code' => 'UNVERIFIED_ACCOUNT'
            ], 403);
        }

        $lelang = Lelang::where('kode_lelang', $request->kode_lelang)->first();
        if (!$lelang) {
            return response()->json(['success' => false, 'message' => 'Data Lelang tidak ditemukan.'], 404);
        }

        $sudahDeposit = Deposit::where('id_lelang', $lelang->id)
            ->where('id_user', $user->id)
            ->where('status', 'berhasil')
            ->exists();

        if (!$sudahDeposit) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus melakukan deposit terlebih dahulu untuk mengikuti lelang ini.',
                'error_code' => 'DEPOSIT_REQUIRED'
            ], 403);
        }

        $bidTertinggi = Bid::where('id_lelang', $lelang->id)->max('bid');

        if ($bidTertinggi) {
            $minBid = $bidTertinggi + ($bidTertinggi * 0.05);
        } else {
            $minBid = $lelang->barang->harga;
        }

        $validator = Validator::make($request->all(), [
            'bid' => ['required', 'numeric', 'min:' . $minBid]
        ], [
            'bid.min' => 'Minimal bid harus Rp ' . number_format($minBid, 0, ',', '.'),
            'bid.required' => 'Nominal bid tidak boleh kosong!',
            'bid.numeric' => 'Bid harus berupa angka!'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bid = new Bid();
            $bid->id_lelang = $lelang->id;
            $bid->id_user = $user->id;
            $bid->bid = $request->bid;
            $bid->save();

            return response()->json([
                'success' => true,
                'message' => 'Bid berhasil dimasukkan!',
                'data' => [
                    'nama_user' => $user->name,
                    'nominal_bid' => $bid->bid,
                    'waktu' => $bid->created_at->format('H:i:s')
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memasukkan bid. Coba lagi nanti.',
            ], 500);
        }
    }

    public function poll(string $kode)
    {
        $lelang = Lelang::where('kode_lelang', $kode)->first();

        if (!$lelang) return response()->json(['message' => 'Lelang tidak ditemukan'], 404);

        $maxBid = Bid::where('id_lelang', $lelang->id)->max('bid') ?? 0;
        $countBid = Bid::where('id_lelang', $lelang->id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'harga_saat_ini' => $lelang->barang->harga + $maxBid,
                'total_bid' => $countBid,
                'status_lelang' => $lelang->status,
            ]
        ]);
    }

    public function bidHistory(string $kode)
    {
        $lelang = Lelang::where('kode_lelang', $kode)->first();
        if (!$lelang) return response()->json(['message' => 'Lelang hilang'], 404);

        $bids = Bid::with('users')
            ->where('id_lelang', $lelang->id)
            ->latest()
            ->take(10) // neken performa
            ->get();

        $data = $bids->map(function ($bid) {
            return [
                'nama' => $bid->user->name, // Sesuaikan field nama user
                'foto' => $bid->user->foto ? url(Storage::url($bid->user->foto)) : null,
                'nominal' => $bid->bid,
                'waktu_asli' => $bid->created_at->toDateTimeString(),
                'waktu_format' => $bid->created_at->diffForHumans(), // "2 menit yang lalu"
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
