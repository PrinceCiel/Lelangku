<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Lelang;
use App\Models\Struk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrukController extends Controller
{
    public function index()
    {
        $user = auth('sanctum')->user();

        $lelang = Lelang::whereHas('pemenang', function($query) use ($user) {
            $query->where('id_user', $user->id)->where('status', 'menang');
        })
        ->with(['barang', 'struk']) // Struk cukup buat ambil status doang
        ->latest()
        ->get();

        if(!$lelang == null){
            return response()->json([
                'success' => false,
                'message' => 'Belum ada lelang yang dimenangkan.'
            ], 404);
        }

        if ($lelang->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada lelang yang dimenangkan.'
             ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lelang->map(function ($l) {
                return [
                    'id_lelang' => $l->id,
                    'kode_lelang' => $l->kode_lelang,
                    'nama_barang' => $l->barang->nama,
                    'foto_barang' => $l->barang->foto ? url(Storage::url($l->barang->foto)) : null,
                    'harga_menang' => $l->pemenang->bid ?? 0,
                    'status_pembayaran' => $l->struk->status ?? 'belum dibayar', // Buat label warna di UI
                    'tanggal_menang' => $l->pemenang->updated_at->format('d M Y'),
                ];
            })
        ]);
    }
    
    public function show($kode_struk)
    {
        $user = auth('sanctum')->user();

        // Cari struk berdasarkan kode_struk dan pastikan milik user yang login
        $struk = Struk::with(['lelang.barang', 'pemenang'])
            ->where('kode_struk', $kode_struk)
            ->where('user_id', $user->id)
            ->first();

        if (!$struk) {
            return response()->json([
                'success' => false,
                'message' => 'Data transaksi tidak ditemukan.'
            ], 404);
        }

        $lelang = $struk->lelang;
        $pemenang = $struk->pemenang;

        // Logic rincian biaya (untuk transparansi di aplikasi Flutter)
        $bidAkhir = $pemenang->bid;
        $adminFee = $bidAkhir * 0.05;
        $potonganDeposit = ($bidAkhir + $adminFee) - $struk->total;

        return response()->json([
            'success' => true,
            'data' => [
                'transaksi' => [
                    'kode_struk' => $struk->kode_struk,
                    'order_id' => $struk->order_id,
                    'tgl_transaksi' => $struk->tgl_trx->format('d M Y H:i'),
                    'status_pembayaran' => $struk->status,
                    'snap_token' => $struk->snap_token, // Langsung dipake Flutter Midtrans SDK
                ],
                'barang' => [
                    'nama' => $lelang->barang->nama ?? 'Produk Lelang',
                    'foto' => $lelang->barang->foto ? url(Storage::url($lelang->barang->foto)) : null,
                ],
                'rincian_pembayaran' => [
                    'harga_lelang' => (int) $bidAkhir,
                    'biaya_admin' => (int) $adminFee,
                    'potongan_deposit' => (int) max($potonganDeposit, 0),
                    'total_bayar' => (int) $struk->total,
                ],
                'info_kandidat' => [
                    'urutan' => $pemenang->urutan, // Biar user tau dia Pemenang 1 atau 2
                    'status' => $pemenang->status_kandidat,
                ]
            ]
        ]);
    }
}
