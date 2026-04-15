<?php

namespace App\Http\Controllers;

use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundUserController extends Controller
{
    // =========================================================================
    // Halaman riwayat refund user
    // =========================================================================
    public function index()
    {
        $refunds = RefundRequest::with(['deposit.lelang.barang'])
            ->where('id_user', Auth::id())
            ->latest()
            ->get();

        return view('refund.index', compact('refunds'));
    }

    // =========================================================================
    // User isi rekening tujuan refund
    // =========================================================================
    public function isiRekening(Request $request, $id)
    {
        $request->validate([
            'rekening_tujuan' => 'required|string|max:50',
            'nama_pemilik'    => 'required|string|max:100',
            'bank_tujuan'     => 'required|string|max:50',
        ]);

        $refund = RefundRequest::where('id', $id)
            ->where('id_user', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $refund->update([
            'rekening_tujuan' => $request->rekening_tujuan,
            'nama_pemilik'    => $request->nama_pemilik,
            'bank_tujuan'     => $request->bank_tujuan,
        ]);

        return back()->with('success', 'Rekening tujuan berhasil disimpan. Admin akan segera memproses refund Anda.');
    }
}
