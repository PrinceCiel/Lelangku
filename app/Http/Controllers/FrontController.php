<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Kategori;
use App\Models\Lelang;
use App\Models\Struk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('frontend', compact('kategori'));
    }

    public function show(string $slug)
    {
        $kategori = Kategori::where('slug', $slug)->first();
        return view('kategori', compact('kategori'));
    }
    public function search(Request $request)
    {
        $katakunci = $request->search;
        if (!$katakunci) {
            toast('Masukkan kata kunci pencarian.', 'error');
            return redirect()->back();
        }

        $hasil = Lelang::whereHas('barang', function ($query) use ($katakunci) {
        $query->where('nama', 'like', '%' . $katakunci . '%')
              ->orWhereHas('kategori', function ($q) use ($katakunci) {
                  $q->where('nama', 'like', '%' . $katakunci . '%');
              });
        })->with('barang.kategori')->where('status', 'dibuka')->get();

        if ($hasil->isEmpty()) {
            toast('Tidak ada hasil.', 'warning');
            return redirect()->back();

        }
        foreach($hasil as $lelang){
            $bid = Bid::where('id_lelang', $lelang->id)->get();
            $TotalBidUser = $bid->sum(function($item) {
                return $item->bid;
            });
            $TotalBid = $lelang->barang->harga + $TotalBidUser;
        }
        return view('hasil', compact('hasil', 'katakunci', 'TotalBid'));
    }
    public function dashboard()
    {
        $userdata = User::where('id', Auth::user()->id)->first();
        $struk = Struk::with('pemenang')->whereHas('pemenang', function ($q) {
            $q->where('id_user', Auth::id());})->get();
        $biduser = Bid::where('id_user', Auth::id())->get();
        $user_id = Auth::user()->id;
        $lelang = Lelang::whereIn('id', function($query) use ($user_id) {
            $query->select('id_lelang')
              ->from('pemenangs')
              ->where('id_user', $user_id);
        })->with(['barang', 'struk', 'pemenang'])->get();
        $totalbiduser = $biduser->count();
        $totallelang = $lelang->count();
        return view('profile.dashboard', compact('userdata', 'struk', 'totalbiduser', 'totallelang'));
    }
    public function personal()
    {
        $userdata = User::where('id', Auth::user()->id)->first();
        return view('profile.profile', compact('userdata'));
    }
}
