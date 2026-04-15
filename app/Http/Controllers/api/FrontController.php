<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Lelang;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $kategori = Kategori::with(['barang.lelang'])->get();

        // Mapping supaya JSON-nya bersih
        $data = $kategori->map(function ($kat) {
            return [
                'id_kategori' => $kat->id,
                'nama' => $kat->nama,
                'foto_kategori' => $kat->foto ? url('storage/' . $kat->foto) : null,
                'daftar_barang' => $kat->barang->map(function ($b) {
                    return [
                        'id_barang' => $b->id,
                        'nama_produk' => $b->nama,
                        'foto' => url('storage/' . $b->foto), // Full URL buat Mobile
                        'data_lelang' => $b->lelang->map(function ($l) {
                            return [
                                'status' => $l->status,
                                'detail_lelang' => [
                                    'id_lelang' => $l->id,
                                    'kode_lelang' => $l->kode_lelang,
                                    'harga_awal' => $l->barang->harga,
                                    'status' => $l->status,
                                    'tgl_mulai' => $l->created_at->format('d-m-Y')
                                ]
                            ];
                        })
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    public function showKategori(string $slug)
    {
        // Ambil kategori berdasarkan slug dengan eager loading barang dan lelang
        $kategori = Kategori::with(['barang.lelang'])
            ->where('slug', $slug)
            ->first();

        // Proteksi kalau slug gak ada di DB
        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan.'
            ], 404);
        }

        // Mapping data supaya strukturnya rapi buat mobile
        $data = [
            'id_kategori' => $kategori->id,
            'nama' => $kategori->nama,
            'slug' => $kategori->slug,
            'daftar_barang' => $kategori->barang->map(function ($b) {
                return [
                    'id_barang' => $b->id,
                    'nama_produk' => $b->nama,
                    'foto' => $b->foto ? url('storage/' . $b->foto) : null,
                    'data_lelang' => $b->lelang->map(function ($l) {
                        return [
                            'status' => $l->status,
                            'detail_lelang' => [
                                'id_lelang' => $l->id,
                                'kode_lelang' => $l->kode_lelang,
                                'harga_awal' => $l->barang->harga ?? 0,
                                'tgl_mulai' => $l->created_at->format('d-m-Y')
                            ]
                        ];
                    })
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail kategori ' . $kategori->nama,
            'data' => $data
        ]);
    }
    public function search(Request $request)
    {
        $katakunci = $request->search;

        // 1. Validasi Input
        if (!$katakunci) {
            return response()->json([
                'success' => false,
                'message' => 'Kata kunci pencarian tidak boleh kosong.'
            ], 400);
        }

        // 2. Query Pencarian (Lelang -> Barang -> Kategori)
        $hasil = Lelang::whereHas('barang', function ($query) use ($katakunci) {
            $query->where('nama', 'like', '%' . $katakunci . '%')
                ->orWhereHas('kategori', function ($q) use ($katakunci) {
                    $q->where('nama', 'like', '%' . $katakunci . '%');
                });
        })
        ->with(['barang.kategori', 'bid']) // Eager load bids sekalian biar gak N+1 query
        ->where('status', 'dibuka')
        ->get();

        // 3. Handle jika kosong
        if ($hasil->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Hasil pencarian "' . $katakunci . '" nggak ketemu.',
                'data' => []
            ], 404);
        }

        // 4. Mapping Data & Kalkulasi Harga (TotalBid)
        $data = $hasil->map(function($lelang) {
            // Hitung total bid saat ini
            $totalBidUser = $lelang->bid->sum('bid');
            $hargaSekarang = ($lelang->barang->harga ?? 0) + $totalBidUser;

            return [
                'id_lelang' => $lelang->id,
                'status' => $lelang->status,
                'harga_saat_ini' => $hargaSekarang,
                'total_bid_masuk' => $lelang->bid->count(),
                'barang' => [
                    'id' => $lelang->barang->id,
                    'nama' => $lelang->barang->nama,
                    'foto' => $lelang->barang->foto ? url('storage/' . $lelang->barang->foto) : null,
                    'harga_awal' => $lelang->barang->harga,
                    'kategori' => $lelang->barang->kategori->nama ?? null,
                ],
                'tgl_dibuka' => $lelang->created_at->format('d-m-Y H:i')
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Ditemukan ' . $data->count() . ' hasil untuk "' . $katakunci . '"',
            'data' => $data
        ]);
    }
}
