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

                            {{-- Total Pendaftar --}}
                            <div class="col-sm-6 col-lg-4">
                                <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Total Pendaftar</p>
                                        <h4 class="mb-1">{{ $users->count() }}</h4>
                                        <p class="mb-0">
                                            <span class="me-2">Semua Pengajuan</span>
                                            <span class="badge rounded-pill bg-label-info">Aktif</span>
                                        </p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-user-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-6" />
                            </div>

                            {{-- Menunggu Persetujuan --}}
                            <div class="col-sm-6 col-lg-4">
                                <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Menunggu Persetujuan</p>
                                        <h4 class="mb-1">{{ $users->count() }}</h4>
                                        <p class="mb-0">
                                            <span class="me-2">Perlu Ditinjau</span>
                                            <span class="badge rounded-pill bg-label-warning">Pending</span>
                                        </p>
                                    </div>
                                    <div class="avatar me-lg-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-time-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none" />
                            </div>

                            {{-- Dokumen Diunggah --}}
                            <div class="col-sm-6 col-lg-4">
                                <div class="d-flex justify-content-between align-items-start pb-4 pb-sm-0 card-widget-3">
                                    <div>
                                        <p class="mb-1">Dokumen Diunggah</p>
                                        <h4 class="mb-1">{{ $users->filter(fn($u) => $u->datadiri && $u->datadiri->foto_dokumen)->count() }}</h4>
                                        <p class="mb-0">
                                            <span class="me-2">Siap Diverifikasi</span>
                                            <span class="badge rounded-pill bg-label-success">Tersedia</span>
                                        </p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-id-card-line icon-28px"></i>
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
                    <h5 class="mb-0">Persetujuan Verifikasi User</h5>
                    <span class="badge rounded-pill bg-label-warning">Menunggu Persetujuan</span>
                </div>
                <div class="card-datatable table-responsive">
                    <table class="datatables-verifikasi table" id="tabelVerifikasi">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No Telp</th>
                                <th>Tgl Lahir</th>
                                <th>Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $i => $user)
                                @php $datadiri = $user->datadiri @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar avatar-sm">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                            <span class="fw-semibold">{{ $user->nama_lengkap }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted" style="font-size: .85rem;">{{ $user->email }}</span>
                                    </td>
                                    <td>{{ $datadiri->no_telp ?? '-' }}</td>
                                    <td>
                                        <span class="text-muted" style="font-size: .82rem;">
                                            {{ $datadiri->tanggal_lahir ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalVerifikasi-{{ $user->id }}"
                                                title="Lihat Detail">
                                            <i class="ri ri-eye-line"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('backend.verifikasi.approve', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-icon btn-outline-success"
                                                        title="Setujui Verifikasi"
                                                        onclick="return confirm('Setujui verifikasi user ini?')">
                                                    <i class="ri ri-check-double-line"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('backend.verifikasi.reject', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-icon btn-outline-danger"
                                                        title="Tolak Verifikasi"
                                                        onclick="return confirm('Tolak verifikasi user ini?')">
                                                    <i class="ri ri-close-circle-line"></i>
                                                </button>
                                            </form>
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
                        made with ❤️ by <a href="https://github.com/princeciel" target="_blank" class="footer-link fw-medium">Ardhika Pratama</a>
                    </div>
                </div>
            </div>
        </footer>

        <div class="content-backdrop fade"></div>
    </div>

    {{-- ==================== MODALS DETAIL ==================== --}}
    @foreach ($users as $user)
        @php $datadiri = $user->datadiri @endphp
        <div class="modal fade" id="modalVerifikasi-{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple">
                <div class="modal-content">
                    <div class="modal-body p-0">

                        {{-- Header Modal --}}
                        <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="icon-base ri ri-shield-user-line icon-28px"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-semibold">Detail Verifikasi</h5>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="px-5 py-4">

                            {{-- Badge Status --}}
                            <div class="d-flex justify-content-center mb-4">
                                <span class="badge bg-label-warning rounded-pill px-4 py-2" style="font-size:.9rem;">
                                    <i class="ri ri-time-line me-1"></i> Menunggu Persetujuan
                                </span>
                            </div>

                            {{-- Section: Info Pengguna --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                <i class="bx bx-user me-1"></i> Informasi Pengguna
                            </p>
                            <div class="row g-4 mb-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-muted">Nama Lengkap</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri ri-user-line"></i></span>
                                        <input type="text" class="form-control"
                                               value="{{ $user->nama_lengkap }}" disabled />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-muted">Email</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri ri-mail-line"></i></span>
                                        <input type="text" class="form-control"
                                               value="{{ $user->email }}" disabled />
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" />

                            {{-- Section: Data Diri --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                <i class="bx bx-id-card me-1"></i> Data Diri
                            </p>
                            <div class="row g-4 mb-4">
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-medium text-muted">No Telepon</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri ri-phone-line"></i></span>
                                        <input type="text" class="form-control"
                                               value="{{ $datadiri->no_telp ?? '-' }}" disabled />
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-medium text-muted">Tanggal Lahir</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri ri-calendar-line"></i></span>
                                        <input type="text" class="form-control"
                                               value="{{ $datadiri->tanggal_lahir ?? '-' }}" disabled />
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-medium text-muted">Alamat</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri ri-map-pin-line"></i></span>
                                        <input type="text" class="form-control"
                                               value="{{ $datadiri->alamat ?? '-' }}" disabled />
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" />

                            {{-- Section: Dokumen KTP --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                <i class="bx bx-file me-1"></i> Dokumen KTP
                            </p>
                            <div class="mb-4">
                                @if ($datadiri && $datadiri->foto_dokumen)
                                    <img src="{{ asset('storage/' . $datadiri->foto_dokumen) }}"
                                         class="img-fluid rounded w-100"
                                         style="max-height: 280px; object-fit: cover;"
                                         alt="KTP {{ $user->nama_lengkap }}" />
                                @else
                                    <div class="alert alert-secondary d-flex align-items-center gap-2 mb-0">
                                        <i class="ri ri-error-warning-line" style="font-size:1.2rem;"></i>
                                        <span>Foto dokumen <strong>tidak ditemukan</strong> untuk user ini.</span>
                                    </div>
                                @endif
                            </div>

                        </div>

                        {{-- Footer Modal --}}
                        <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Tutup
                            </button>
                            <form action="{{ route('backend.verifikasi.reject', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Tolak verifikasi user ini?')">
                                    <i class="bx bx-x-circle me-1"></i> Tolak
                                </button>
                            </form>
                            <form action="{{ route('backend.verifikasi.approve', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Setujui verifikasi user ini?')">
                                    <i class="bx bx-check-double me-1"></i> Setujui
                                </button>
                            </form>
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
        $(document).ready(function () {
            $('#tabelVerifikasi').DataTable({
                responsive: true,
                language: {
                    search:         'Cari:',
                    lengthMenu:     'Tampilkan _MENU_ data',
                    info:           'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    paginate: {
                        previous: '<',
                        next:     '>'
                    },
                    emptyTable: 'Tidak ada user yang menunggu verifikasi 🎉'
                },
                columnDefs: [
                    { orderable: false, targets: [5, 6] }
                ]
            });
        });
    </script>
@endpush
