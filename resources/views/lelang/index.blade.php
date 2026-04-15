@extends('layouts.kerangkabackend')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- ─── Stat Widgets ─────────────────────────────────── --}}
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">Total Lelang</p>
                                    <h4 class="mb-1">{{ $lelangs->count() }}</h4>
                                    <p class="mb-0"><span class="me-2">Semua Lelang</span><span class="badge rounded-pill bg-label-primary">Aktif</span></p>
                                </div>
                                <div class="avatar me-sm-6">
                                    <span class="avatar-initial rounded-3 text-heading">
                                        <i class="icon-base ri ri-auction-line icon-28px"></i>
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-6" />
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">Sedang Dibuka</p>
                                    <h4 class="mb-1">{{ $lelangs->where('status','dibuka')->count() }}</h4>
                                    <p class="mb-0"><span class="me-2">-</span><span class="badge rounded-pill bg-label-success">Live</span></p>
                                </div>
                                <div class="avatar me-lg-6">
                                    <span class="avatar-initial rounded-3 text-heading">
                                        <i class="icon-base ri ri-live-line icon-28px"></i>
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none" />
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                <div>
                                    <p class="mb-1">Ditutup</p>
                                    <h4 class="mb-1">{{ $lelangs->where('status','ditutup')->count() }}</h4>
                                    <p class="mb-0"><span class="me-2">-</span><span class="badge rounded-pill bg-label-danger">Closed</span></p>
                                </div>
                                <div class="avatar me-sm-6">
                                    <span class="avatar-initial rounded-3 text-heading">
                                        <i class="icon-base ri ri-lock-line icon-28px"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1">Selesai</p>
                                    <h4 class="mb-1">{{ $lelangs->where('status','selesai')->count() }}</h4>
                                    <p class="mb-0"><span class="me-2">-</span><span class="badge rounded-pill bg-label-success">Completed</span></p>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded-3 text-heading">
                                        <i class="icon-base ri ri-checkbox-circle-line icon-28px"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Error Alerts ──────────────────────────────────── --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- ─── Main Card ────────────────────────────────────── --}}
        <div class="card position-relative">
            <div class="card-toast-wrapper" id="cardToastWrapper"></div>
            <div id="table-loader" class="table-loader-wrapper">
                <div class="loader"><span class="loader-inner"></span></div>
            </div>
            {{-- Filter Bar --}}
            <div class="card-header border-bottom">
                <div class="d-flex justify-content-between align-items-center row gap-5 gx-6 gap-md-0">
                    <div class="col-md-3 lelang_date_min"></div>
                    <div class="col-md-3 lelang_date_max"></div>
                    <div class="col-md-3 lelang_status"></div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="resetFilter" class="btn btn-outline-secondary w-100">
                            <i class="ri ri-refresh-line me-1"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            {{-- DataTable --}}
            <div class="card-datatable table-responsive">
                <table class="datatables-lelang table">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Kode Lelang</th>
                            <th>Nama Barang</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Berakhir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>{{-- /card --}}
    </div>{{-- /container --}}

    {{-- Footer --}}
    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl">
            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    © <script>document.write(new Date().getFullYear());</script>, made with ❤️ by
                    <a href="https://themeselection.com" target="_blank" class="footer-link fw-medium">ThemeSelection</a>
                </div>
            </div>
        </div>
    </footer>
    <div class="content-backdrop fade"></div>
</div>{{-- /content-wrapper --}}


