@extends('layouts.kerangkabackend')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    @endpush

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            {{-- ==================== WIDGET ==================== --}}
            <div class="card mb-6">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Total Pengguna</p>
                                        <h4 class="mb-1">{{ $users->count() }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-primary">Semua</span></p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-group-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-6" />
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Aktif</p>
                                        <h4 class="mb-1">{{ $users->where('is_banned', false)->where('is_suspicious', false)->count() }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-success">Normal</span></p>
                                    </div>
                                    <div class="avatar me-lg-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-user-follow-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none" />
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                    <div>
                                        <p class="mb-1">Suspicious</p>
                                        <h4 class="mb-1">{{ $users->where('is_suspicious', true)->count() }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-warning">Perlu Pantau</span></p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-alert-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none" />
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1">Banned</p>
                                        <h4 class="mb-1">{{ $users->where('is_banned', true)->count() }}</h4>
                                        <p class="mb-0"><span class="badge rounded-pill bg-label-danger">Diblokir</span></p>
                                    </div>
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-user-unfollow-line icon-28px"></i>
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

            {{-- ==================== TABLE ==================== --}}
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Daftar Pengguna</h5>
                </div>
                <div class="card-datatable table-responsive">
                    <table class="table" id="tabelUsers">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pengguna</th>
                                <th>Verifikasi</th>
                                <th>Status</th>
                                <th>Strike</th>
                                <th>Ikut Lelang</th>
                                <th>Menang</th>
                                <th>Gagal Bayar</th>
                                <th>Last Login</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $i => $user)
                                @php
                                    $totalIkut      = $user->deposit->where('status','berhasil')->count();
                                    $totalMenang    = $user->pemenang->where('status_kandidat', 'menang')->count();
                                    $totalGagalBayar = $user->strikes->where('alasan', 'gagal_bayar')->count();
                                    $winRate        = $totalIkut > 0 ? round(($totalMenang / $totalIkut) * 100) : 0;
                                @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>

                                    {{-- Pengguna --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar avatar-sm">
                                                @if ($user->foto)
                                                    <img src="{{ Storage::url($user->foto) }}" alt="foto" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="d-block fw-semibold">{{ $user->nama_lengkap }}</span>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Verifikasi --}}
                                    <td>
                                        @if ($user->status === 'Terverifikasi')
                                            <span class="badge bg-label-success">
                                                <i class="ri ri-shield-check-line me-1"></i> Verified
                                            </span>
                                        @elseif ($user->status === 'diajukan')
                                            <span class="badge bg-label-warning">
                                                <i class="ri ri-time-line me-1"></i> Diajukan
                                            </span>
                                        @else
                                            <span class="badge bg-label-secondary">
                                                <i class="ri ri-shield-line me-1"></i> Belum
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @if ($user->is_banned)
                                            <span class="badge bg-label-danger">
                                                <i class="ri ri-close-circle-line me-1"></i> Banned
                                            </span>
                                        @elseif ($user->is_suspicious)
                                            <span class="badge bg-label-warning">
                                                <i class="ri ri-alert-line me-1"></i> Suspicious
                                            </span>
                                        @else
                                            <span class="badge bg-label-success">
                                                <i class="ri ri-checkbox-circle-line me-1"></i> Active
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Strike --}}
                                    <td>
                                        @php $strike = $user->strike_count ?? 0; @endphp
                                        @if ($strike === 0)
                                            <span class="badge bg-label-success">{{ $strike }}</span>
                                        @elseif ($strike === 1)
                                            <span class="badge bg-label-info">{{ $strike }} ⚠️</span>
                                        @elseif ($strike === 2)
                                            <span class="badge bg-label-warning">{{ $strike }} ⚠️⚠️</span>
                                        @else
                                            <span class="badge bg-label-danger">{{ $strike }}</span>
                                        @endif
                                    </td>

                                    {{-- Ikut Lelang --}}
                                    <td>
                                        <span class="fw-medium">{{ $totalIkut }}</span>
                                    </td>

                                    {{-- Menang --}}
                                    <td>
                                        <span class="fw-medium text-success">{{ $totalMenang }}</span>
                                        @if ($totalIkut > 0)
                                            <small class="text-muted d-block">{{ $winRate }}% WR</small>
                                        @endif
                                    </td>

                                    {{-- Gagal Bayar --}}
                                    <td>
                                        @if ($totalGagalBayar > 0)
                                            <span class="fw-medium text-danger">{{ $totalGagalBayar }}</span>
                                        @else
                                            <span class="fw-medium text-muted">0</span>
                                        @endif
                                    </td>

                                    {{-- Last Login --}}
                                    <td>
                                        <span class="text-muted" style="font-size:.82rem;">
                                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td>
                                        <button class="btn btn-sm btn-icon btn-outline-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalUser-{{ $user->id }}"
                                            title="Detail">
                                            <i class="ri ri-eye-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="ri ri-group-line text-muted" style="font-size:2.5rem;"></i>
                                        <p class="mt-2 text-muted mb-0">Belum ada pengguna.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- ==================== MODALS DETAIL ==================== --}}
        @foreach ($users as $user)
            @php
                $datadiri        = $user->datadiri;
                $totalIkut       = $user->deposit->where('status', 'berhasil')->count();
                $totalMenang     = $user->pemenang->where('status_kandidat', 'menang')->count();
                $totalGagalBayar = $user->strikes->where('alasan', 'gagal_bayar')->count();
                $totalBid        = $user->bid->count();
                $winRate         = $totalIkut > 0 ? round(($totalMenang / $totalIkut) * 100) : 0;
                $strikes         = $user->strikes ?? collect();
                $kickHistory     = $user->kicks ?? collect();
            @endphp
            <div class="modal fade" id="modalUser-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-simple">
                    <div class="modal-content">
                        <div class="modal-body p-0">

                            {{-- Header --}}
                            <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar flex-shrink-0">
                                        @if ($user->foto)
                                            <img src="{{ Storage::url($user->foto) }}" alt="foto"
                                                class="rounded-circle" style="width:42px;height:42px;object-fit:cover;">
                                        @else
                                            <span class="avatar-initial rounded-circle bg-label-primary" style="width:42px;height:42px;font-size:1.1rem;">
                                                {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-semibold">{{ $user->nama_lengkap }}</h5>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    {{-- Status badge --}}
                                    @if ($user->is_banned)
                                        <span class="badge bg-label-danger px-3 py-2">
                                            <i class="ri ri-close-circle-line me-1"></i> Banned
                                        </span>
                                    @elseif ($user->is_suspicious)
                                        <span class="badge bg-label-warning px-3 py-2">
                                            <i class="ri ri-alert-line me-1"></i> Suspicious
                                        </span>
                                    @else
                                        <span class="badge bg-label-success px-3 py-2">
                                            <i class="ri ri-checkbox-circle-line me-1"></i> Active
                                        </span>
                                    @endif
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                            </div>

                            <div class="px-5 py-4">
                                <div class="row g-5">

                                    {{-- KOLOM KIRI --}}
                                    <div class="col-12 col-lg-5">

                                        {{-- Data Pribadi --}}
                                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                            <i class="bx bx-user me-1"></i> Data Pribadi
                                        </p>
                                        <div class="d-flex flex-column gap-3 mb-4">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted" style="font-size:.85rem;">Nama Lengkap</span>
                                                <span class="fw-medium" style="font-size:.85rem;">{{ $user->nama_lengkap }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted" style="font-size:.85rem;">Email</span>
                                                <span class="fw-medium" style="font-size:.85rem;">{{ $user->email }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted" style="font-size:.85rem;">Status Verifikasi</span>
                                                <span style="font-size:.85rem;">
                                                    @if ($user->status === 'Terverifikasi')
                                                        <span class="badge bg-label-success">Verified</span>
                                                    @elseif ($user->status === 'diajukan')
                                                        <span class="badge bg-label-warning">Diajukan</span>
                                                    @else
                                                        <span class="badge bg-label-secondary">Belum</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted" style="font-size:.85rem;">Bergabung</span>
                                                <span class="fw-medium" style="font-size:.85rem;">{{ $user->created_at->format('d M Y') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted" style="font-size:.85rem;">Last Login</span>
                                                <span class="fw-medium" style="font-size:.85rem;">
                                                    {{ $user->last_login_at ? $user->last_login_at->format('H:i, d M Y') : 'Belum pernah' }}
                                                </span>
                                            </div>
                                            @if ($datadiri)
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted" style="font-size:.85rem;">TTL</span>
                                                    <span class="fw-medium" style="font-size:.85rem;">{{ $datadiri->ttl ?? '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted" style="font-size:.85rem;">Alamat</span>
                                                    <span class="fw-medium text-end" style="font-size:.85rem; max-width:180px;">{{ $datadiri->alamat ?? '-' }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Foto KTP --}}
                                        @if ($datadiri && $datadiri->foto_ktp)
                                            <hr class="my-3" />
                                            <p class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.7rem; letter-spacing:.08em;">
                                                <i class="bx bx-id-card me-1"></i> Foto KTP
                                            </p>
                                            <img src="{{ Storage::url($datadiri->foto_ktp) }}"
                                                alt="KTP" class="rounded-3 w-100" style="object-fit:cover; max-height:160px;">
                                        @endif

                                        <hr class="my-4" />

                                        {{-- Stats Lelang --}}
                                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                            <i class="bx bx-bar-chart me-1"></i> Statistik Lelang
                                        </p>
                                        <div class="row g-3 mb-4">
                                            <div class="col-6">
                                                <div class="bg-label-primary rounded-3 p-3 text-center">
                                                    <h5 class="mb-0 fw-bold">{{ $totalIkut }}</h5>
                                                    <small class="text-muted">Total Ikut</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-label-success rounded-3 p-3 text-center">
                                                    <h5 class="mb-0 fw-bold text-success">{{ $totalMenang }}</h5>
                                                    <small class="text-muted">Menang</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-label-danger rounded-3 p-3 text-center">
                                                    <h5 class="mb-0 fw-bold text-danger">{{ $totalGagalBayar }}</h5>
                                                    <small class="text-muted">Gagal Bayar</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-label-info rounded-3 p-3 text-center">
                                                    <h5 class="mb-0 fw-bold">{{ $totalBid }}</h5>
                                                    <small class="text-muted">Total Bid</small>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Win Rate Bar --}}
                                        <div class="mb-4">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted">Win Rate</small>
                                                <small class="fw-semibold">{{ $winRate }}%</small>
                                            </div>
                                            <div class="progress" style="height:6px;">
                                                <div class="progress-bar {{ $winRate >= 50 ? 'bg-success' : ($winRate >= 25 ? 'bg-warning' : 'bg-danger') }}"
                                                    style="width:{{ $winRate }}%"></div>
                                            </div>
                                        </div>

                                        {{-- Strike Info --}}
                                        <hr class="my-4" />
                                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                            <i class="bx bx-error me-1"></i> Strike
                                        </p>
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div class="text-center">
                                                <h2 class="mb-0 fw-black {{ ($user->strike_count ?? 0) >= 3 ? 'text-danger' : (($user->strike_count ?? 0) >= 2 ? 'text-warning' : 'text-success') }}">
                                                    {{ $user->strike_count ?? 0 }}
                                                </h2>
                                                <small class="text-muted">/ 3</small>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="progress" style="height:8px;">
                                                    @php $strikePercent = min((($user->strike_count ?? 0) / 3) * 100, 100); @endphp
                                                    <div class="progress-bar {{ ($user->strike_count ?? 0) >= 3 ? 'bg-danger' : (($user->strike_count ?? 0) >= 2 ? 'bg-warning' : 'bg-success') }}"
                                                        style="width:{{ $strikePercent }}%"></div>
                                                </div>
                                                <small class="text-muted mt-1 d-block">
                                                    @if (($user->strike_count ?? 0) >= 3)
                                                        Akun diblacklist otomatis
                                                    @elseif (($user->strike_count ?? 0) === 2)
                                                        1 strike lagi → blacklist
                                                    @else
                                                        Aman
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                    </div>

                                    {{-- KOLOM KANAN --}}
                                    <div class="col-12 col-lg-7">

                                        {{-- Riwayat Strike --}}
                                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                            <i class="bx bx-error-circle me-1"></i> Riwayat Strike
                                        </p>
                                        @if ($strikes->isEmpty())
                                            <div class="text-center py-3 mb-4">
                                                <i class="ri ri-checkbox-circle-line text-success" style="font-size:1.5rem;"></i>
                                                <p class="text-muted mb-0 mt-1" style="font-size:.85rem;">Tidak ada riwayat strike.</p>
                                            </div>
                                        @else
                                            <div class="d-flex flex-column gap-2 mb-4" style="max-height:160px; overflow-y:auto;">
                                                @foreach ($strikes as $strike)
                                                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-label-danger">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge bg-danger">Strike #{{ $strike->strike_ke }}</span>
                                                            <span style="font-size:.83rem;">
                                                                @if ($strike->alasan === 'gagal_bayar') Gagal Bayar
                                                                @elseif ($strike->alasan === 'suspicious_activity') Aktivitas Mencurigakan
                                                                @elseif ($strike->alasan === 'manual_admin') Ditambah Admin
                                                                @else {{ $strike->alasan }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <small class="text-muted">{{ $strike->created_at->format('d M Y') }}</small>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Riwayat Lelang --}}
                                        <hr class="my-3" />
                                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                            <i class="bx bx-gavel me-1"></i> Riwayat Lelang
                                        </p>
                                        @if ($user->pemenang->isEmpty() && $kickHistory->isEmpty())
                                            <div class="text-center py-3 mb-4">
                                                <p class="text-muted mb-0" style="font-size:.85rem;">Belum pernah ikut lelang.</p>
                                            </div>
                                        @else
                                            <div class="d-flex flex-column gap-2 mb-4" style="max-height:200px; overflow-y:auto;">
                                                @foreach ($user->pemenang as $p)
                                                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3
                                                        {{ $p->status_kandidat === 'menang' ? 'bg-label-success' : ($p->status_kandidat === 'gugur' ? 'bg-label-danger' : 'bg-label-secondary') }}">
                                                        <div>
                                                            <span class="fw-medium d-block" style="font-size:.85rem;">
                                                                {{ $p->lelang->kode_lelang ?? '-' }} — {{ $p->lelang->barang->nama ?? '-' }}
                                                            </span>
                                                            <small class="text-muted">Rp{{ number_format($p->bid, 0, ',', '.') }}</small>
                                                        </div>
                                                        <span class="badge
                                                            {{ $p->status_kandidat === 'menang' ? 'bg-success' : ($p->status_kandidat === 'gugur' ? 'bg-danger' : ($p->status_kandidat === 'aktif' ? 'bg-primary' : 'bg-secondary')) }}">
                                                            {{ ucfirst($p->status_kandidat) }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                                @foreach ($kickHistory as $kick)
                                                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-label-warning">
                                                        <div>
                                                            <span class="fw-medium d-block" style="font-size:.85rem;">
                                                                {{ $kick->lelang->kode_lelang ?? '-' }} — {{ $kick->lelang->barang->nama ?? '-' }}
                                                            </span>
                                                            <small class="text-muted">{{ $kick->alasan ?? 'Di-kick dari lelang' }}</small>
                                                        </div>
                                                        <span class="badge bg-warning text-dark">Di-kick</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Actions --}}
                                        <hr class="my-3" />
                                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem; letter-spacing:.08em;">
                                            <i class="bx bx-cog me-1"></i> Tindakan Admin
                                        </p>
                                        <div class="d-flex flex-wrap gap-2">

                                            {{-- Ban / Unban --}}
                                            @if (!$user->is_banned)
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="banUser({{ $user->id }}, '{{ $user->nama_lengkap }}')">
                                                    <i class="ri ri-user-unfollow-line me-1"></i> Ban
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-success"
                                                    onclick="unbanUser({{ $user->id }}, '{{ $user->nama_lengkap }}')">
                                                    <i class="ri ri-user-follow-line me-1"></i> Unban
                                                </button>
                                            @endif

                                            {{-- Suspicious --}}
                                            <button class="btn btn-sm {{ $user->is_suspicious ? 'btn-outline-warning' : 'btn-warning' }}"
                                                onclick="toggleSuspicious({{ $user->id }}, '{{ $user->nama_lengkap }}')">
                                                <i class="ri ri-alert-line me-1"></i>
                                                {{ $user->is_suspicious ? 'Hapus Suspicious' : 'Mark Suspicious' }}
                                            </button>

                                            {{-- Tambah Strike Manual --}}
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="tambahStrike({{ $user->id }}, '{{ $user->nama_lengkap }}')">
                                                <i class="ri ri-add-line me-1"></i> Tambah Strike
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="d-flex justify-content-end px-5 py-4 border-top">
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

    {{-- Modal Ban --}}
    <div class="modal fade" id="modalBan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                        <h5 class="mb-0 fw-semibold">Ban Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="formBan" method="POST" class="px-5 py-4">
                        @csrf
                        <p class="mb-3">Ban akun <strong id="banUserName"></strong>?</p>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Alasan (opsional)</label>
                            <input type="text" name="reason" class="form-control" placeholder="Contoh: Spam bidding, fraud, dll" />
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="ri ri-user-unfollow-line me-1"></i> Ban
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Strike --}}
    <div class="modal fade" id="modalStrike" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                        <h5 class="mb-0 fw-semibold">Tambah Strike Manual</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="formStrike" method="POST" class="px-5 py-4">
                        @csrf
                        <p class="mb-3">Tambah strike untuk <strong id="strikeUserName"></strong>?</p>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Alasan <span class="text-danger">*</span></label>
                            <select name="alasan" class="form-select" required>
                                <option value="suspicious_activity">Aktivitas Mencurigakan</option>
                                <option value="manual_admin">Keputusan Admin</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="ri ri-add-line me-1"></i> Tambah Strike
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#tabelUsers').DataTable({
                responsive: true,
                order: [[8, 'desc']],
                language: {
                    search:     'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info:       'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    paginate:   { previous: 'Sebelumnya', next: 'Selanjutnya' },
                    emptyTable: 'Belum ada pengguna.'
                },
                columnDefs: [{ orderable: false, targets: [9] }]
            });
        });

        function banUser(userId, nama) {
            document.getElementById('banUserName').textContent = nama;
            document.getElementById('formBan').action = `/admin/users/${userId}/ban`;
            new bootstrap.Modal(document.getElementById('modalBan')).show();
        }

        function unbanUser(userId, nama) {
            if (!confirm(`Unban ${nama}?`)) return;
            fetch(`/admin/users/${userId}/unban`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            }).then(r => r.json()).then(data => {
                alert(data.message);
                location.reload();
            });
        }

        function toggleSuspicious(userId, nama) {
            if (!confirm(`Ubah status suspicious untuk ${nama}?`)) return;
            fetch(`/admin/users/${userId}/suspicious`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            }).then(r => r.json()).then(data => {
                alert(data.message);
                location.reload();
            });
        }

        function tambahStrike(userId, nama) {
            document.getElementById('strikeUserName').textContent = nama;
            document.getElementById('formStrike').action = `/admin/users/${userId}/strike`;
            new bootstrap.Modal(document.getElementById('modalStrike')).show();
        }
    </script>
@endpush
