@extends('layouts.kerangkabackend')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    @endpush

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            {{-- WIDGET --}}
            <div class="card mb-6">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Total Gagal</p>
                                        <h4 class="mb-1">{{ $totalGagal }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-danger">Semua Riwayat</span></p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-close-circle-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-6" />
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Perlu Tindakan</p>
                                        <h4 class="mb-1">{{ $perluTindak }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-warning">Belum Ditindak</span></p>
                                    </div>
                                    <div class="avatar me-lg-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-error-warning-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none" />
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                    <div>
                                        <p class="mb-1">Sudah Ditindak</p>
                                        <h4 class="mb-1">{{ $sudahDitindak }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-success">Selesai</span></p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-checkbox-circle-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none" />
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1">Total Nilai Gagal</p>
                                        <h4 class="mb-1">Rp{{ number_format($totalNilaiGagal, 0, ',', '.') }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-secondary">Tidak Masuk</span></p>
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

            {{-- Alert perlu tindakan --}}
            @if ($perluTindak > 0)
                <div class="alert alert-warning d-flex align-items-center gap-3 mb-5" role="alert">
                    <i class="ri ri-alert-line" style="font-size:1.4rem; flex-shrink:0;"></i>
                    <div>
                        <strong>{{ $perluTindak }} transaksi gagal</strong> belum ditindaklanjuti.
                        Kandidat pengganti mungkin belum diaktifkan atau lelang belum dijadwalkan ulang.
                        <a href="{{ route('backend.gagalbayar.penyelesaian') }}" class="alert-link ms-1">Ke halaman Penyelesaian →</a>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif

            {{-- TABLE --}}
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Transaksi Gagal</h5>
                    <a href="{{ route('backend.gagalbayar.penyelesaian') }}" class="btn btn-warning btn-sm">
                        <i class="ri ri-tools-line me-1"></i> Penyelesaian
                    </a>
                </div>
                <div class="card-datatable table-responsive">
                    @if ($struks->isEmpty())
                        <div class="text-center py-5">
                            <i class="ri ri-checkbox-circle-line text-success" style="font-size:2.5rem;"></i>
                            <p class="mt-2 text-muted">Tidak ada riwayat transaksi gagal 🎉</p>
                        </div>
                    @else
                        <table class="table" id="tabelRiwayat">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Struk</th>
                                    <th>Kandidat</th>
                                    <th>Lelang</th>
                                    <th>Total</th>
                                    <th>Urutan</th>
                                    <th>Gagal Pada</th>
                                    <th>Status Lelang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($struks as $i => $struk)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($struk->perlu_tindak)
                                                    <span class="badge bg-label-warning" title="Perlu ditindaklanjuti">
                                                        <i class="ri ri-alert-line"></i>
                                                    </span>
                                                @else
                                                    <span class="badge bg-label-success" title="Sudah ditindaklanjuti">
                                                        <i class="ri ri-checkbox-circle-line"></i>
                                                    </span>
                                                @endif
                                                <span class="fw-semibold text-danger">{{ $struk->kode_struk }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar avatar-sm">
                                                    <span class="avatar-initial rounded-circle bg-label-danger">
                                                        {{ strtoupper(substr($struk->pemenang->user->nama_lengkap ?? 'U', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-medium">{{ $struk->pemenang->user->nama_lengkap ?? '-' }}</span>
                                                    <small class="text-muted">{{ $struk->pemenang->user->email ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $struk->lelang->kode_lelang ?? '-' }}</span>
                                            <br>
                                            <small class="text-muted">{{ $struk->lelang->barang->nama ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">Rp{{ number_format($struk->total, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            @if (($struk->pemenang->urutan ?? 1) == 1)
                                                <span class="badge bg-label-primary">Kandidat 1</span>
                                            @else
                                                <span class="badge bg-label-info">Kandidat 2</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted" style="font-size:.82rem;">
                                                {{ $struk->updated_at->format('H:i, d M Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            @php $sl = $struk->lelang->status ?? '-' @endphp
                                            @if ($sl === 'draft')
                                                <span class="badge bg-label-secondary">Draft</span>
                                            @elseif ($sl === 'selesai')
                                                <span class="badge bg-label-warning">Selesai</span>
                                            @elseif ($sl === 'dibuka')
                                                <span class="badge bg-label-success">Dibuka</span>
                                            @elseif ($sl === 'ditutup')
                                                <span class="badge bg-label-info">Ditutup</span>
                                            @else
                                                <span class="badge bg-label-secondary">{{ $sl }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-icon btn-outline-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetail-{{ $struk->kode_struk }}"
                                                    title="Detail">
                                                    <i class="ri ri-eye-line"></i>
                                                </button>
                                                <form method="POST" action="{{ route('backend.gagalbayar.hapus', $struk->kode_struk) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-icon btn-outline-danger"
                                                        title="Hapus dari riwayat"
                                                        onclick="return confirm('Hapus struk ini dari riwayat?')">
                                                        <i class="ri ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="ri ri-checkbox-circle-line text-success" style="font-size:2.5rem;"></i>
                                            <p class="mt-2 text-muted mb-0">Tidak ada riwayat transaksi gagal 🎉</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>

        {{-- MODALS DETAIL --}}
        @foreach ($struks as $struk)
            @php
                $bid      = $struk->pemenang->bid ?? 0;
                $adminfee = $bid * 0.05;
                $total    = $bid + $adminfee;
            @endphp
            <div class="modal fade" id="modalDetail-{{ $struk->kode_struk }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-danger">
                                            <i class="icon-base ri ri-close-circle-line icon-28px"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-semibold">Detail Transaksi Gagal</h5>
                                        <small class="text-muted">{{ $struk->kode_struk }}</small>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="px-5 py-4">
                                <div class="d-flex justify-content-center gap-2 mb-4">
                                    <span class="badge bg-label-danger rounded-pill px-4 py-2" style="font-size:.9rem;">
                                        <i class="ri ri-close-circle-line me-1"></i> Gagal Bayar
                                    </span>
                                    <span class="badge {{ ($struk->pemenang->urutan ?? 1) == 1 ? 'bg-label-primary' : 'bg-label-info' }} rounded-pill px-4 py-2" style="font-size:.9rem;">
                                        Kandidat {{ $struk->pemenang->urutan ?? 1 }}
                                    </span>
                                </div>

                                <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                    <i class="bx bx-user me-1"></i> Informasi Kandidat
                                </p>
                                <div class="row g-4 mb-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-medium text-muted">Nama Lengkap</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="ri ri-user-line"></i></span>
                                            <input type="text" class="form-control" value="{{ $struk->pemenang->user->nama_lengkap ?? '-' }}" disabled />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-medium text-muted">Email</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="ri ri-mail-line"></i></span>
                                            <input type="text" class="form-control" value="{{ $struk->pemenang->user->email ?? '-' }}" disabled />
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4" />

                                <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                    <i class="bx bx-gavel me-1"></i> Informasi Lelang
                                </p>
                                <div class="row g-4 mb-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-medium text-muted">Kode Lelang</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="ri ri-barcode-line"></i></span>
                                            <input type="text" class="form-control" value="{{ $struk->lelang->kode_lelang ?? '-' }}" disabled />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-medium text-muted">Nama Barang</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="ri ri-archive-2-line"></i></span>
                                            <input type="text" class="form-control" value="{{ $struk->lelang->barang->nama ?? '-' }}" disabled />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-medium text-muted">Status Lelang</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="ri ri-information-line"></i></span>
                                            <input type="text" class="form-control" value="{{ ucfirst($struk->lelang->status ?? '-') }}" disabled />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-medium text-muted">Waktu Gagal</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="ri ri-time-line"></i></span>
                                            <input type="text" class="form-control" value="{{ $struk->updated_at->format('H:i, d M Y') }}" disabled />
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4" />

                                <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                    <i class="bx bx-dollar me-1"></i> Rincian Pembayaran
                                </p>
                                <div class="row g-4">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-medium text-muted">Bid</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control" value="{{ number_format($bid, 0, ',', '.') }}" disabled />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-medium text-muted">Biaya Admin (5%)</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control" value="{{ number_format($adminfee, 0, ',', '.') }}" disabled />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-medium text-muted">Total Tagihan</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control fw-bold text-danger" value="{{ number_format($total, 0, ',', '.') }}" disabled />
                                        </div>
                                    </div>
                                </div>

                                <div class="alert {{ $struk->perlu_tindak ? 'alert-warning' : 'alert-success' }} d-flex align-items-center gap-2 mt-4 mb-0">
                                    <i class="ri {{ $struk->perlu_tindak ? 'ri-alert-line' : 'ri-checkbox-circle-line' }}"></i>
                                    @if ($struk->perlu_tindak)
                                        <span>Lelang ini <strong>belum ditindaklanjuti</strong>. Silakan ke halaman Penyelesaian.</span>
                                    @else
                                        <span>Lelang ini sudah ditindaklanjuti.</span>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="bx bx-x me-1"></i> Tutup
                                </button>
                                @if ($struk->perlu_tindak)
                                    <a href="{{ route('backend.gagalbayar.penyelesaian') }}" class="btn btn-warning">
                                        <i class="ri ri-tools-line me-1"></i> Ke Penyelesaian
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

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
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#tabelRiwayat').DataTable({
                responsive: true,
                order: [[6, 'desc']],
                language: {
                    search:     'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info:       'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    paginate:   { previous: '<', next: '>' },
                    emptyTable: 'Tidak ada riwayat transaksi gagal 🎉'
                },
                columnDefs: [{ orderable: false, targets: [8] }]
            });
        });
    </script>
@endpush
