<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lelang;
use App\Models\Struk;
use App\Models\Pemenang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BackendController extends Controller
{
    public function index()
    {
        // =====================
        // STAT CARDS
        // =====================

        // Total barang (item unik)
        $totalbarang = Barang::count();

        // Total stok tersedia (sum semua jumlah)
        $totalbarangready = Barang::sum('jumlah');

        // Total nilai aset (harga modal x jumlah)
        $totalaset = Barang::where('jumlah', '>', 0)
            ->get()
            ->sum(fn($item) => $item->harga * $item->jumlah);

        // Lelang terjadwal (status ditutup = siap dilelang)
        $totaljadwal = Lelang::where('status', 'ditutup')->count();

        // Lelang selesai
        $totalberes = Lelang::where('status', 'selesai')->count();

        // Lelang aktif (sedang berjalan)
        $totalaktif = Lelang::where('status', 'dibuka')->count();

        // Total transaksi berhasil
        $totaltransaksi = Struk::where('status', 'berhasil')->count();

        // =====================
        // KEUANGAN
        // =====================

        $strukBerhasil = Struk::where('status', 'berhasil')->with('barang')->get();

        // Total pemasukan (dari struk berhasil)
        $totalPemasukan = $strukBerhasil->sum('total');

        // Total modal terpakai (harga barang yang sudah terjual)
        $totalModal = $strukBerhasil->sum(fn($s) => $s->barang->harga ?? 0);

        // Keuntungan bersih
        $keuntungan = $totalPemasukan - $totalModal;

        // Rata-rata keuntungan per transaksi
        $rataKeuntungan = $strukBerhasil->count() > 0
            ? $keuntungan / $strukBerhasil->count()
            : 0;

        // =====================
        // CHART DATA
        // =====================

        // Donut chart - distribusi aset per kategori
        $barangKategori = Barang::with('kategori')->where('jumlah', '>', 0)->get();
        $chartData = $barangKategori->groupBy(fn($item) => $item->kategori->nama ?? 'Tanpa Kategori')
            ->map(fn($items) => $items->sum(fn($item) => $item->harga * $item->jumlah));

        // Bar/Line chart - pemasukan, modal, keuntungan per bulan (12 bulan terakhir)
        $bulanList = collect(range(11, 0))->map(fn($i) => Carbon::now()->subMonths($i));

        $chartBulanan = $bulanList->map(function ($bulan) {
            $strukBulan = Struk::where('status', 'berhasil')
                ->whereYear('tgl_trx', $bulan->year)
                ->whereMonth('tgl_trx', $bulan->month)
                ->with('barang')
                ->get();

            $pemasukan = $strukBulan->sum('total');
            $modal     = $strukBulan->sum(fn($s) => $s->barang->harga ?? 0);

            return [
                'bulan'      => $bulan->translatedFormat('M Y'),
                'pemasukan'  => $pemasukan,
                'modal'      => $modal,
                'keuntungan' => $pemasukan - $modal,
            ];
        });

        // =====================
        // TABEL & LIST
        // =====================

        // Top 5 barang nilai tertinggi (harga x jumlah)
        $topBarang = Barang::with('kategori')
            ->where('jumlah', '>', 0)
            ->get()
            ->sortByDesc(fn($item) => $item->harga * $item->jumlah)
            ->take(5);

        // Lelang segera berakhir (status dibuka, jadwal_berakhir paling dekat)
        $lelangAktif = Lelang::where('status', 'dibuka')
            ->with('barang')
            ->orderBy('jadwal_berakhir', 'asc')
            ->take(5)
            ->get();

        // Transaksi terbaru
        $transaksiTerbaru = Struk::with(['barang', 'pemenang.user'])
            ->orderBy('tgl_trx', 'desc')
            ->take(5)
            ->get();

        return view('backend', compact(
            // Stat cards
            'totalbarang',
            'totalbarangready',
            'totalaset',
            'totaljadwal',
            'totalberes',
            'totalaktif',
            'totaltransaksi',
            // Keuangan
            'totalPemasukan',
            'totalModal',
            'keuntungan',
            'rataKeuntungan',
            // Chart
            'chartData',
            'chartBulanan',
            // Tabel
            'topBarang',
            'lelangAktif',
            'transaksiTerbaru'
        ));
    }
    // app/Http/Controllers/BackendController.php

    public function getSearchData()
    {
        return response()->json([
            "navigation" => [
                "Dashboards" => [
                    ["name" => "Dashboard Utama", "icon" => "ri-home-smile-line", "url" => route('backend.home')]
                ],
                "Aset & Barang" => [
                    ["name" => "Kategori Barang", "icon" => "ri-function-line", "url" => route('backend.kategori.index')],
                    ["name" => "Pengajuan Barang (Submissions)", "icon" => "ri-box-3-line", "url" => route('backend.submissions.index')],
                    ["name" => "Master Data Barang", "icon" => "ri-archive-line", "url" => route('backend.barang.index')]
                ],
                "Manajemen Lelang" => [
                    ["name" => "Daftar Lelang", "icon" => "ri-auction-line", "url" => route('backend.lelang.index')],
                    ["name" => "Log Penawaran (Bid)", "icon" => "ri-history-line", "url" => route('backend.bid.index')],
                    ["name" => "Daftar Pemenang", "icon" => "ri-trophy-line", "url" => route('backend.pemenang')]
                ],
                "Transaksi & Keuangan" => [
                    ["name" => "Menunggu Pembayaran", "icon" => "ri-money-dollar-circle-line", "url" => route('backend.struk.belum-bayar')],
                    ["name" => "Data Struk/Invoice", "icon" => "ri-file-list-3-line", "url" => route('backend.struk.index')],
                    ["name" => "Manajemen Refund", "icon" => "ri-refund-2-line", "url" => route('backend.refund.index')],
                    ["name" => "Penyelesaian Gagal Bayar", "icon" => "ri-error-warning-line", "url" => route('backend.gagalbayar.penyelesaian')],
                    ["name" => "Riwayat Gagal Bayar", "icon" => "ri-history-line", "url" => route('backend.gagalbayar.riwayat')]
                ],
                "User Management" => [
                    ["name" => "Daftar Pengguna", "icon" => "ri-group-line", "url" => route('backend.users.index')],
                    ["name" => "Verifikasi Identitas (KYC)", "icon" => "ri-shield-user-line", "url" => route('backend.verifikasi.index')]
                ]
            ],
            "suggestions" => [
                "Aksi Cepat" => [
                    ["name" => "Tambah Barang Baru", "icon" => "ri-add-box-line", "url" => route('backend.barang.create')],
                    ["name" => "Buat Jadwal Lelang", "icon" => "ri-calendar-event-line", "url" => route('backend.lelang.create')],
                    ["name" => "Cek Verifikasi Pending", "icon" => "ri-shield-user-line", "url" => route('backend.verifikasi.index')]
                ]
            ]
        ]);
    }
}