{{-- ═══════════════════════════════════════════════════
     MODAL DETAIL — ID pakai $row->id (konsisten dengan JS)
═══════════════════════════════════════════════════ --}}
@foreach($lelangs as $row)
<div class="modal fade" id="modalDetail-{{ $row->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-simple">
        <div class="modal-content">
            <div class="modal-body p-0">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="icon-base ri ri-eye-line icon-28px"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Detail Lelang</h5>
                            <small class="text-muted">Informasi lengkap lelang.</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="px-5 py-4">

                    {{-- Foto --}}
                    <div class="d-flex justify-content-center mb-4">
                        <img src="{{ Storage::url($row->barang->foto) }}"
                             alt="{{ $row->barang->nama }}"
                             class="rounded-3 shadow-sm"
                             style="width:120px;height:120px;object-fit:cover;">
                    </div>

                    {{-- Identifikasi --}}
                    <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">
                        <i class="ri ri-barcode-line me-1"></i> Identifikasi
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label class="form-label fw-medium text-muted">Kode Lelang</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri ri-barcode-line"></i></span>
                                <input type="text" class="form-control" value="{{ $row->kode_lelang }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-medium text-muted">Nama Barang</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri ri-archive-2-line"></i></span>
                                <input type="text" class="form-control" value="{{ $row->barang->nama }}" disabled>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Jadwal --}}
                    <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">
                        <i class="ri ri-calendar-line me-1"></i> Jadwal Lelang
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Tanggal Mulai</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri ri-calendar-event-line"></i></span>
                                <input type="text" class="form-control"
                                       value="{{ \Carbon\Carbon::parse($row->jadwal_mulai)->format('d F Y, H:i') }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Tanggal Berakhir</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri ri-calendar-check-line"></i></span>
                                <input type="text" class="form-control"
                                       value="{{ \Carbon\Carbon::parse($row->jadwal_berakhir)->format('d F Y, H:i') }}" disabled>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Status --}}
                    <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">
                        <i class="ri ri-information-line me-1"></i> Status
                    </p>
                    @php
                        $badgeCls = match($row->status) {
                            'dibuka'  => 'bg-label-success',
                            'ditutup' => 'bg-label-warning',
                            'selesai' => 'bg-label-danger',
                            default   => 'bg-label-secondary',
                        };
                    @endphp
                    <span class="badge rounded-pill {{ $badgeCls }}" style="font-size:13px;padding:7px 16px;">
                        {{ ucfirst($row->status) }}
                    </span>

                </div>

                {{-- Footer --}}
                <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ri ri-close-line me-1"></i> Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endforeach


