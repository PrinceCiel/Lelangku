<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use App\Models\Barang;
use App\Models\Item;
use App\Models\ItemSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubmissionAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', IsAdmin::class]);
    }

    /**
     * List semua pengajuan masuk
     */
    public function index(Request $request)
    {
        $query = ItemSubmission::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        $submissions = $query->paginate(15)->withQueryString();
        $counts = [
            'pending'      => ItemSubmission::pending()->count(),
            'under_review' => ItemSubmission::underReview()->count(),
            'approved'     => ItemSubmission::approved()->count(),
            'rejected'     => ItemSubmission::rejected()->count(),
            'purchased'    => ItemSubmission::purchased()->count(),
        ];

        return view('submissions.admin.index', compact('submissions', 'counts'));
    }

    /**
     * Detail pengajuan + form aksi admin
     */
    public function show(ItemSubmission $submission)
    {
        $submission->load('user', 'reviewer');
        return view('submissions.admin.show', compact('submission'));
    }

    /**
     * Update status: under_review / approve (+ set harga deal) / reject
     */
    public function updateStatus(Request $request, ItemSubmission $submission)
    {
        $request->validate([
            'action'        => 'required|in:under_review,approve,reject',
            'catatan_admin' => 'nullable|string|max:1000',
            'harga_deal'    => 'required_if:action,approve|nullable|numeric|min:1000',
        ], [
            'harga_deal.required_if' => 'Harga deal wajib diisi saat menyetujui pengajuan.',
        ]);

        $newStatus = match ($request->action) {
            'under_review' => ItemSubmission::STATUS_UNDER_REVIEW,
            'approve'      => ItemSubmission::STATUS_APPROVED,
            'reject'       => ItemSubmission::STATUS_REJECTED,
        };

        $submission->update([
            'status'        => $newStatus,
            'catatan_admin' => $request->catatan_admin,
            'harga_deal'    => $request->action === 'approve' ? $request->harga_deal : null,
            'reviewed_by'   => Auth::id(),
            'reviewed_at'   => now(),
        ]);

        $messages = [
            ItemSubmission::STATUS_UNDER_REVIEW => 'Pengajuan dipindah ke status "Sedang Ditinjau".',
            ItemSubmission::STATUS_APPROVED     => 'Pengajuan disetujui! Silakan lakukan pembayaran ke user.',
            ItemSubmission::STATUS_REJECTED     => 'Pengajuan berhasil ditolak.',
        ];

        return redirect()->route('backend.submissions.show', $submission)
            ->with('success', $messages[$newStatus]);
    }

    /**
     * Tandai sudah bayar → status purchased
     * Setelah ini, convert ke item lelang
     */
    public function markAsPurchased(Request $request, ItemSubmission $submission)
    {
        if ($submission->status !== ItemSubmission::STATUS_APPROVED) {
            return back()->with('error', 'Hanya pengajuan berstatus "Disetujui" yang bisa diproses pembayaran.');
        }

        DB::transaction(function () use ($submission) {
            // 1. Tandai sudah dibeli
            $submission->update([
                'status'       => ItemSubmission::STATUS_PURCHASED,
                'is_purchased' => true,
                'paid_at'      => now(),
            ]);
            $slug = Str::slug($submission->nama_barang) . '-';
            // 2. Convert ke item lelang
            $barang = Barang::create([
                'nama'                 => $submission->nama_barang,
                'id_kategori'          => $submission->id_kategori,
                'deskripsi'            => $submission->deskripsi,
                'harga'                => (int) $submission->harga_deal,
                'foto'                 => $submission->foto_barang[0] ?? null,
                // 'foto_barang'          => $submission->foto_barang,
                'kondisi'              => 'Bekas',   // default, bisa diubah admin
                'jenis_barang'         => '-',       // admin isi manual di edit barang
                'jumlah'               => 1,
                'slug'                 => $slug,
                // 'source_submission_id' => $submission->id,
                // 'owner_type'           => 'platform',
            ]);
            $submission->update(['converted_barang_id' => $barang->id]);
        });

        return redirect()->route('backend.submissions.show', $submission)
            ->with('success', 'Pembayaran dikonfirmasi! Barang otomatis masuk ke daftar item lelang.');
    }
}
