<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ItemSubmission;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ItemSubmissionController extends Controller
{
    public function getCategories()
    {
        $kategori = Kategori::select('id', 'nama_kategori')->get();
        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }

    /**
     * Ambil riwayat pengajuan user
     */
    public function index()
    {
        $user = auth('sanctum')->user();
        $submissions = ItemSubmission::where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $submissions->map(function($item) {
                return [
                    'id'            => $item->id,
                    'nama_barang'   => $item->nama_barang,
                    'status'        => $item->status,
                    'status_label'  => $item->status_label, // Langsung manggil accessor di model lo
                    'status_badge'  => $item->status_badge, // Langsung manggil accessor di model lo
                    'harga_awal'    => (int) $item->harga_ditawarkan,
                    'harga_deal'    => $item->harga_deal ? (int) $item->harga_deal : null,
                    'tgl_pengajuan' => $item->created_at->diffForHumans(),
                    // Ini si 'thumbnail' alias foto pertama
                    'thumbnail'     => !empty($item->foto_barang) && isset($item->foto_barang[0])
                                        ? url(Storage::url($item->foto_barang[0]))
                                        : null,
                ];
            })
        ]);
    }
    /**
     * Simpan pengajuan (Handle Multiple Image Upload)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang'      => 'required|string|max:255',
            'deskripsi'        => 'required|string|min:20',
            'harga_ditawarkan' => 'required|numeric|min:1000',
            'nomor_whatsapp'   => 'required|string|max:20',
            'nomor_telepon'    => 'required|string|max:20',
            'alamat_lengkap'   => 'required|string|min:10',
            'kategori_id'      => 'required|exists:kategoris,id',
            'foto_barang'      => 'required|array|min:1|max:5',
            'foto_barang.*'    => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('sanctum')->user();
        $fotoPaths = [];

        if ($request->hasFile('foto_barang')) {
            foreach ($request->file('foto_barang') as $foto) {
                // Simpan foto dengan folder berdasarkan ID user
                $path = $foto->store('submissions/' . $user->id, 'public');
                $fotoPaths[] = $path;
            }
        }

        $submission = ItemSubmission::create([
            'user_id'          => $user->id,
            'nama_barang'      => $request->nama_barang,
            'id_kategori'      => $request->kategori_id,
            'deskripsi'        => $request->deskripsi,
            'harga_ditawarkan' => $request->harga_ditawarkan,
            'foto_barang'      => $fotoPaths, // Pastikan cast array di Model
            'nomor_whatsapp'   => $request->nomor_whatsapp,
            'nomor_telepon'    => $request->nomor_telepon,
            'alamat_lengkap'   => $request->alamat_lengkap,
            'status'           => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan barang berhasil dikirim!',
            'data' => $submission
        ], 201);
    }
    /**
     * Detail pengajuan user
     */
    public function show($id)
    {
        $user = auth('sanctum')->user();

        // Eager loading kategori biar nggak berat (N+1 query)
        $submission = ItemSubmission::with('kategori')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $listFoto = [];
        if (is_array($submission->foto_barang)) {
            foreach ($submission->foto_barang as $path) {
                $listFoto[] = url(Storage::url($path));
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'                => $submission->id,
                'nama_barang'       => $submission->nama_barang,
                'kategori'          => $submission->kategori->nama_kategori ?? 'Umum',
                'deskripsi'         => $submission->deskripsi,
                'status_label'      => $submission->status_label,
                'status_badge'      => $submission->status_badge,
                'harga_ditawarkan'  => (int) $submission->harga_ditawarkan,
                'harga_deal'        => $submission->harga_deal ? (int) $submission->harga_deal : null,
                'catatan_admin'     => $submission->catatan_admin,
                'foto_barang'       => $listFoto,
                'alamat'            => $submission->alamat_lengkap,
                'kontak'            => [
                    'whatsapp' => $submission->nomor_whatsapp,
                    'telepon'  => $submission->nomor_telepon,
                ],
                'is_purchased'      => $submission->is_purchased,
                'dibuat_pada'       => $submission->created_at->format('d M Y H:i'),
            ]
        ]);
    }
}