{{-- ═══════════════════════════════════════════════════
     MODAL TAMBAH LELANG
═══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalAddLelang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-body p-0">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="icon-base ri ri-auction-line icon-28px"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Tambah Lelang Baru</h5>
                            <small class="text-muted">Isi formulir berikut untuk membuat lelang baru.</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formAddLelang" action="{{ route('backend.lelang.store') }}" method="POST">
                    @csrf
                    <div class="px-5 py-4">

                        {{-- Identifikasi --}}
                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">
                            <i class="ri ri-barcode-line me-1"></i> Identifikasi
                        </p>
                        <div class="row g-4 mb-4">
                            <div class="col-md-5">
                                <label class="form-label fw-medium" for="addKodeLelang">
                                    Kode Lelang <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri ri-barcode-line"></i></span>
                                    <input type="text" id="addKodeLelang" name="kode_lelang"
                                           class="form-control" placeholder="Generate otomatis…" required readonly>
                                    <span class="input-group-text cursor-pointer" id="btnGenKode" title="Generate ulang">
                                        <i class="ri ri-refresh-line"></i>
                                    </span>
                                </div>
                                <small class="text-muted">Klik <i class="ri ri-refresh-line"></i> untuk generate ulang</small>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-medium" for="addBarang">
                                    Barang <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri ri-archive-2-line"></i></span>
                                    <select id="addBarang" name="id_barang" class="form-select" required>
                                        <option value="">Pilih barang…</option>
                                        @foreach($barangs as $b)
                                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Jadwal --}}
                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">
                            <i class="ri ri-calendar-line me-1"></i> Jadwal Lelang
                        </p>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium" for="addJadwalMulai">
                                    Tanggal Mulai <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri ri-calendar-event-line"></i></span>
                                    <input type="text" id="addJadwalMulai" name="jadwal_mulai"
                                           class="form-control" placeholder="Pilih tanggal & waktu" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium" for="addJadwalBerakhir">
                                    Tanggal Berakhir <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri ri-calendar-check-line"></i></span>
                                    <input type="text" id="addJadwalBerakhir" name="jadwal_berakhir"
                                           class="form-control" placeholder="Pilih tanggal & waktu" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Status --}}
                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">
                            <i class="ri ri-information-line me-1"></i> Status Awal
                        </p>
                        @php
                            $statusOptions = [
                                'ditutup' => ['label'=>'Ditutup','badge'=>'bg-label-warning'],
                                'dibuka'  => ['label'=>'Dibuka', 'badge'=>'bg-label-success'],
                                'selesai' => ['label'=>'Selesai','badge'=>'bg-label-danger'],
                            ];
                        @endphp
                        <div class="d-flex gap-3 flex-wrap">
                            @foreach($statusOptions as $val => $opt)
                            <div class="form-check border rounded px-4 py-2 kondisi-opt">
                                <input class="form-check-input" type="radio"
                                       name="status" id="addStatus-{{ $val }}" value="{{ $val }}"
                                       {{ $val==='ditutup' ? 'checked' : '' }}>
                                <label class="form-check-label" style="cursor:pointer" for="addStatus-{{ $val }}">
                                    <span class="badge {{ $opt['badge'] }}">{{ $opt['label'] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="ri ri-close-line me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i> Simpan Lelang
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════
     MODAL EDIT LELANG
═══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEditLelang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-body p-0">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="icon-base ri ri-edit-box-line icon-28px"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Edit Lelang</h5>
                            <small class="text-muted">Ubah informasi lelang yang sudah ada.</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formEditLelang" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-5 py-4">

                        {{-- Identifikasi --}}
                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">
                            <i class="ri ri-barcode-line me-1"></i> Identifikasi
                        </p>
                        <div class="row g-4 mb-4">
                            <div class="col-md-5">
                                <label class="form-label fw-medium" for="editKodeLelang">
                                    Kode Lelang <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri ri-barcode-line"></i></span>
                                    <input type="text" id="editKodeLelang" name="kode_lelang"
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-medium" for="editBarang">
                                    Barang <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri ri-archive-2-line"></i></span>
                                    <select id="editBarang" name="id_barang" class="form-select" required>
                                        <option value="">Pilih barang…</option>
                                        @foreach($barangs as $b)
                                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Jadwal --}}
                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">
                            <i class="ri ri-calendar-line me-1"></i> Jadwal Lelang
                        </p>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium" for="editJadwalMulai">
                                    Tanggal Mulai <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri ri-calendar-event-line"></i></span>
                                    <input type="text" id="editJadwalMulai" name="jadwal_mulai"
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium" for="editJadwalBerakhir">
                                    Tanggal Berakhir <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri ri-calendar-check-line"></i></span>
                                    <input type="text" id="editJadwalBerakhir" name="jadwal_berakhir"
                                           class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Status --}}
                        <p class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">
                            <i class="ri ri-information-line me-1"></i> Status
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            @foreach($statusOptions as $val => $opt)
                            <div class="form-check border rounded px-4 py-2 kondisi-opt">
                                <input class="form-check-input" type="radio"
                                       name="status" id="editStatus-{{ $val }}" value="{{ $val }}">
                                <label class="form-check-label" style="cursor:pointer" for="editStatus-{{ $val }}">
                                    <span class="badge {{ $opt['badge'] }}">{{ $opt['label'] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="ri ri-close-line me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="ri ri-save-line me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- style kondisi-opt (sama persis dengan barang) --}}
<style>
    .kondisi-opt:has(input:checked) {
        border-color: var(--bs-primary) !important;
        background-color: rgba(var(--bs-primary-rgb), .05);
    }
</style>
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>

    <script>
        const lelangsData = @json($lelangs->load('barang'));
    </script>

    <script src="{{ asset('assets/js/custom/toast.js') }}"></script>
    <script src="{{ asset('assets/js/custom/lelang.js') }}"></script>
@endpush
