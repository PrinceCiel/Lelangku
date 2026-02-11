<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lelang;
use App\Models\Struk;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        $lelangjadwal = Lelang::where('status', 'ditutup')->get();
        $lelangberes = Lelang::where('status', 'selesai')->get();
        $transaksiberes = Struk::where('status', 'berhasil')->get();
        $totaltransaksi = $transaksiberes->count();
        $totalberes = $lelangberes->count();
        $totaljadwal = $lelangjadwal->count();
        $totalbarang = $barang->count();
        $barangready = Barang::where('jumlah', '>', 0)->get();
        $totalaset = $barangready->sum(function($item) {
            return $item->harga * $item->jumlah;
        });
        $totalbarangready = Barang::sum('jumlah');
        // Ambil barang yang jumlahnya lebih dari 0
        $kategori = Barang::with('kategori')
            ->where('jumlah', '>', 0)
            ->get()
            ->sortByDesc(function($item) {
                // Urutkan berdasarkan total nilai (harga * jumlah)
                return $item->harga * $item->jumlah;
            });
        // Hitung total aset per nama kategori untuk Chart
        $chartData = $kategori->groupBy(function($item) {
            return $item->kategori->nama ?? 'Tanpa Kategori';
        })->map(function($items) {
            return $items->sum(function($item) {
                return $item->harga * $item->jumlah;
            });
        });

        // Hasilnya nanti formatnya: ['Elektronik' => 1000000, 'Fashion' => 500000]
        // dd($kategori);
        return view('backend', compact('totalbarang', 'totalberes', 'totaljadwal', 'totaltransaksi', 'barangready', 'totalaset', 'totalbarangready', 'kategori', 'chartData'));
    }
}
