<?php

namespace App\Http\Controllers;

use App\Models\Datadiri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;
use Storage;
class VerifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::check()){
            if(Auth::user()->status == "Terverifikasi"){
                return redirect()->back();
            } else {
                return view('verifikasi.create');
            }
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_lahir' => 'date|required',
            'foto' => 'required',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
        ]);
        $verif = new Datadiri();
        $verif->id_user = Auth::user()->id;
        $verif->no_telp = $request->no_telp;
        $verif->tanggal_lahir = $request->tgl_lahir;
        if($request->hasFile('foto')) {
            $file = $request->file('foto');
            $randomName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('dokumen', $randomName, 'public');
            // memasukan nama image nya ke database
            $verif->foto_dokumen = $path;
        }
        $verif->alamat = $request->alamat;
        $verif->save();
        if($verif){
            $user_id = Auth::user()->id;
            $user = User::findOrFail($user_id);
            $user->status = 'diajukan';
            $user->save();
        }
        toast('Berhasil mengajukan verifikasi diri', 'success');
        return redirect()->route('home.user');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
