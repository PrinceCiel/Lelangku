<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lelang;
use App\Models\Pemenang;
use App\Models\Struk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Transaction;

class GagalBayarController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    // =========================================================================
    // Riwayat Gagal
    // =========================================================================
    public function riwayat()
    {
        $struks = Struk::with(['lelang.barang', 'pemenang.user'])
            ->where('status', 'gagal')
            ->latest()
            ->get();

        $struks->each(function ($struk) {
            $lelang = $struk->lelang;
            if (!$lelang) {
                $struk->perlu_tindak = false;
                return;
            }
            $adaMenang = $lelang->pemenang()->where('status_kandidat', 'menang')->exists();
            $adaAktif  = $lelang->pemenang()->where('status_kandidat', 'aktif')->exists();
            $struk->perlu_tindak = !$adaMenang && !$adaAktif;
        });

        $totalGagal      = $struks->count();
        $perluTindak     = $struks->where('perlu_tindak', true)->count();
        $sudahDitindak   = $struks->where('perlu_tindak', false)->count();
        $totalNilaiGagal = $struks->sum('total');

        return view('gagalbayar.riwayat', compact(
            'struks', 'totalGagal', 'perluTindak', 'sudahDitindak', 'totalNilaiGagal'
        ));
    }

    public function hapusStruk($kode)
    {
        $struk = Struk::where('kode_struk', $kode)->where('status', 'gagal')->firstOrFail();
        $struk->delete();
        return back()->with('success', 'Struk gagal berhasil dihapus dari riwayat.');
    }

    // =========================================================================
    // Penyelesaian
    // =========================================================================
    public function penyelesaian()
    {
        // Lelang draft = semua kandidat gugur, perlu dijadwalkan ulang
        $lelangDraft = Lelang::with(['barang', 'pemenang.user'])
            ->where('status', 'draft')
            ->whereHas('pemenang')
            ->latest()
            ->get();

        // Lelang yang kandidat 2 sedang aktif (proses bayar)
        $lelangProses = Lelang::with(['barang', 'pemenang.user'])
            ->where('status', 'selesai')
            ->whereHas('pemenang', fn($q) => $q->where('urutan', 1)->where('status_kandidat', 'gugur'))
            ->whereHas('pemenang', fn($q) => $q->where('urutan', 2)->where('status_kandidat', 'aktif'))
            ->latest()
            ->get();

        // Lelang yang kandidat 1 masih aktif (bisa di-alih manual)
        $lelangAktif = Lelang::with(['barang', 'pemenang.user'])
            ->where('status', 'selesai')
            ->whereHas('pemenang', fn($q) => $q->where('urutan', 1)->where('status_kandidat', 'aktif'))
            ->whereHas('pemenang', fn($q) => $q->where('urutan', 2)->where('status_kandidat', 'standby'))
            ->latest()
            ->get();

        return view('gagalbayar.penyelesaian', compact(
            'lelangDraft', 'lelangProses', 'lelangAktif'
        ));
    }

    // =========================================================================
    // Jadwalkan ulang lelang draft
    // =========================================================================
    public function jadwalUlang(Request $request, $kode)
    {
        $request->validate([
            'jadwal_mulai'    => 'required|date|after:now',
            'jadwal_berakhir' => 'required|date|after:jadwal_mulai',
        ], [
            'jadwal_mulai.after'    => 'Jadwal mulai harus di masa depan.',
            'jadwal_berakhir.after' => 'Jadwal berakhir harus setelah jadwal mulai.',
        ]);

        $lelang = Lelang::where('kode_lelang', $kode)->where('status', 'draft')->firstOrFail();

        // Reset bid & kandidat lama
        $lelang->bid()->update([
            'is_active' => false
        ]);
        $lelang->pemenang()->delete();

        $lelang->jadwal_mulai    = $request->jadwal_mulai;
        $lelang->jadwal_berakhir = $request->jadwal_berakhir;
        $lelang->status          = 'ditutup';
        $lelang->save();

        Log::info('Lelang dijadwalkan ulang oleh admin', ['kode' => $kode]);

        return back()->with('success', "Lelang {$lelang->kode_lelang} berhasil dijadwalkan ulang.");
    }

    // =========================================================================
    // Alih pemenang manual (admin force kandidat 2 naik)
    // =========================================================================
    public function alihPemenang($kode)
    {
        $lelang = Lelang::where('kode_lelang', $kode)->where('status', 'selesai')->firstOrFail();

        $kandidat1 = $lelang->pemenang()->where('urutan', 1)->where('status_kandidat', 'aktif')->first();
        if (!$kandidat1) {
            return back()->with('error', 'Tidak ada kandidat 1 yang aktif untuk dialihkan.');
        }

        $kandidat2 = $lelang->pemenang()->where('urutan', 2)->where('status_kandidat', 'standby')->first();
        if (!$kandidat2) {
            return back()->with('error', 'Tidak ada kandidat 2. Lelang perlu dijadwalkan ulang.');
        }

        // Expire struk kandidat 1
        $struk1 = Struk::where('id_pemenang', $kandidat1->id)
            ->whereIn('status', ['belum dibayar', 'pending'])
            ->first();

        if ($struk1) {
            if ($struk1->order_id && $struk1->snap_token) {
                try {
                    Transaction::expire($struk1->order_id);
                    $struk1->snap_token = null;
                } catch (\Exception $e) {
                    Log::warning('Gagal expire Midtrans (alih manual)', [
                        'order_id' => $struk1->order_id,
                        'error'    => $e->getMessage(),
                    ]);
                }
            }
            $struk1->status = 'gagal';
            $struk1->save();
        }

        $kandidat1->status_kandidat = 'gugur';
        $kandidat1->save();

        // Naikkan kandidat 2
        $kandidat2->status_kandidat = 'aktif';
        $kandidat2->save();

        // Generate struk baru untuk kandidat 2
        $kodeStruk = 'STRL-' . Str::upper(Str::random(10));
        while (Struk::where('kode_struk', $kodeStruk)->exists()) {
            $kodeStruk = 'STRL-' . Str::upper(Str::random(10));
        }

        $orderId  = 'ORDER-' . time() . '-' . $kandidat2->id;
        $bid      = $kandidat2->bid;
        $adminfee = $bid * 0.05;
        $total    = $bid + $adminfee;

        Struk::create([
            'id_lelang'   => $lelang->id,
            'id_barang'   => $lelang->id_barang,
            'id_pemenang' => $kandidat2->id,
            'total'       => $total,
            'status'      => 'belum dibayar',
            'kode_unik'   => null,
            'tgl_trx'     => now(),
            'kode_struk'  => $kodeStruk,
            'order_id'    => $orderId,
        ]);

        Log::info('Alih pemenang manual', [
            'lelang'    => $lelang->kode_lelang,
            'dari_user' => $kandidat1->id_user,
            'ke_user'   => $kandidat2->id_user,
        ]);

        return back()->with('success', 'Pemenang berhasil dialihkan ke kandidat 2.');
    }
}
