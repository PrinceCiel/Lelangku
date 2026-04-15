<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Bid;
use App\Models\Lelang;
use App\Models\Pemenang;
use App\Models\Struk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Storage;

class SingleController extends Controller
{

    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }
    public function index()
    {
        $user_id = Auth::user()->id;
        $pemenang = Pemenang::where('id_user', $user_id)->get();
        $lelang = Lelang::whereIn('id', function($query) use ($user_id) {
            $query->select('id_lelang')
              ->from('pemenangs')
              ->where('id_user', $user_id);
        })->with(['barang', 'struk', 'pemenang'])->get();
        // dd($lelang);
        // $struk = Struk::where('id_user', $pemenang->id_user);
        return view('order', compact('lelang'));
    }

    public function show(string $kode)
    {
        $lelang = Lelang::where('kode_lelang', $kode)->first();
        // dd($lelang);
        $bid = Bid::where('id_lelang', $lelang->id)->latest()->get();
        $bidtertinggi = Bid::where('id_lelang', $lelang->id)->max('bid');
        if(! $bidtertinggi){
            $bidtertinggi = $lelang->barang->harga;
        }
        $countBid = $bid->count();
        $TotalBid = $bid->sum(function($item) {
            return $item->bid;
        });
        $title = 'Membeli Item?';
        $text = "Apakah anda yakin?";
        confirmDelete($title, $text);
        $sudahDeposit = false;
        $nominalDeposit = $lelang->barang->harga * 0.30;

        if (Auth::check()) {
            $sudahDeposit = \App\Models\Deposit::where('id_lelang', $lelang->id)
                                ->where('id_user', Auth::id())
                                ->where('status', 'berhasil')
                                ->exists();
        }
        return view('single', compact('bidtertinggi','lelang', 'bid', 'countBid','sudahDeposit', 'nominalDeposit'));
    }

    public function store(Request $request)
    {
        if(Auth::check()){
            if(Auth::user()->status == 'Terverifikasi'){
                $lelang = Lelang::where('kode_lelang', $request->kode_lelang)->first();
                $bidTertinggi = Bid::where('id_lelang', $lelang->id)->max('bid');
                if($bidTertinggi){
                    $minBid = $bidTertinggi + ($bidTertinggi * 0.05);
                } else {
                    $minBid = $lelang->barang->harga;
                }
                $request->validate([
                    'bid' => ['required', 'numeric', 'min:' . $minBid]
                ], [
                    'bid.min' => 'Minimal bid harus lebih dari Rp' . number_format($minBid, 0, ',', '.'),
                    'bid.required' => 'Bid tidak boleh kosong!',
                    'bid.numeric' => 'Bid harus berupa angka!'
                ]);
                $bid = new Bid();
                $bid->id_lelang = $lelang->id;
                $bid->id_user = Auth::user()->id;
                $bid->bid = $request->bid;
                $bid->save();
                return redirect()->route('lelang.show', $request->kode_lelang);
            } else{
                return redirect()->route('verifikasi.index');
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function poll(string $kode)
    {
        $lelang = Lelang::where('kode_lelang', $kode)->firstOrFail();

        $bidtertinggi = Bid::where('id_lelang', $lelang->id)->max('bid');
        if (! $bidtertinggi) {
            $bidtertinggi = $lelang->barang->harga;
        }

        $countBid = Bid::where('id_lelang', $lelang->id)->count();

        return response()->json([
            'bidtertinggi' => $bidtertinggi,
            'countBid' => $countBid,
            'status' => $lelang->status,
        ]);
    }

    public function bidHistory(string $kode)
    {
        $lelang = Lelang::where('kode_lelang', $kode)->firstOrFail();

        $bids = Bid::with('users')
            ->where('id_lelang', $lelang->id)
            ->latest()
            ->take(20)
            ->get();

        return response()->json(
            $bids->map(function ($bid) {
                return [
                    'nama' => $bid->users->nama_lengkap,
                    'foto' => Storage::url($bid->users->foto),
                    'tanggal' => $bid->created_at->format('Y-m-d'),
                    'jam' => $bid->created_at->format('H:i'),
                    'bid' => number_format($bid->bid, 0, ',', '.'),
                ];
            })
        );
    }


}
