{{-- resources/views/admin/submissions/show.blade.php --}}
@extends('layouts.kerangkabackend')

@section('content')
<div class="container-fluid py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('backend.submissions.index') }}">Pengajuan</a></li>
            <li class="breadcrumb-item active">#{{ $submission->id }} — {{ $submission->nama_barang }}</li>
        </ol>
    </nav>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ── LEFT: Info Barang ──────────────────────────────────────────────── --}}
        <div class="col-lg-7">

            {{-- Foto Gallery --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom fw-semibold">
                    <i class="fas fa-images me-2 text-primary"></i> Foto Barang
                    <small class="text-muted ms-1">({{ count($submission->foto_barang ?? []) }} foto)</small>
                </div>
                <div class="card-body">
                    @if(!empty($submission->foto_barang))

                        {{-- Foto Utama + Zoom Result --}}
                        <div class="foto-main-wrap mb-3">
                            <div class="foto-main" id="foto-main-container">
                                <img id="main-foto"
                                     src="{{ Storage::url($submission->foto_barang[0]) }}"
                                     onclick="openFotoModal(currentIndex)"
                                     alt="foto utama">
                                <div class="zoom-lens" id="zoom-lens"></div>
                            </div>
                            <div class="zoom-result" id="zoom-result"></div>
                        </div>

                        {{-- Thumbnail Row --}}
                        @if(count($submission->foto_barang) > 1)
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($submission->foto_barang as $i => $foto)
                                <img src="{{ Storage::url($foto) }}"
                                     class="foto-thumb {{ $i === 0 ? 'active' : '' }}"
                                     data-index="{{ $i }}"
                                     onclick="switchThumb({{ $i }})"
                                     alt="foto {{ $i+1 }}">
                            @endforeach
                        </div>
                        @endif

                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p>Tidak ada foto</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Detail Barang --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom fw-semibold">
                    <i class="fas fa-box-open me-2 text-primary"></i> Informasi Barang
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted fw-normal" width="160">Nama Barang</th>
                            <td><strong>{{ $submission->nama_barang }}</strong></td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Deskripsi</th>
                            <td style="white-space: pre-line;">{{ $submission->deskripsi }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Harga Ditawarkan</th>
                            <td>
                                <span class="fw-semibold fs-5">
                                    Rp {{ number_format($submission->harga_ditawarkan, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @if($submission->harga_deal)
                        <tr>
                            <th class="text-muted fw-normal">Harga Deal</th>
                            <td>
                                <span class="fw-bold fs-5 text-success">
                                    Rp {{ number_format($submission->harga_deal, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th class="text-muted fw-normal">Status</th>
                            <td>
                                <span class="badge bg-{{ $submission->status_badge }} fs-6">
                                    {{ $submission->status_label }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Diajukan</th>
                            <td>{{ $submission->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @if($submission->reviewed_at)
                        <tr>
                            <th class="text-muted fw-normal">Direview</th>
                            <td>
                                {{ $submission->reviewed_at->format('d M Y, H:i') }}
                                @if($submission->reviewer)
                                    <small class="text-muted">oleh {{ $submission->reviewer->name }}</small>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @if($submission->catatan_admin)
                        <tr>
                            <th class="text-muted fw-normal">Catatan Admin</th>
                            <td>
                                <div class="alert alert-warning mb-0 py-2 px-3">
                                    {{ $submission->catatan_admin }}
                                </div>
                            </td>
                        </tr>
                        @endif
                        @if($submission->is_purchased)
                        <tr>
                            <th class="text-muted fw-normal">Dibeli Platform</th>
                            <td>
                                <span class="text-success fw-semibold">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $submission->paid_at?->format('d M Y, H:i') }}
                                </span>
                            </td>
                        </tr>
                        @endif
                        @if($submission->convertedItem)
                        <tr>
                            <th class="text-muted fw-normal">Item Lelang</th>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-gavel me-1"></i>
                                    Lihat Item #{{ $submission->converted_item_id }}
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>

        {{-- ── RIGHT: Kontak User + Aksi Admin ──────────────────────────────── --}}
        <div class="col-lg-5">

            {{-- Info Kontak User --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom fw-semibold">
                    <i class="fas fa-address-card me-2 text-primary"></i> Kontak User
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">User</small>
                        <strong>{{ $submission->user->name }}</strong>
                        <span class="text-muted ms-2">{{ $submission->user->email }}</span>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        {{-- WhatsApp --}}
                        <div class="kontak-item">
                            <div class="kontak-icon bg-success bg-opacity-10 text-success">
                                <i class="ri ri-whatsapp-line"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">WhatsApp</small>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $submission->nomor_whatsapp) }}"
                                   target="_blank"
                                   class="fw-semibold text-success text-decoration-none">
                                    {{ $submission->nomor_whatsapp }}
                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                            </div>
                        </div>
                        {{-- Telepon --}}
                        <div class="kontak-item">
                            <div class="kontak-icon bg-primary bg-opacity-10 text-primary">
                                <i class="ri ri-phone-line"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Telepon</small>
                                <a href="tel:{{ $submission->nomor_telepon }}"
                                   class="fw-semibold text-decoration-none">
                                    {{ $submission->nomor_telepon }}
                                </a>
                            </div>
                        </div>
                        {{-- Alamat --}}
                        <div class="kontak-item align-items-start">
                            <div class="kontak-icon bg-danger bg-opacity-10 text-danger">
                                <i class="ri ri-map-pin-line"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Alamat (Cek Fisik)</small>
                                <span class="fw-semibold" style="white-space:pre-line">{{ $submission->alamat_lengkap }}</span>
                                <div id="map" class="mt-3" style="height:250px; border-radius:10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Panel Aksi Admin ──────────────────────────────────────────── --}}
            @if(!in_array($submission->status, ['rejected', 'purchased']))

                {{-- Form: Under Review / Approve / Reject --}}
                @if($submission->status !== 'purchased')
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header border-bottom fw-semibold">
                        <i class="fas fa-tasks me-2 text-primary"></i> Aksi Review
                    </div>
                    <div class="card-body">
                        <form action="{{ route('backend.submissions.updateStatus', $submission) }}" method="POST">
                            @csrf

                            {{-- Pilih Aksi --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih Aksi</label>
                                <div class="d-flex flex-column gap-2">
                                    @if($submission->status === 'pending')
                                    <label class="action-radio">
                                        <input type="radio" name="action" value="under_review" required>
                                        <div class="action-radio__box border-info">
                                            <i class="fas fa-search text-info me-2"></i>
                                            <div>
                                                <div class="fw-semibold">Tinjau Lebih Lanjut</div>
                                                <small class="text-muted">Status jadi "Sedang Ditinjau"</small>
                                            </div>
                                        </div>
                                    </label>
                                    @endif

                                    <label class="action-radio">
                                        <input type="radio" name="action" value="approve" required id="radio-approve">
                                        <div class="action-radio__box border-success">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <div>
                                                <div class="fw-semibold">Setujui & Deal Harga</div>
                                                <small class="text-muted">Barang diterima, isi harga deal</small>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="action-radio">
                                        <input type="radio" name="action" value="reject" required>
                                        <div class="action-radio__box border-danger">
                                            <i class="fas fa-times-circle text-danger me-2"></i>
                                            <div>
                                                <div class="fw-semibold">Tolak Pengajuan</div>
                                                <small class="text-muted">Barang tidak diterima platform</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Harga Deal (muncul saat approve) --}}
                            <div class="mb-3" id="harga-deal-field" style="display:none;">
                                <label class="form-label fw-semibold">
                                    Harga Deal <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga_deal" class="form-control"
                                           placeholder="Harga yang disepakati platform" min="1000"
                                           value="{{ old('harga_deal', $submission->harga_deal) }}">
                                </div>
                                <small class="text-muted">
                                    User menawarkan: <strong>Rp {{ number_format($submission->harga_ditawarkan, 0, ',', '.') }}</strong>
                                </small>
                                @error('harga_deal')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Catatan --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan untuk User</label>
                                <textarea name="catatan_admin" class="form-control" rows="3"
                                          placeholder="Alasan penolakan, hasil review, catatan negosiasi, dll.">{{ old('catatan_admin', $submission->catatan_admin) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i> Simpan Keputusan
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Form: Mark as Purchased (hanya setelah approved) --}}
                @if($submission->status === 'approved')
                <div class="card border-0 shadow-sm border border-success">
                    <div class="card-header bg-success text-white fw-semibold">
                        <i class="fas fa-money-bill-wave me-2"></i> Konfirmasi Pembayaran
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Setelah kamu membayar user sebesar
                            <strong class="text-success">Rp {{ number_format($submission->harga_deal, 0, ',', '.') }}</strong>,
                            klik tombol di bawah. Barang otomatis masuk ke daftar item lelang.
                        </p>
                        <form action="{{ route('backend.submissions.markAsPurchased', $submission) }}"
                              method="POST"
                              onsubmit="return confirm('Konfirmasi: kamu sudah membayar user dan barang sah milik platform?')">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 fw-semibold">
                                <i class="fas fa-check me-2"></i>
                                Sudah Dibayar → Masukkan ke Lelang
                            </button>
                        </form>
                    </div>
                </div>
                @endif

            @else
                {{-- Status final: ditolak / sudah dibeli --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        @if($submission->status === 'rejected')
                            <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                            <h6 class="text-danger">Pengajuan Ditolak</h6>
                        @else
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h6 class="text-success">Barang Sudah Dibeli Platform</h6>
                            @if($submission->convertedItem)
                                <a href="#" class="btn btn-outline-success btn-sm mt-2">
                                    <i class="fas fa-gavel me-1"></i> Lihat di Lelang
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- ── Modal Foto ──────────────────────────────────────────────────────────── --}}
<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0 pb-0 px-3 pt-3">
                <small class="text-muted" id="modal-counter"></small>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-3 pt-2 pb-3">
                {{-- Foto utama modal --}}
                <div class="position-relative d-flex align-items-center justify-content-center">
                    <button class="modal-nav modal-nav--prev" onclick="modalNav(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <img id="modal-main-foto"
                         src=""
                         class="rounded"
                         style="max-height:520px; max-width:100%; object-fit:contain;">
                    <button class="modal-nav modal-nav--next" onclick="modalNav(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                {{-- Thumbnail strip --}}
                @if(!empty($submission->foto_barang) && count($submission->foto_barang) > 1)
                <div class="d-flex gap-2 flex-wrap justify-content-center mt-3" id="modal-thumb-strip">
                    @foreach($submission->foto_barang as $i => $foto)
                        <img src="{{ Storage::url($foto) }}"
                             class="modal-thumb {{ $i === 0 ? 'active' : '' }}"
                             data-index="{{ $i }}"
                             onclick="goToFoto({{ $i }})"
                             alt="foto {{ $i+1 }}">
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>

/* ── Foto Main + Zoom Magnifier ────────────────────────────────────────────── */
.foto-main-wrap {
    display: flex;
    gap: 14px;
    align-items: flex-start;
}
.foto-main {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    flex: 1;
    cursor: crosshair;
    border: 1px solid #e5e5e5;
    background: #fafafa;
}
.foto-main img {
    display: block;
    width: 100%;
    max-height: 380px;
    object-fit: contain;
}

/* Kotak lens di atas gambar */
.zoom-lens {
    position: absolute;
    width: 100px;
    height: 100px;
    border: 2px solid #0d6efd;
    background: rgba(13, 110, 253, 0.08);
    border-radius: 4px;
    pointer-events: none;
    display: none;
    z-index: 5;
}

/* Kotak hasil zoom di sebelah kanan */
.zoom-result {
    width: 280px;
    height: 280px;
    border-radius: 10px;
    border: 1px solid #e5e5e5;
    background-repeat: no-repeat;
    background-color: #f8f9fa;
    display: none;
    flex-shrink: 0;
    overflow: hidden;
}

/* Sembunyikan zoom di layar kecil */
@media (max-width: 900px) {
    .zoom-result { display: none !important; }
    .zoom-lens   { display: none !important; }
    .foto-main   { cursor: pointer; }
}

/* ── Thumbnail ─────────────────────────────────────────────────────────────── */
.foto-thumb {
    width: 68px;
    height: 68px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    border: 2px solid transparent;
    opacity: 0.65;
    transition: border-color 0.2s, opacity 0.2s;
}
.foto-thumb.active,
.foto-thumb:hover {
    border-color: var(--bs-primary);
    opacity: 1;
}

/* ── Modal Foto ────────────────────────────────────────────────────────────── */
.modal-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #fff;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    cursor: pointer;
    transition: background 0.2s;
    backdrop-filter: blur(4px);
}
.modal-nav:hover { background: rgba(255, 255, 255, 0.3); }
.modal-nav--prev { left: 8px; }
.modal-nav--next { right: 8px; }

.modal-thumb {
    width: 64px;
    height: 64px;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    border: 2px solid transparent;
    opacity: 0.5;
    transition: border-color 0.2s, opacity 0.2s;
}
.modal-thumb.active,
.modal-thumb:hover {
    border-color: #fff;
    opacity: 1;
}

/* ── Kontak ────────────────────────────────────────────────────────────────── */
.kontak-item { display: flex; align-items: center; gap: 12px; }
.kontak-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 16px;
}
/* ── Action Radio ──────────────────────────────────────────────────────────── */
.action-radio input[type="radio"] { display: none; }
.action-radio__box {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.25s ease, border-color 0.25s ease,
                box-shadow 0.25s ease, transform 0.15s ease;
    user-select: none;
}
.action-radio__box:hover {
    background: rgba(128, 128, 128, 0.08);
    transform: translateX(2px);
}

/* ── Checked states ── */
.action-radio input:checked + .action-radio__box {
    transform: translateX(3px);
}
.action-radio input:checked + .action-radio__box.border-info {
    border-color: var(--bs-info) !important;
    background: rgba(13, 202, 240, 0.08);
    box-shadow: 0 0 0 3px rgba(13, 202, 240, 0.15), inset 3px 0 0 var(--bs-info);
}
.action-radio input:checked + .action-radio__box.border-success {
    border-color: var(--bs-success) !important;
    background: rgba(25, 135, 84, 0.08);
    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.15), inset 3px 0 0 var(--bs-success);
}
.action-radio input:checked + .action-radio__box.border-danger {
    border-color: var(--bs-danger) !important;
    background: rgba(220, 53, 69, 0.08);
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15), inset 3px 0 0 var(--bs-danger);
}

/* Dot indicator di kanan */
.action-radio__box::after {
    content: '';
    margin-left: auto;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #ccc;
    flex-shrink: 0;
    transition: background 0.2s, border-color 0.2s, transform 0.2s;
}
.action-radio input:checked + .action-radio__box.border-info::after {
    background: var(--bs-info);
    border-color: var(--bs-info);
    transform: scale(1.1);
}
.action-radio input:checked + .action-radio__box.border-success::after {
    background: var(--bs-success);
    border-color: var(--bs-success);
    transform: scale(1.1);
}
.action-radio input:checked + .action-radio__box.border-danger::after {
    background: var(--bs-danger);
    border-color: var(--bs-danger);
    transform: scale(1.1);
}
</style>
@endpush

@push('script')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const alamat = @json($submission->alamat_lengkap);
    console.log("Mencari koordinat untuk alamat:", alamat);
    // default ke Indonesia dulu (fallback)
    let map = L.map('map').setView([-2.5, 118], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // 🔥 geocoding alamat → jadi koordinat
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(alamat)}`)
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                console.log(data);
                let lat = data[0].lat;
                let lon = data[0].lon;

                map.setView([lat, lon], 15);

                L.marker([lat, lon]).addTo(map)
                    .bindPopup(alamat)
                    .openPopup();
            } else {
                console.warn("Alamat tidak ditemukan:", alamat);
            }
        });

});
// ── Data foto dari Laravel ──────────────────────────────────────────────────
const allFotos    = @json($submission->foto_barang ?? []);
const storageBase = '{{ Storage::url('') }}';
let currentIndex  = 0;

// ── Switch thumbnail (halaman utama) ────────────────────────────────────────
function switchThumb(index) {
    currentIndex = index;
    const src = storageBase + allFotos[index];

    document.getElementById('main-foto').src = src;
    document.querySelectorAll('.foto-thumb').forEach((t, i) =>
        t.classList.toggle('active', i === index)
    );

    // Update background zoom result biar langsung sinkron
    const result = document.getElementById('zoom-result');
    if (result) result.style.backgroundImage = `url('${src}')`;
}

// ── Zoom Magnifier ──────────────────────────────────────────────────────────
function initZoom() {
    const img       = document.getElementById('main-foto');
    const lens      = document.getElementById('zoom-lens');
    const result    = document.getElementById('zoom-result');
    const container = document.getElementById('foto-main-container');

    if (!img || !lens || !result || !container) return;

    const LENS_SIZE = 100; // ukuran kotak lens (px)

    lens.style.width  = LENS_SIZE + 'px';
    lens.style.height = LENS_SIZE + 'px';

    function getScale() {
        return {
            cx: result.offsetWidth  / LENS_SIZE,
            cy: result.offsetHeight / LENS_SIZE,
        };
    }

    function moveLens(e) {
        e.preventDefault();

        const imgRect = img.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        const { cx, cy } = getScale();

        // Posisi cursor relatif ke gambar
        let x = clientX - imgRect.left;
        let y = clientY - imgRect.top;

        // Clamp: lens tidak boleh keluar area gambar
        const half = LENS_SIZE / 2;
        x = Math.max(half, Math.min(x, imgRect.width  - half));
        y = Math.max(half, Math.min(y, imgRect.height - half));

        // Posisi lens
        lens.style.left = (x - half) + 'px';
        lens.style.top  = (y - half) + 'px';

        // Zoom result: background-image, size, position
        result.style.backgroundImage    = `url('${img.src}')`;
        result.style.backgroundSize     = `${img.offsetWidth * cx}px ${img.offsetHeight * cy}px`;
        result.style.backgroundPosition = `-${(x - half) * cx}px -${(y - half) * cy}px`;
    }

    container.addEventListener('mouseenter', () => {
        lens.style.display   = 'block';
        result.style.display = 'block';
    });
    container.addEventListener('mouseleave', () => {
        lens.style.display   = 'none';
        result.style.display = 'none';
    });
    container.addEventListener('mousemove', moveLens);
}

document.addEventListener('DOMContentLoaded', initZoom);

// ── Modal Foto ──────────────────────────────────────────────────────────────
let modalInstance = null;

function openFotoModal(index) {
    currentIndex = index;
    renderModal();
    if (!modalInstance) {
        modalInstance = new bootstrap.Modal(document.getElementById('fotoModal'));
    }
    modalInstance.show();
}

function goToFoto(index) {
    currentIndex = index;
    renderModal();
}

function modalNav(dir) {
    currentIndex = (currentIndex + dir + allFotos.length) % allFotos.length;
    renderModal();
}

function renderModal() {
    const src = storageBase + allFotos[currentIndex];

    document.getElementById('modal-main-foto').src = src;
    document.getElementById('modal-counter').textContent =
        `${currentIndex + 1} / ${allFotos.length}`;

    document.querySelectorAll('.modal-thumb').forEach((t, i) =>
        t.classList.toggle('active', i === currentIndex)
    );
}

// Keyboard navigation di modal
document.getElementById('fotoModal')?.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft')  modalNav(-1);
    if (e.key === 'ArrowRight') modalNav(1);
});

// ── Harga Deal toggle ───────────────────────────────────────────────────────
document.querySelectorAll('input[name="action"]').forEach(radio => {
    radio.addEventListener('change', function () {
        const f = document.getElementById('harga-deal-field');
        if (f) {
            f.style.display = this.value === 'approve' ? 'block' : 'none';
            f.querySelector('input').required = this.value === 'approve';
        }
    });
});

@if(old('action') === 'approve')
    document.addEventListener('DOMContentLoaded', () => {
        const f = document.getElementById('harga-deal-field');
        if (f) { f.style.display = 'block'; f.querySelector('input').required = true; }
    });
@endif
</script>
@endpush
