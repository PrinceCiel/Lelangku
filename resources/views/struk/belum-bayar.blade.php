@extends('layouts.kerangkabackend')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    @endpush

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            {{-- ==================== WIDGET SUMMARY ==================== --}}
            <div class="card mb-6">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">

                            {{-- Total Transaksi --}}
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Total Transaksi</p>
                                        <h4 class="mb-1">{{ $struks->count() }}</h4>
                                        <p class="mb-0">
                                            <span class="me-2">Semua Tagihan</span>
                                            <span class="badge rounded-pill bg-label-info">Aktif</span>
                                        </p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-file-list-3-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-6" />
                            </div>

                            {{-- Belum Bayar --}}
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Belum Bayar</p>
                                        <h4 class="mb-1">{{ $struks->where('status', 'belum dibayar')->count() }}</h4>
                                        <p class="mb-0">
                                            <span class="me-2">Menunggu Aksi</span>
                                            <span class="badge rounded-pill bg-label-danger">Urgent</span>
                                        </p>
                                    </div>
                                    <div class="avatar me-lg-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-error-warning-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none" />
                            </div>

                            {{-- Pending --}}
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                    <div>
                                        <p class="mb-1">Pending</p>
                                        <h4 class="mb-1">{{ $struks->where('status', 'pending')->count() }}</h4>
                                        <p class="mb-0">
                                            <span class="me-2">Menunggu Konfirmasi</span>
                                            <span class="badge rounded-pill bg-label-warning">Pending</span>
                                        </p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-time-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none" />
                            </div>

                            {{-- Total Nilai --}}
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1">Total Nilai</p>
                                        <h4 class="mb-1">Rp{{ number_format($struks->sum('total'), 0, ',', '.') }}</h4>
                                        <p class="mb-0">
                                            <span class="me-2">Belum Masuk</span>
                                            <span class="badge rounded-pill bg-label-warning">Tertahan</span>
                                        </p>
                                    </div>
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-money-dollar-circle-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== TABLE ==================== --}}
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Pembayaran Belum Lunas</h5>
                </div>
                <div class="card-datatable table-responsive">
                    <table class="datatables-struk table" id="tabelStruk">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Struk</th>
                                <th>Nama Pemenang</th>
                                <th>Lelang</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Batas Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($struks as $i => $struk)
                                @php
                                    $bidakhir = $struk->pemenang->bid ?? 0;
                                    $adminfee = $bidakhir * 0.05;
                                    $total    = $bidakhir + $adminfee;
                                    $batas    = $struk->tgl_trx->addHour();
                                    $expired  = now()->gt($batas) && $struk->status === 'belum dibayar';
                                @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <span class="fw-semibold text-primary">{{ $struk->kode_struk }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar avatar-sm">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($struk->pemenang->user->nama_lengkap ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                            <span>{{ $struk->pemenang->user->nama_lengkap ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted" style="font-size: .85rem;">
                                            {{ $struk->lelang->kode_lelang ?? '-' }} —
                                            {{ $struk->lelang->barang->nama ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        @if ($struk->status === 'belum dibayar')
                                            @if ($expired)
                                                <span class="badge bg-label-secondary">Expired</span>
                                            @else
                                                <span class="badge bg-label-danger">Belum Bayar</span>
                                            @endif
                                        @elseif ($struk->status === 'pending')
                                            <span class="badge bg-label-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="{{ $expired ? 'text-danger' : 'text-muted' }}" style="font-size: .82rem;">
                                            {{ $batas->format('H:i, d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            {{-- Tombol Detail --}}
                                            <button class="btn btn-sm btn-icon btn-outline-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetail-{{ $struk->kode_struk }}"
                                                    title="Lihat Detail">
                                                <i class="ri ri-eye-line"></i>
                                            </button>

                                            {{-- Tombol Konfirmasi (hanya untuk status pending) --}}
                                            @if ($struk->status === 'pending')
                                                <form method="POST" action="{{ route('backend.struk.konfirmasi', $struk->kode_struk) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-icon btn-outline-success"
                                                            title="Konfirmasi Lunas"
                                                            onclick="return confirm('Konfirmasi pembayaran ini sebagai BERHASIL?')">
                                                        <i class="ri ri-check-double-line"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Tombol Batalkan (hanya untuk belum dibayar) --}}
                                            @if ($struk->status === 'belum dibayar')
                                                <form method="POST" action="{{ route('backend.struk.batal', $struk->kode_struk) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-icon btn-outline-danger"
                                                            title="Batalkan Tagihan"
                                                            onclick="return confirm('Batalkan tagihan ini?')">
                                                        <i class="ri ri-close-circle-line"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- ==================== FOOTER ==================== --}}
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">
                <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                    <div class="mb-2 mb-md-0">
                        &#169; <script>document.write(new Date().getFullYear())</script>,
                        made with ❤️ by <a href="https://pixinvent.com" target="_blank" class="footer-link fw-medium">Pixinvent</a>
                    </div>
                </div>
            </div>
        </footer>

        <div class="content-backdrop fade"></div>
    </div>

    {{-- ==================== MODALS DETAIL ==================== --}}
    @foreach ($struks as $struk)
        @php
            $bidakhir = $struk->pemenang->bid ?? 0;
            $adminfee = $bidakhir * 0.05;
            $total    = $bidakhir + $adminfee;
            $batas    = $struk->tgl_trx->addHour();
            $expired  = now()->gt($batas) && $struk->status === 'belum dibayar';
        @endphp
        <div class="modal fade" id="modalDetail-{{ $struk->kode_struk }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple">
                <div class="modal-content">
                    <div class="modal-body p-0">

                        {{-- Header Modal --}}
                        <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar flex-shrink-0">
                                    @if ($struk->status === 'pending')
                                        <span class="avatar-initial rounded bg-label-warning">
                                            <i class="icon-base ri ri-time-line icon-28px"></i>
                                        </span>
                                    @else
                                        <span class="avatar-initial rounded bg-label-danger">
                                            <i class="icon-base ri ri-error-warning-line icon-28px"></i>
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-semibold">Detail Tagihan</h5>
                                    <small class="text-muted">{{ $struk->kode_struk }}</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="px-5 py-4">

                            {{-- Badge Status --}}
                            <div class="d-flex justify-content-center mb-4">
                                @if ($struk->status === 'belum dibayar')
                                    @if ($expired)
                                        <span class="badge bg-label-secondary rounded-pill px-4 py-2" style="font-size:.9rem;">
                                            <i class="ri ri-timer-flash-line me-1"></i> Expired
                                        </span>
                                    @else
                                        <span class="badge bg-label-danger rounded-pill px-4 py-2" style="font-size:.9rem;">
                                            <i class="ri ri-error-warning-line me-1"></i> Belum Dibayar
                                        </span>
                                    @endif
                                @elseif ($struk->status === 'pending')
                                    <span class="badge bg-label-warning rounded-pill px-4 py-2" style="font-size:.9rem;">
                                        <i class="ri ri-time-line me-1"></i> Menunggu Konfirmasi
                                    </span>
                                @endif
                            </div>

                            {{-- Section: Info Pemenang --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                <i class="bx bx-user me-1"></i> Informasi Pemenang
                            </p>
                            <div class="row g-4 mb-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-muted">Nama Lengkap</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri ri-user-line"></i></span>
                                        <input type="text" class="form-control"
                                               value="{{ $struk->pemenang->user->nama_lengkap ?? '-' }}" disabled />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-muted">Email</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri ri-mail-line"></i></span>
                                        <input type="text" class="form-control"
                                               value="{{ $struk->pemenang->user->email ?? '-' }}" disabled />
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" />

                            {{-- Section: Info Lelang --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                <i class="bx bx-gavel me-1"></i> Informasi Lelang
                            </p>
                            <div class="row g-4 mb-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-muted">Kode Lelang</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri ri-barcode-line"></i></span>
                                        <input type="text" class="form-control"
                                               value="{{ $struk->lelang->kode_lelang ?? '-' }}" disabled />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-muted">Nama Barang</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri ri-archive-2-line"></i></span>
                                        <input type="text" class="form-control"
                                               value="{{ $struk->lelang->barang->nama ?? '-' }}" disabled />
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" />

                            {{-- Section: Rincian Pembayaran --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                <i class="bx bx-dollar me-1"></i> Rincian Pembayaran
                            </p>
                            <div class="row g-4 mb-4">
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-medium text-muted">Bid Akhir</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control"
                                               value="{{ number_format($bidakhir, 0, ',', '.') }}" disabled />
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-medium text-muted">Biaya Admin (5%)</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control"
                                               value="{{ number_format($adminfee, 0, ',', '.') }}" disabled />
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-medium text-muted">Total Tagihan</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text fw-bold">Rp</span>
                                        <input type="text" class="form-control fw-bold text-primary"
                                               value="{{ number_format($total, 0, ',', '.') }}" disabled />
                                    </div>
                                </div>
                            </div>

                            {{-- Batas Waktu --}}
                            <div class="alert {{ $expired ? 'alert-secondary' : ($struk->status === 'pending' ? 'alert-warning' : 'alert-danger') }} d-flex align-items-center gap-2 mb-0">
                                <i class="ri ri-timer-line" style="font-size:1.2rem;"></i>
                                <span>
                                    @if ($expired)
                                        Batas waktu pembayaran telah <strong>kedaluwarsa</strong> pada
                                        <strong>{{ $batas->format('H:i, d M Y') }}</strong>.
                                    @elseif ($struk->status === 'pending')
                                        Pembayaran sudah dilakukan, menunggu konfirmasi admin.
                                    @else
                                        Batas waktu pembayaran: <strong>{{ $batas->format('H:i, d M Y') }}</strong>
                                        <span class="ms-1 badge bg-label-danger countdown-badge"
                                              data-deadline="{{ $batas->toIso8601String() }}">
                                        </span>
                                    @endif
                                </span>
                            </div>

                        </div>

                        {{-- Footer Modal --}}
                        <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Tutup
                            </button>
                            @if ($struk->status === 'pending')
                                <form method="POST" action="{{ route('backend.struk.konfirmasi', $struk->kode_struk) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success"
                                            onclick="return confirm('Konfirmasi pembayaran ini sebagai BERHASIL?')">
                                        <i class="bx bx-check-double me-1"></i> Konfirmasi Lunas
                                    </button>
                                </form>
                            @endif
                            @if ($struk->status === 'belum dibayar')
                                <form method="POST" action="{{ route('backend.struk.batal', $struk->kode_struk) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Batalkan tagihan ini?')">
                                        <i class="bx bx-x-circle me-1"></i> Batalkan Tagihan
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script>
        // Init DataTable
        $(document).ready(function () {
            $('#tabelStruk').DataTable({
                responsive: true,
                language: {
                    search:         'Cari:',
                    lengthMenu:     'Tampilkan _MENU_ data',
                    info:           'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    paginate: {
                        previous: 'Sebelumnya',
                        next:     'Selanjutnya'
                    },
                    emptyTable: 'Tidak ada data pembayaran yang belum lunas 🎉'
                },
                columnDefs: [
                    { orderable: false, targets: [7] } // kolom Aksi tidak bisa di-sort
                ]
            });
        });

        // Countdown timer untuk batas bayar di modal
        function updateCountdowns() {
            document.querySelectorAll('.countdown-badge[data-deadline]').forEach(function (el) {
                const deadline = new Date(el.dataset.deadline);
                const now      = new Date();
                const diff     = deadline - now;

                if (diff <= 0) {
                    el.textContent = 'Waktu habis!';
                    el.classList.remove('bg-label-danger');
                    el.classList.add('bg-label-secondary');
                    return;
                }

                const hours   = Math.floor(diff / 1000 / 60 / 60);
                const minutes = Math.floor((diff / 1000 / 60) % 60);
                const seconds = Math.floor((diff / 1000) % 60);

                el.textContent = `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')} tersisa`;
            });
        }

        updateCountdowns();
        setInterval(updateCountdowns, 1000);
    </script>
@endpush
