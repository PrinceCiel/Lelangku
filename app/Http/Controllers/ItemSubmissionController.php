<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemSubmission;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman form ajukan barang
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('submissions.create', compact('kategori'));
    }

    /**
     * Simpan pengajuan barang dari user
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'      => 'required|string|max:255',
            'deskripsi'        => 'required|string|min:20',
            'harga_ditawarkan' => 'required|numeric|min:1000',
            'nomor_whatsapp'   => 'required|string|max:20',
            'nomor_telepon'    => 'required|string|max:20',
            'alamat_lengkap'   => 'required|string|min:10',
            'foto_barang'      => 'required|array|min:1|max:5',
            'kategori'         => 'required|string|max:255',
            'foto_barang.*'    => 'required|image|mimes:jpg,jpeg,png,webp|max:3072', // 3MB per foto
        ], [
            'nama_barang.required'      => 'Nama barang wajib diisi.',
            'deskripsi.min'             => 'Deskripsi minimal 20 karakter.',
            'harga_ditawarkan.required' => 'Harga yang ditawarkan wajib diisi.',
            'harga_ditawarkan.min'      => 'Harga minimal Rp 1.000.',
            'nomor_whatsapp.required'   => 'Nomor WhatsApp wajib diisi.',
            'nomor_telepon.required'    => 'Nomor telepon wajib diisi.',
            'alamat_lengkap.required'   => 'Alamat lengkap wajib diisi.',
            'foto_barang.required'      => 'Minimal 1 foto barang wajib diunggah.',
            'foto_barang.max'           => 'Maksimal 5 foto barang.',
            'foto_barang.*.image'       => 'File harus berupa gambar.',
            'foto_barang.*.max'         => 'Ukuran foto maksimal 3MB.',
        ]);

        // Upload semua foto
        $fotoPaths = [];
        foreach ($request->file('foto_barang') as $foto) {
            $path = $foto->store('submissions/' . Auth::id(), 'public');
            $fotoPaths[] = $path;
        }
        // dd($request->kategori, $fotoPaths);
        ItemSubmission::create([
            'user_id'          => Auth::id(),
            'nama_barang'      => $request->nama_barang,
            'id_kategori'      => $request->kategori,
            'deskripsi'        => $request->deskripsi,
            'harga_ditawarkan' => $request->harga_ditawarkan,
            'foto_barang'      => $fotoPaths,
            'nomor_whatsapp'   => $request->nomor_whatsapp,
            'nomor_telepon'    => $request->nomor_telepon,
            'alamat_lengkap'   => $request->alamat_lengkap,
            'status'           => ItemSubmission::STATUS_PENDING,
        ]);

        return redirect()->route('submissions.index')
            ->with('success', 'Pengajuan barang berhasil dikirim! Admin akan segera menghubungi Anda untuk proses review.');
    }

    /**
     * Halaman "Pengajuan Saya" — list semua pengajuan milik user
     */
    public function index()
    {
        $submissions = ItemSubmission::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('submissions.index', compact('submissions'));
    }

    /**
     * Detail pengajuan user
     */
    public function show(ItemSubmission $submission)
    {
        // Pastikan hanya pemilik yang bisa lihat
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        return view('submissions.show', compact('submission'));
    }
}
