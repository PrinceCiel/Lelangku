<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        $title = 'Hapus Data!';
        $text = "Apakah anda yakin?";
        confirmDelete($title, $text);
        return view('kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        $kategori = new Kategori();
        $kategori->nama = $request->nama;
        $kategori->slug = Str::slug($request->nama, '-');
        if($request->hasFile('foto')) {
            $file = $request->file('foto');
            $randomName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('kategoris', $randomName, 'public');
            // memasukan nama image nya ke database
            $kategori->foto = $path;
        }
        $kategori->save();
        return redirect()->route('backend.kategori.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $kategori = Kategori::where('slug', $slug)->first();
        return view('kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $kategori = Kategori::where('slug', $slug)->first();
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->nama = $request->nama;
        $kategori->slug = Str::slug($request->nama, '-');
        if($request->hasFile('foto')) {
            Storage::disk('public')->delete($kategori->foto);
            $file = $request->file('foto');
            $randomName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('kategoris', $randomName, 'public');
            // memasukan nama image nya ke database
            $kategori->foto = $path;
        }
        $kategori->save();
        return redirect()->route('backend.kategori.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        toast('Data berhasil dihapus', 'success');
        return redirect()->route('backend.kategori.index', ['toast' => 'deleted']);
    }
}
