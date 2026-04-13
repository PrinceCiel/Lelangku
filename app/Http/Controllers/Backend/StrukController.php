<?php

namespace App\Http\Controllers\Backend;
use App\Services\MidtransService;
use App\Http\Controllers\Controller;
use App\Models\Pemenang;
use App\Models\Struk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StrukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $struk = Struk::where('status', 'pending')->latest()->get();
        return view('struk.index', compact('struk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $kode)
    {
        $request->validate(['status' => 'required']);
        $struk = Struk::where('kode_struk', $kode)->first();
        $struk->status = $request->status;
        $kodeUnik = 'RCPT-' . Str::upper(Str::random(4)) . '-' .Str::upper(Str::random(4)) . '-' . Str::upper(Str::random(4));

        while (Struk::where('kode_unik', $kodeUnik)->exists()) {
            $kodeUnik = 'L' . Str::upper(Str::random(10));
        }
        $struk->kode_unik = $kodeUnik;
        $struk->save();
        toast('Status berhasil diupdate', 'success');
        return redirect()->route('backend.struk.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
