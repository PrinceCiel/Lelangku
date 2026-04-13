@extends('layouts.kerangkabackend')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            @if (session('success'))
                <div class="alert alert-success mb-5">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mb-5">{{ session('error') }}</div>
            @endif

            {{-- ================================================================
                 SECTION 1: Lelang Aktif — Kandidat 1 & 2 sama-sama masih jalan
                 Admin bisa alih manual ke kandidat 2
            ================================================================ --}}
            <div class="card mb-6">
                <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Lelang Aktif — Bisa Dialih Manual</h5>
                        <small class="text-muted">Kandidat 1 masih aktif. Admin bisa paksa alih ke kandidat 2 kapanpun.</small>
                    </div>
                    <span class="badge bg-label-primary rounded-pill px-3 py-2" style="font-size:.85rem;">
                        {{ $lelangAktif->count() }} Lelang
                    </span>
                </div>

                @forelse ($lelangAktif as $lelang)
                    @php
                        $k1 = $lelang->pemenang->firstWhere('urutan', 1);
                        $k2 = $lelang->pemenang->firstWhere('urutan', 2);
                    @endphp
                    <div class="card-body border-bottom py-4">
                        <div class="row align-items-center g-4">

                            {{-- Info Lelang --}}
                            <div class="col-12 col-md-4">
                                <p class="text-uppercase text-muted fw-semibold mb-1" style="font-size:.7rem; letter-spacing:.08em;">Lelang</p>
                                <h6 class="mb-0 fw-bold">{{ $lelang->kode_lelang }}</h6>
                                <small class="text-muted">{{ $lelang->barang->nama ?? '-' }}</small>
                            </div>

                            {{-- Kandidat 1 --}}
                            <div class="col-12 col-md-3">
                                <p class="text-uppercase text-muted fw-semibold mb-1" style="font-size:.7rem; letter-spacing:.08em;">Kandidat 1 (Aktif)</p>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-sm">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ strtoupper(substr($k1->user->nama_lengkap ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="d-block fw-medium" style="font-size:.88rem;">{{ $k1->user->nama_lengkap ?? '-' }}</span>
                                        <small class="text-muted">Rp{{ number_format($k1->bid ?? 0, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Kandidat 2 --}}
                            <div class="col-12 col-md-3">
                                <p class="text-uppercase text-muted fw-semibold mb-1" style="font-size:.7rem; letter-spacing:.08em;">Kandidat 2 (Standby)</p>
                                @if ($k2)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                {{ strtoupper(substr($k2->user->nama_lengkap ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="d-block fw-medium" style="font-size:.88rem;">{{ $k2->user->nama_lengkap ?? '-' }}</span>
                                            <small class="text-muted">Rp{{ number_format($k2->bid ?? 0, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted" style="font-size:.85rem;">Tidak ada kandidat 2</span>
                                @endif
                            </div>

                            {{-- Aksi --}}
                            <div class="col-12 col-md-2 text-md-end">
                                @if ($k2)
                                    <form method="POST" action="{{ route('backend.gagalbayar.alih', $lelang->kode_lelang) }}">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-warning"
                                            onclick="return confirm('Paksa alih ke kandidat 2? Struk kandidat 1 akan di-expire.')">
                                            <i class="ri ri-swap-line me-1"></i> Alih ke K2
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-label-secondary">Tidak bisa dialih</span>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="card-body text-center py-5">
                        <i class="ri ri-checkbox-circle-line text-success" style="font-size:2rem;"></i>
                        <p class="mt-2 text-muted mb-0">Tidak ada lelang yang perlu dialih manual.</p>
                    </div>
                @endforelse
            </div>

            {{-- ================================================================
                 SECTION 2: Lelang Proses — Kandidat 2 sedang aktif
            ================================================================ --}}
            <div class="card mb-6">
                <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Sedang Diproses — Kandidat 2 Aktif</h5>
                        <small class="text-muted">Kandidat 1 gugur. Kandidat 2 sedang dalam proses pembayaran.</small>
                    </div>
                    <span class="badge bg-label-info rounded-pill px-3 py-2" style="font-size:.85rem;">
                        {{ $lelangProses->count() }} Lelang
                    </span>
                </div>

                @forelse ($lelangProses as $lelang)
                    @php
                        $k1 = $lelang->pemenang->firstWhere('urutan', 1);
                        $k2 = $lelang->pemenang->firstWhere('urutan', 2);
                    @endphp
                    <div class="card-body border-bottom py-4">
                        <div class="row align-items-center g-4">

                            <div class="col-12 col-md-4">
                                <p class="text-uppercase text-muted fw-semibold mb-1" style="font-size:.7rem; letter-spacing:.08em;">Lelang</p>
                                <h6 class="mb-0 fw-bold">{{ $lelang->kode_lelang }}</h6>
                                <small class="text-muted">{{ $lelang->barang->nama ?? '-' }}</small>
                            </div>

                            <div class="col-12 col-md-3">
                                <p class="text-uppercase text-muted fw-semibold mb-1" style="font-size:.7rem; letter-spacing:.08em;">Kandidat 1 (Gugur)</p>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-sm">
                                        <span class="avatar-initial rounded-circle bg-label-danger">
                                            {{ strtoupper(substr($k1->user->nama_lengkap ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="d-block fw-medium text-decoration-line-through text-muted" style="font-size:.88rem;">{{ $k1->user->nama_lengkap ?? '-' }}</span>
                                        <small class="text-muted">Rp{{ number_format($k1->bid ?? 0, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <p class="text-uppercase text-muted fw-semibold mb-1" style="font-size:.7rem; letter-spacing:.08em;">Kandidat 2 (Aktif)</p>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-sm">
                                        <span class="avatar-initial rounded-circle bg-label-success">
                                            {{ strtoupper(substr($k2->user->nama_lengkap ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="d-block fw-medium" style="font-size:.88rem;">{{ $k2->user->nama_lengkap ?? '-' }}</span>
                                        <small class="text-muted">Rp{{ number_format($k2->bid ?? 0, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-2 text-md-end">
                                <span class="badge bg-label-info">Menunggu Bayar</span>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="card-body text-center py-5">
                        <i class="ri ri-time-line text-muted" style="font-size:2rem;"></i>
                        <p class="mt-2 text-muted mb-0">Tidak ada lelang yang sedang diproses kandidat 2.</p>
                    </div>
                @endforelse
            </div>

            {{-- ================================================================
                 SECTION 3: Lelang Draft — Semua kandidat gugur, perlu jadwal ulang
            ================================================================ --}}
            <div class="card mb-6">
                <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Perlu Dijadwalkan Ulang</h5>
                        <small class="text-muted">Semua kandidat gugur. Lelang jadi draft — admin perlu set jadwal baru.</small>
                    </div>
                    <span class="badge bg-label-warning rounded-pill px-3 py-2" style="font-size:.85rem;">
                        {{ $lelangDraft->count() }} Lelang
                    </span>
                </div>

                @forelse ($lelangDraft as $lelang)
                    @php
                        $k1 = $lelang->pemenang->firstWhere('urutan', 1);
                        $k2 = $lelang->pemenang->firstWhere('urutan', 2);
                    @endphp
                    <div class="card-body border-bottom py-4">
                        <div class="row g-4">

                            {{-- Info --}}
                            <div class="col-12 col-md-3">
                                <p class="text-uppercase text-muted fw-semibold mb-1" style="font-size:.7rem; letter-spacing:.08em;">Lelang</p>
                                <h6 class="mb-1 fw-bold">{{ $lelang->kode_lelang }}</h6>
                                <small class="text-muted">{{ $lelang->barang->nama ?? '-' }}</small>
                                <div class="mt-2">
                                    <span class="badge bg-label-secondary">Draft</span>
                                </div>
                            </div>

                            {{-- Riwayat Kandidat --}}
                            <div class="col-12 col-md-3">
                                <p class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.7rem; letter-spacing:.08em;">Riwayat Kandidat</p>
                                <div class="d-flex flex-column gap-2">
                                    @if ($k1)
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-label-danger" style="font-size:.7rem;">K1 Gugur</span>
                                            <small>{{ $k1->user->nama_lengkap ?? '-' }}</small>
                                        </div>
                                    @endif
                                    @if ($k2)
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-label-danger" style="font-size:.7rem;">K2 Gugur</span>
                                            <small>{{ $k2->user->nama_lengkap ?? '-' }}</small>
                                        </div>
                                    @else
                                        <small class="text-muted">Tidak ada kandidat 2</small>
                                    @endif
                                </div>
                            </div>

                            {{-- Form Jadwal Ulang --}}
                            <div class="col-12 col-md-6">
                                <p class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.7rem; letter-spacing:.08em;">Set Jadwal Baru</p>
                                <form method="POST" action="{{ route('backend.gagalbayar.jadwal-ulang', $lelang->kode_lelang) }}">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-5">
                                            <label class="form-label fw-medium" style="font-size:.82rem;">Jadwal Mulai</label>
                                            <input type="datetime-local"
                                                name="jadwal_mulai"
                                                placeholder="Pilih tanggal mulai"
                                                class="form-control flatpickr form-control-sm @error('jadwal_mulai') is-invalid @enderror"
                                                required />
                                            @error('jadwal_mulai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-5">
                                            <label class="form-label fw-medium" style="font-size:.82rem;">Jadwal Berakhir</label>
                                            <input type="datetime-local"
                                                name="jadwal_berakhir"
                                                placeholder="Pilih tanggal berakhir"
                                                class="form-control flatpickr form-control-sm @error('jadwal_berakhir') is-invalid @enderror"
                                                required />
                                            @error('jadwal_berakhir')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-2 d-flex align-items-end">
                                            <button type="submit"
                                                class="btn btn-success btn-sm w-100"
                                                onclick="return confirm('Jadwalkan ulang lelang {{ $lelang->kode_lelang }}? Semua bid dan kandidat lama akan dihapus.')">
                                                <i class="ri ri-calendar-check-line me-1"></i> Publish
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="card-body text-center py-5">
                        <i class="ri ri-checkbox-circle-line text-success" style="font-size:2rem;"></i>
                        <p class="mt-2 text-muted mb-0">Tidak ada lelang yang perlu dijadwalkan ulang.</p>
                    </div>
                @endforelse
            </div>

        </div>

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
@push("script")
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script>
    flatpickr('input[name="jadwal_mulai"], input[name="jadwal_berakhir"]', {
        enableTime: true,
        dateFormat: 'Y-m-d H:i:S', // ← S = detik, biar sesuai Laravel datetime
        time_24hr: true,
        minDate: 'today',
        allowInput: true,
    });
</script>
@endpush
