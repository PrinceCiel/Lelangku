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
                                        <p class="mb-1">Menunggu Tindakan</p>
                                        <h4 class="mb-1">{{ $totalPending }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-warning">Pending</span></p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-time-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-6" />
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Sedang Diproses</p>
                                        <h4 class="mb-1">{{ $totalDiproses }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-info">Diproses</span></p>
                                    </div>
                                    <div class="avatar me-lg-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-loader-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none" />
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                    <div>
                                        <p class="mb-1">Selesai</p>
                                        <h4 class="mb-1">{{ $totalSelesai }}</h4>
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
                                        <p class="mb-1">Total Nilai Pending</p>
                                        <h4 class="mb-1">Rp{{ number_format($totalNilai, 0, ',', '.') }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-danger">Belum Dikembalikan</span></p>
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

            @if (session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif

            {{-- TABLE --}}
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Daftar Refund Request</h5>
                </div>
                <div class="card-datatable table-responsive">
                    <table class="table" id="tabelRefund">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Lelang</th>
                                <th>Jumlah</th>
                                <th>Metode Asal</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Rekening Tujuan</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($refunds as $i => $refund)
                                <tr>
                                    <td>{{ $i + 1 }}</td>

                                    {{-- User --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar avatar-sm">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($refund->user->nama_lengkap ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="d-block fw-medium" style="font-size:.85rem;">{{ $refund->user->nama_lengkap ?? '-' }}</span>
                                                <small class="text-muted">{{ $refund->user->email ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Lelang --}}
                                    <td>
                                        <span class="fw-semibold" style="font-size:.85rem;">
                                            {{ $refund->deposit->lelang->kode_lelang ?? '-' }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $refund->deposit->lelang->barang->nama ?? '-' }}
                                        </small>
                                    </td>

                                    {{-- Jumlah --}}
                                    <td>
                                        <span class="fw-semibold">Rp{{ number_format($refund->jumlah, 0, ',', '.') }}</span>
                                    </td>

                                    {{-- Metode Asal --}}
                                    <td>
                                        <span class="badge bg-label-secondary">{{ strtoupper($refund->payment_type ?? '-') }}</span>
                                        @if ($refund->masked_account)
                                            <br><small class="text-muted">{{ $refund->masked_account }}</small>
                                        @endif
                                    </td>

                                    {{-- Alasan --}}
                                    <td>
                                        @php
                                            $alasanLabel = match($refund->alasan_manual) {
                                                'payment_type_va', 'payment_type_va_kalah' => 'VA/Transfer',
                                                'refund_api_gagal', 'refund_api_gagal_kick', 'refund_api_gagal_kalah' => 'API Gagal',
                                                'kick_dari_lelang' => 'Di-kick',
                                                'user_diblacklist' => 'Blacklist',
                                                'kalah_lelang' => 'Kalah Lelang',
                                                default => $refund->alasan_manual,
                                            };
                                            $alasanColor = match($refund->alasan_manual) {
                                                'kick_dari_lelang' => 'bg-label-warning',
                                                'user_diblacklist' => 'bg-label-danger',
                                                'refund_api_gagal', 'refund_api_gagal_kick', 'refund_api_gagal_kalah' => 'bg-label-danger',
                                                default => 'bg-label-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $alasanColor }}">{{ $alasanLabel }}</span>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @if ($refund->status === 'pending')
                                            <span class="badge bg-label-warning">Pending</span>
                                        @elseif ($refund->status === 'diproses')
                                            <span class="badge bg-label-info">Diproses</span>
                                        @elseif ($refund->status === 'selesai')
                                            <span class="badge bg-label-success">Selesai</span>
                                        @elseif ($refund->status === 'gagal')
                                            <span class="badge bg-label-danger">Ditolak</span>
                                        @endif
                                    </td>

                                    {{-- Rekening Tujuan --}}
                                    <td>
                                        @if ($refund->rekening_tujuan)
                                            <span class="fw-medium" style="font-size:.83rem;">{{ $refund->rekening_tujuan }}</span>
                                            <br>
                                            <small class="text-muted">{{ $refund->nama_pemilik }} — {{ $refund->bank_tujuan }}</small>
                                        @else
                                            <span class="badge bg-label-warning">Belum diisi user</span>
                                        @endif
                                    </td>

                                    {{-- Dibuat --}}
                                    <td>
                                        <span class="text-muted" style="font-size:.82rem;">
                                            {{ $refund->created_at->format('d M Y') }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td>
                                        <div class="d-flex gap-1">
                                            {{-- Detail --}}
                                            <button class="btn btn-sm btn-icon btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDetail-{{ $refund->id }}"
                                                title="Detail">
                                                <i class="ri ri-eye-line"></i>
                                            </button>

                                            {{-- Mulai Proses --}}
                                            @if ($refund->status === 'pending')
                                                <form method="POST" action="{{ route('backend.refund.mulai', $refund->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-primary" title="Mulai Proses"
                                                        onclick="return confirm('Tandai refund ini sedang diproses?')">
                                                        <i class="ri ri-play-line"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Retry Midtrans --}}
                                            @if (in_array($refund->alasan_manual, ['refund_api_gagal', 'refund_api_gagal_kick', 'refund_api_gagal_kalah']) && in_array($refund->status, ['pending', 'diproses']))
                                                <form method="POST" action="{{ route('backend.refund.retry', $refund->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-warning" title="Retry via Midtrans"
                                                        onclick="return confirm('Coba refund ulang via Midtrans?')">
                                                        <i class="ri ri-refresh-line"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="ri ri-checkbox-circle-line text-success" style="font-size:2.5rem;"></i>
                                        <p class="mt-2 text-muted mb-0">Tidak ada refund request 🎉</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- MODALS DETAIL --}}
        @foreach ($refunds as $refund)
            <div class="modal fade" id="modalDetail-{{ $refund->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple">
                    <div class="modal-content">
                        <div class="modal-body p-0">

                            {{-- Header --}}
                            <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="icon-base ri ri-refund-2-line icon-28px"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-semibold">Detail Refund Request</h5>
                                        <small class="text-muted">#{{ $refund->id }} — {{ $refund->user->nama_lengkap ?? '-' }}</small>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="px-5 py-4">
                                <div class="row g-4">

                                    {{-- Info User & Deposit --}}
                                    <div class="col-12 col-md-6">
                                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                            Informasi User
                                        </p>
                                        <div class="d-flex flex-column gap-2">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted" style="font-size:.85rem;">Nama</span>
                                                <span class="fw-medium" style="font-size:.85rem;">{{ $refund->user->nama_lengkap ?? '-' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted" style="font-size:.85rem;">Email</span>
                                                <span class="fw-medium" style="font-size:.85rem;">{{ $refund->user->email ?? '-' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted" style="font-size:.85rem;">Lelang</span>
                                                <span class="fw-medium" style="font-size:.85rem;">{{ $refund->deposit->lelang->kode_lelang ?? '-' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted" style="font-size:.85rem;">Jumlah Refund</span>
                                                <span class="fw-bold text-primary">Rp{{ number_format($refund->jumlah, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted" style="font-size:.85rem;">Metode Bayar Asal</span>
                                                <span class="badge bg-label-secondary">{{ strtoupper($refund->payment_type ?? '-') }}</span>
                                            </div>
                                            @if ($refund->masked_account)
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted" style="font-size:.85rem;">No. Akun Asal</span>
                                                    <span class="fw-medium" style="font-size:.85rem;">{{ $refund->masked_account }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Rekening Tujuan --}}
                                    <div class="col-12 col-md-6">
                                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                            Rekening Tujuan Refund
                                        </p>
                                        @if ($refund->rekening_tujuan)
                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted" style="font-size:.85rem;">Bank</span>
                                                    <span class="fw-semibold">{{ $refund->bank_tujuan ?? '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted" style="font-size:.85rem;">No. Rekening</span>
                                                    <span class="fw-semibold">{{ $refund->rekening_tujuan }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted" style="font-size:.85rem;">Atas Nama</span>
                                                    <span class="fw-semibold">{{ $refund->nama_pemilik ?? '-' }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
                                                <i class="ri ri-alert-line"></i>
                                                <span style="font-size:.85rem;">User belum mengisi rekening tujuan refund.</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Bukti Transfer (kalau sudah selesai) --}}
                                    @if ($refund->bukti_transfer)
                                        <div class="col-12">
                                            <hr class="my-2" />
                                            <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                                Bukti Transfer
                                            </p>
                                            <img src="{{ Storage::url($refund->bukti_transfer) }}"
                                                alt="Bukti Transfer" class="rounded-3"
                                                style="max-height:200px; object-fit:contain;">
                                            @if ($refund->catatan_admin)
                                                <p class="text-muted mt-2 mb-0" style="font-size:.85rem;">
                                                    <strong>Catatan:</strong> {{ $refund->catatan_admin }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif

                                </div>
                            </div>

                            {{-- Footer — form proses / tolak --}}
                            @if (in_array($refund->status, ['pending', 'diproses']) && $refund->rekening_tujuan)
                                <div class="border-top">
                                    {{-- Form Proses --}}
                                    <div class="px-5 py-4 border-bottom">
                                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                            <i class="bx bx-upload me-1"></i> Upload Bukti Transfer
                                        </p>
                                        <form method="POST"
                                            action="{{ route('backend.refund.proses', $refund->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-medium">Bukti Transfer <span class="text-danger">*</span></label>
                                                    <input type="file" name="bukti_transfer" class="form-control" accept="image/*" required />
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-medium">Catatan Admin</label>
                                                    <input type="text" name="catatan_admin" class="form-control" placeholder="Opsional" />
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-success"
                                                        onclick="return confirm('Konfirmasi refund ini sudah dikirim?')">
                                                        <i class="ri ri-check-line me-1"></i> Konfirmasi Selesai
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Form Tolak --}}
                                    <div class="px-5 py-4">
                                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                            <i class="bx bx-x me-1"></i> Tolak Refund
                                        </p>
                                        <form method="POST" action="{{ route('backend.refund.tolak', $refund->id) }}">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-12 col-md-8">
                                                    <input type="text" name="catatan_admin" class="form-control"
                                                        placeholder="Alasan penolakan (wajib)" required />
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <button type="submit" class="btn btn-danger w-100"
                                                        onclick="return confirm('Tolak refund ini?')">
                                                        <i class="ri ri-close-line me-1"></i> Tolak
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-end px-5 py-3 border-top">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="bx bx-x me-1"></i> Tutup
                                </button>
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
            $('#tabelRefund').DataTable({
                responsive: true,
                order: [[8, 'desc']],
                language: {
                    search:     'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info:       'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    paginate:   { previous: 'Sebelumnya', next: 'Selanjutnya' },
                    emptyTable: 'Tidak ada refund request.'
                },
                columnDefs: [{ orderable: false, targets: [9] }]
            });
        });
    </script>
@endpush
