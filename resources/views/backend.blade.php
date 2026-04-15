@extends('layouts.kerangkabackend')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- ===================== --}}
        {{-- HEADER --}}
        {{-- ===================== --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary" style="border: none;">
                    <div class="d-flex align-items-end row">
                        <div class="col-md-8 order-2 order-md-1">
                            <div class="card-body py-5">
                                <h4 class="card-title mb-2 text-white fw-bold fs-3">
                                    Selamat Datang, Admin! 👋
                                </h4>
                                <p class="mb-1 text-white opacity-75">
                                    Berikut ringkasan aktivitas platform lelang hari ini.
                                </p>
                                <p class="mb-4 text-white opacity-75">
                                    {{ now()->translatedFormat('l, d F Y') }}
                                </p>
                                <div class="d-flex gap-3 flex-wrap">
                                    <a href="{{ route('lelang.index') }}" class="btn btn-light btn-sm fw-semibold">
                                        <i class="ri ri-auction-line me-1"></i> Kelola Lelang
                                    </a>
                                    <a href="{{ route('backend.barang.index') }}" class="btn btn-outline-light btn-sm fw-semibold">
                                        <i class="ri ri-box-3-line me-1"></i> Kelola Barang
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center order-1 order-md-2 d-none d-md-block">
                            <div class="card-body pb-0">
                                <img src="{{ asset('assets/img/illustrations/illustration-john-light.png') }}"
                                    height="180" alt="Dashboard" class="opacity-75"
                                    data-app-light-img="illustrations/illustration-john-light.png"
                                    data-app-dark-img="illustrations/illustration-john-dark.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- STAT CARDS ROW 1 - INVENTORY --}}
        {{-- ===================== --}}
        <div class="row g-4 mb-4">
            {{-- Total Barang --}}
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                            <div>
                                <p class="mb-1 text-body-secondary small fw-semibold text-uppercase tracking-wide">Total Barang</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($totalbarang) }}</h4>
                            </div>
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded-3">
                                    <i class="icon-base ri ri-box-3-line icon-24px"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 small text-body-secondary">Item unik di inventori</p>
                    </div>
                </div>
            </div>

            {{-- Total Stok --}}
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                            <div>
                                <p class="mb-1 text-body-secondary small fw-semibold text-uppercase">Total Stok</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($totalbarangready) }}</h4>
                            </div>
                            <div class="avatar">
                                <div class="avatar-initial bg-label-info rounded-3">
                                    <i class="icon-base ri ri-stack-line icon-24px"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 small text-body-secondary">Unit tersedia siap dilelang</p>
                    </div>
                </div>
            </div>

            {{-- Total Nilai Aset --}}
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                            <div>
                                <p class="mb-1 text-body-secondary small fw-semibold text-uppercase">Total Nilai Aset</p>
                                <h4 class="mb-0 fw-bold">Rp {{ number_format($totalaset, 0, ',', '.') }}</h4>
                            </div>
                            <div class="avatar">
                                <div class="avatar-initial bg-label-warning rounded-3">
                                    <i class="icon-base ri ri-safe-2-line icon-24px"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 small text-body-secondary">Nilai modal × stok tersedia</p>
                    </div>
                </div>
            </div>

            {{-- Total Transaksi --}}
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                            <div>
                                <p class="mb-1 text-body-secondary small fw-semibold text-uppercase">Transaksi Berhasil</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($totaltransaksi) }}</h4>
                            </div>
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded-3">
                                    <i class="icon-base ri ri-checkbox-circle-line icon-24px"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 small text-body-secondary">Total pembayaran berhasil</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- STAT CARDS ROW 2 - LELANG --}}
        {{-- ===================== --}}
        <div class="row g-4 mb-4">
            {{-- Lelang Aktif --}}
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100" style="border-left: 4px solid #28a745;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                            <div>
                                <p class="mb-1 text-body-secondary small fw-semibold text-uppercase">Lelang Aktif</p>
                                <h4 class="mb-0 fw-bold text-success">{{ number_format($totalaktif) }}</h4>
                            </div>
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded-3">
                                    <i class="icon-base ri ri-live-line icon-24px"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 small text-body-secondary">Sedang berlangsung sekarang</p>
                    </div>
                </div>
            </div>

            {{-- Lelang Terjadwal --}}
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100" style="border-left: 4px solid #fd7e14;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                            <div>
                                <p class="mb-1 text-body-secondary small fw-semibold text-uppercase">Lelang Terjadwal</p>
                                <h4 class="mb-0 fw-bold text-warning">{{ number_format($totaljadwal) }}</h4>
                            </div>
                            <div class="avatar">
                                <div class="avatar-initial bg-label-warning rounded-3">
                                    <i class="icon-base ri ri-calendar-schedule-line icon-24px"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 small text-body-secondary">Menunggu dibuka</p>
                    </div>
                </div>
            </div>

            {{-- Lelang Selesai --}}
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100" style="border-left: 4px solid #6f42c1;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                            <div>
                                <p class="mb-1 text-body-secondary small fw-semibold text-uppercase">Lelang Selesai</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($totalberes) }}</h4>
                            </div>
                            <div class="avatar">
                                <div class="avatar-initial bg-label-secondary rounded-3">
                                    <i class="icon-base ri ri-flag-2-line icon-24px"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 small text-body-secondary">Semua lelang yang telah selesai</p>
                    </div>
                </div>
            </div>

            {{-- Rata Keuntungan --}}
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100" style="border-left: 4px solid #20c997;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                            <div>
                                <p class="mb-1 text-body-secondary small fw-semibold text-uppercase">Rata-rata Untung</p>
                                <h4 class="mb-0 fw-bold text-success">
                                    Rp {{ number_format($rataKeuntungan, 0, ',', '.') }}
                                </h4>
                            </div>
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded-3">
                                    <i class="icon-base ri ri-bar-chart-grouped-line icon-24px"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 small text-body-secondary">Per transaksi berhasil</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- KEUANGAN SUMMARY --}}
        {{-- ===================== --}}
        <div class="row g-4 mb-4">
            {{-- Total Pemasukan --}}
            <div class="col-sm-4">
                <div class="card h-100" style="background: linear-gradient(135deg, #11998e, #38ef7d); border: none;">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="mb-0 fw-semibold opacity-90 small text-uppercase">Total Pemasukan</p>
                            <i class="icon-base ri ri-arrow-up-circle-line icon-28px opacity-75"></i>
                        </div>
                        <h3 class="mb-1 fw-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                        <p class="mb-0 small opacity-75">Dari semua transaksi berhasil</p>
                    </div>
                </div>
            </div>

            {{-- Total Modal --}}
            <div class="col-sm-4">
                <div class="card h-100" style="background: linear-gradient(135deg, #f7971e, #ffd200); border: none;">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="mb-0 fw-semibold opacity-90 small text-uppercase">Total Modal</p>
                            <i class="icon-base ri ri-arrow-down-circle-line icon-28px opacity-75"></i>
                        </div>
                        <h3 class="mb-1 fw-bold">Rp {{ number_format($totalModal, 0, ',', '.') }}</h3>
                        <p class="mb-0 small opacity-75">Harga modal barang terjual</p>
                    </div>
                </div>
            </div>

            {{-- Keuntungan Bersih --}}
            <div class="col-sm-4">
                <div class="card h-100" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="mb-0 fw-semibold opacity-90 small text-uppercase">Keuntungan Bersih</p>
                            <i class="icon-base ri ri-money-dollar-circle-line icon-28px opacity-75"></i>
                        </div>
                        <h3 class="mb-1 fw-bold">Rp {{ number_format($keuntungan, 0, ',', '.') }}</h3>
                        <p class="mb-0 small opacity-75">Pemasukan - Modal</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- CHART ROW --}}
        {{-- ===================== --}}
        <div class="row g-4 mb-4">
            {{-- Chart Bulanan --}}
            <div class="col-12 col-lg-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Pemasukan vs Modal vs Keuntungan</h5>
                            <p class="mb-0 card-subtitle">12 Bulan Terakhir</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="chartBulanan"></div>
                    </div>
                </div>
            </div>

            {{-- Donut Chart Kategori --}}
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-1">Distribusi Aset</h5>
                        <p class="mb-0 card-subtitle">Per Kategori Barang</p>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div id="chartKategori"></div>
                        <div class="mt-3" id="legendKategori"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- TABEL ROW --}}
        {{-- ===================== --}}
        <div class="row g-4 mb-4">
            {{-- Top Barang --}}
            <div class="col-12 col-lg-5">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Top Barang</h5>
                            <p class="card-subtitle mb-0">Nilai Aset Tertinggi</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            @forelse($topBarang as $index => $barang)
                            <li class="d-flex align-items-center {{ !$loop->last ? 'mb-5' : '' }}">
                                <div class="avatar avatar-md flex-shrink-0 me-3">
                                    <div class="avatar-initial rounded-3"
                                        style="background: {{ ['#667eea20','#11998e20','#f7971e20','#764ba220','#e0234720'][$index % 5] }}; color: {{ ['#667eea','#11998e','#f7971e','#764ba2','#e02347'][$index % 5] }}; font-weight: 700; font-size: 1rem;">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div>
                                        <h6 class="mb-0">{{ $barang->nama }}</h6>
                                        <small class="text-body-secondary">{{ $barang->kategori->nama ?? '-' }} · Stok: {{ $barang->jumlah }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="badge bg-label-primary rounded-pill">
                                            Rp {{ number_format($barang->harga * $barang->jumlah, 0, ',', '.') }}
                                        </div>
                                        <small class="d-block text-body-secondary mt-1">
                                            @Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="text-center text-body-secondary py-4">
                                <i class="ri ri-inbox-line icon-28px d-block mb-2"></i>
                                Tidak ada barang tersedia
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Lelang Segera Berakhir --}}
            <div class="col-12 col-lg-7">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Lelang Segera Berakhir</h5>
                            <p class="card-subtitle mb-0">Urut berdasarkan jadwal terdekat</p>
                        </div>
                        <a href="{{ route('lelang.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="bg-transparent">Barang</th>
                                    <th class="bg-transparent">Kode</th>
                                    <th class="bg-transparent">Berakhir</th>
                                    <th class="bg-transparent text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lelangAktif as $lelang)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar avatar-sm">
                                                <div class="avatar-initial bg-label-success rounded-2">
                                                    <i class="ri ri-auction-line"></i>
                                                </div>
                                            </div>
                                            <span class="fw-medium">{{ $lelang->barang->nama ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td><code class="small">{{ $lelang->kode_lelang }}</code></td>
                                    <td>
                                        <span class="small">
                                            {{ \Carbon\Carbon::parse($lelang->jadwal_berakhir)->translatedFormat('d M Y, H:i') }}
                                        </span>
                                        <br>
                                        <small class="text-danger fw-semibold">
                                            {{ \Carbon\Carbon::parse($lelang->jadwal_berakhir)->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="badge bg-label-success rounded-pill">Aktif</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-body-secondary py-4">
                                        <i class="ri ri-calendar-check-line icon-28px d-block mb-2"></i>
                                        Tidak ada lelang aktif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- TRANSAKSI TERBARU --}}
        {{-- ===================== --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Transaksi Terbaru</h5>
                            <p class="card-subtitle mb-0">5 transaksi terakhir</p>
                        </div>
                        <a href="{{ route('backend.struk.belum-bayar') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="bg-transparent">Kode Struk</th>
                                    <th class="bg-transparent">Barang</th>
                                    <th class="bg-transparent">Pemenang</th>
                                    <th class="bg-transparent">Total</th>
                                    <th class="bg-transparent">Keuntungan</th>
                                    <th class="bg-transparent">Tanggal</th>
                                    <th class="bg-transparent text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksiTerbaru as $trx)
                                @php
                                    $untung = $trx->total - ($trx->barang->harga ?? 0);
                                    $statusClass = match($trx->status) {
                                        'berhasil'     => 'bg-label-success',
                                        'belum dibayar'=> 'bg-label-warning',
                                        'pending'      => 'bg-label-info',
                                        'gagal'        => 'bg-label-danger',
                                        default        => 'bg-label-secondary',
                                    };
                                @endphp
                                <tr>
                                    <td><code class="small">{{ $trx->kode_struk }}</code></td>
                                    <td>{{ $trx->barang->nama ?? '-' }}</td>
                                    <td>{{ $trx->pemenang->user->name ?? '-' }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                    <td class="{{ $untung >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                                        {{ $untung >= 0 ? '+' : '' }}Rp {{ number_format($untung, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($trx->tgl_trx)->translatedFormat('d M Y, H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="badge {{ $statusClass }} rounded-pill">{{ ucfirst($trx->status) }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-body-secondary py-4">
                                        <i class="ri ri-receipt-line icon-28px d-block mb-2"></i>
                                        Belum ada transaksi
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== --}}
    {{-- FOOTER --}}
    {{-- ===================== --}}
    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl">
            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    &copy; {{ date('Y') }} Platform Lelang. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
</div>

{{-- ===================== --}}
{{-- APEXCHARTS SCRIPTS --}}
{{-- ===================== --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ========================
    // DATA DARI BLADE/LARAVEL
    // ========================
    const chartBulanan = @json($chartBulanan->values());
    const chartKategori = @json($chartData);

    const bulanLabels    = chartBulanan.map(d => d.bulan);
    const pemasukanData  = chartBulanan.map(d => d.pemasukan);
    const modalData      = chartBulanan.map(d => d.modal);
    const keuntunganData = chartBulanan.map(d => d.keuntungan);

    // ========================
    // CHART BULANAN (Bar + Line)
    // ========================
    const optionsBulanan = {
        series: [
            { name: 'Pemasukan',  type: 'bar',  data: pemasukanData  },
            { name: 'Modal',      type: 'bar',  data: modalData      },
            { name: 'Keuntungan', type: 'line', data: keuntunganData },
        ],
        chart: {
            height: 320,
            type: 'line',
            toolbar: { show: false },
            zoom: { enabled: false },
        },
        colors: ['#28a745', '#fd7e14', '#667eea'],
        plotOptions: {
            bar: { borderRadius: 6, columnWidth: '50%' }
        },
        dataLabels: { enabled: false },
        stroke: {
            width: [0, 0, 3],
            curve: 'smooth'
        },
        xaxis: {
            categories: bulanLabels,
            labels: { style: { fontSize: '11px' } }
        },
        yaxis: {
            labels: {
                formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
            }
        },
        tooltip: {
            y: {
                formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
        grid: { borderColor: '#f0f0f0' },
    };

    new ApexCharts(document.querySelector('#chartBulanan'), optionsBulanan).render();

    // ========================
    // DONUT CHART KATEGORI
    // ========================
    const kategoriLabels = Object.keys(chartKategori);
    const kategoriValues = Object.values(chartKategori);

    const optionsKategori = {
        series: kategoriValues,
        chart: {
            type: 'donut',
            height: 220,
        },
        labels: kategoriLabels,
        colors: ['#667eea','#11998e','#f7971e','#e02347','#764ba2','#20c997','#fd7e14'],
        dataLabels: { enabled: false },
        legend: { show: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Aset',
                            formatter: function(w) {
                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            y: {
                formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
            }
        }
    };

    new ApexCharts(document.querySelector('#chartKategori'), optionsKategori).render();

    // ========================
    // LEGEND KATEGORI MANUAL
    // ========================
    const colors = ['#667eea','#11998e','#f7971e','#e02347','#764ba2','#20c997','#fd7e14'];
    const legendEl = document.getElementById('legendKategori');
    if (legendEl) {
        legendEl.innerHTML = kategoriLabels.map((label, i) => `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:10px;height:10px;border-radius:50%;background:${colors[i % colors.length]};flex-shrink:0;"></div>
                    <small>${label}</small>
                </div>
                <small class="fw-semibold">Rp ${new Intl.NumberFormat('id-ID').format(kategoriValues[i])}</small>
            </div>
        `).join('');
    }
});
</script>
@endpush
@endsection
