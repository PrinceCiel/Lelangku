@extends('layouts.kerangkafrontend')
@section('content')

<div class="hero-section">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('submissions.index') }}">Pengajuan Saya</a></li>
            <li><span>{{ Str::limit($submission->nama_barang, 30) }}</span></li>
        </ul>
    </div>
    <div class="bg_img hero-bg bottom_center"
         data-background="{{ asset('sbidu/assets/images/banner/hero-bg.png') }}"></div>
</div>

<section class="contact-section padding-bottom">
    <div class="container">
        <div class="contact-wrapper padding-top padding-bottom mt--100 mt-lg--440" style="border-radius: 20px; border: 1px solid #e9ecef; padding: 40px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">

            {{-- ── Header + Status ─────────────────────────────────────────── --}}
            <div class="submission-detail-header mb-5" data-aos="fade-up">
                <div>
                    <h3 class="mb-2">{{ $submission->nama_barang }}</h3>
                    <p class="text-muted mb-0" style="font-size: 14px;">
                        Diajukan {{ $submission->created_at->diffForHumans() }} &middot;
                        #{{ str_pad($submission->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <span class="status-pill status-pill--{{ $submission->status }}">
                    {{ $submission->status_label }}
                </span>
            </div>

            {{-- ── Timeline Status ─────────────────────────────────────────── --}}
            <div class="status-timeline mb-5" data-aos="fade-up" data-aos-delay="100">
                @php
                    $steps = [
                        'pending'      => ['label' => 'Menunggu Review',    'icon' => 'fas fa-clock'],
                        'under_review' => ['label' => 'Sedang Ditinjau',    'icon' => 'fas fa-search'],
                        'approved'     => ['label' => 'Disetujui',          'icon' => 'fas fa-check'],
                        'purchased'    => ['label' => 'Dibeli Platform',    'icon' => 'fas fa-shopping-bag'],
                    ];
                    $statusOrder = ['pending', 'under_review', 'approved', 'purchased'];
                    $currentIdx  = array_search($submission->status, $statusOrder);
                    if ($submission->status === 'rejected') $currentIdx = -1;
                @endphp

                @if($submission->status === 'rejected')
                    <div class="timeline-rejected">
                        <i class="fas fa-times-circle me-2"></i>
                        Pengajuan ini ditolak
                    </div>
                @else
                    <div class="timeline-steps">
                        @foreach($steps as $key => $step)
                            @php
                                $stepIdx = array_search($key, $statusOrder);
                                $isDone  = $stepIdx <= $currentIdx;
                                $isActive= $stepIdx === $currentIdx;
                            @endphp
                            <div class="timeline-step {{ $isDone ? 'done' : '' }} {{ $isActive ? 'active' : '' }}">
                                <div class="timeline-step__dot">
                                    <i class="{{ $step['icon'] }}"></i>
                                </div>
                                <div class="timeline-step__label">{{ $step['label'] }}</div>
                                @if(!$loop->last)
                                    <div class="timeline-step__line {{ $isDone && !$isActive ? 'done' : '' }}"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="row g-4">

                {{-- ── LEFT: Foto + Info Barang ────────────────────────────── --}}
                <div class="col-lg-7" data-aos="fade-up" data-aos-delay="150">

                    {{-- Foto Gallery --}}
                    @if(!empty($submission->foto_barang))
                    <div class="detail-card mb-4">
                        <div class="detail-card__title">
                            <i class="fas fa-images me-2"></i> Foto Barang
                        </div>
                        <div class="foto-main mb-3 position-relative">

                            <img id="main-foto"
                                src="{{ Storage::url($submission->foto_barang[0]) }}"
                                data-index="0"
                                alt="{{ $submission->nama_barang }}">

                            @if(count($submission->foto_barang) > 1)
                            <button class="foto-nav prev" onclick="prevFoto()">‹</button>
                            <button class="foto-nav next" onclick="nextFoto()">›</button>
                            @endif

                        </div>
                        @if(count($submission->foto_barang) > 1)
                        <div class="foto-thumbs">
                            @foreach($submission->foto_barang as $i => $foto)
                                <img src="{{ Storage::url($foto) }}"
                                     class="foto-thumb {{ $i === 0 ? 'active' : '' }}"
                                     onclick="switchFoto(this, {{ $i }})"
                                     alt="foto {{ $i+1 }}">
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Info Barang --}}
                    <div class="detail-card">
                        <div class="detail-card__title">
                            <i class="fas fa-box-open me-2"></i> Informasi Barang
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Nama Barang</span>
                            <span class="detail-row__value fw-semibold">{{ $submission->nama_barang }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Deskripsi</span>
                            <span class="detail-row__value" style="white-space:pre-line">{{ $submission->deskripsi }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Harga Ditawarkan</span>
                            <span class="detail-row__value fw-semibold fs-5 text-primary">
                                Rp {{ number_format($submission->harga_ditawarkan, 0, ',', '.') }}
                            </span>
                        </div>
                        @if($submission->harga_deal)
                        <div class="detail-row">
                            <span class="detail-row__label">Harga Deal</span>
                            <span class="detail-row__value fw-bold fs-5 text-success">
                                Rp {{ number_format($submission->harga_deal, 0, ',', '.') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ── RIGHT: Status Detail + Kontak ──────────────────────── --}}
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="200">

                    {{-- Catatan dari Admin --}}
                    @if($submission->catatan_admin)
                    <div class="detail-card mb-4 detail-card--warning">
                        <div class="detail-card__title">
                            <i class="fas fa-comment-dots me-2"></i> Catatan dari Admin
                        </div>
                        <p class="mb-0" style="white-space:pre-line; line-height: 1.6;">{{ $submission->catatan_admin }}</p>
                    </div>
                    @endif

                    {{-- Info khusus per status --}}
                    @if($submission->status === 'pending')
                    <div class="detail-card mb-4 detail-card--info">
                        <div class="detail-card__title">
                            <i class="fas fa-info-circle me-2"></i> Apa yang terjadi selanjutnya?
                        </div>
                        <p class="mb-0 small" style="line-height: 1.6;">
                            Admin kami akan segera meninjau pengajuan kamu dan menghubungi lewat
                            <strong>WhatsApp atau telepon</strong> yang kamu daftarkan untuk proses verifikasi.
                        </p>
                    </div>
                    @elseif($submission->status === 'under_review')
                    <div class="detail-card mb-4 detail-card--info">
                        <div class="detail-card__title">
                            <i class="fas fa-search me-2"></i> Sedang dalam proses review
                        </div>
                        <p class="mb-0 small" style="line-height: 1.6;">
                            Admin sedang meninjau barang kamu. Harap standby — admin akan
                            menghubungi kamu untuk pengecekan fisik atau virtual.
                        </p>
                    </div>
                    @elseif($submission->status === 'approved')
                    <div class="detail-card mb-4 detail-card--success">
                        <div class="detail-card__title">
                            <i class="fas fa-check-circle me-2"></i> Pengajuan Disetujui!
                        </div>
                        <p class="mb-2 small" style="line-height: 1.6;">
                            Selamat! Barang kamu diterima platform dengan harga deal:
                        </p>
                        <div class="harga-deal-box">
                            Rp {{ number_format($submission->harga_deal, 0, ',', '.') }}
                        </div>
                        <p class="mt-3 mb-0 small text-muted" style="line-height: 1.6;">
                            Admin akan segera melakukan pembayaran ke kamu. Tunggu konfirmasi selanjutnya.
                        </p>
                    </div>
                    @elseif($submission->status === 'purchased')
                    <div class="detail-card mb-4 detail-card--success">
                        <div class="detail-card__title">
                            <i class="fas fa-shopping-bag me-2"></i> Transaksi Selesai
                        </div>
                        <p class="mb-2 small">Barang telah dibeli platform pada:</p>
                        <strong class="d-block mb-2">{{ $submission->paid_at?->format('d M Y, H:i') }} WIB</strong>
                        <p class="mt-2 mb-0 small text-muted" style="line-height: 1.6;">
                            Barang kamu sekarang sudah masuk ke daftar lelang platform. Terima kasih!
                        </p>
                    </div>
                    @elseif($submission->status === 'rejected')
                    <div class="detail-card mb-4 detail-card--danger">
                        <div class="detail-card__title">
                            <i class="fas fa-times-circle me-2"></i> Pengajuan Ditolak
                        </div>
                        <p class="mb-3 small" style="line-height: 1.6;">
                            Maaf, barang kamu tidak dapat diterima platform saat ini.
                            Kamu bisa mengajukan barang lain yang berbeda.
                        </p>
                        <a href="{{ route('submissions.create') }}" class="custom-button d-inline-block"
                           style="font-size:13px; padding: 8px 20px;">
                            Ajukan Barang Lain
                        </a>
                    </div>
                    @endif

                    {{-- Kontak yang didaftarkan --}}
                    <div class="detail-card mb-4">
                        <div class="detail-card__title">
                            <i class="fas fa-address-card me-2"></i> Kontak yang Didaftarkan
                        </div>
                        <div class="kontak-item mb-3">
                            <div class="kontak-icon kontak-icon--wa">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">WhatsApp</small>
                                <strong>{{ $submission->nomor_whatsapp }}</strong>
                            </div>
                        </div>
                        <div class="kontak-item mb-3">
                            <div class="kontak-icon kontak-icon--tel">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">Telepon</small>
                                <strong>{{ $submission->nomor_telepon }}</strong>
                            </div>
                        </div>
                        <div class="kontak-item">
                            <div class="kontak-icon kontak-icon--addr">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">Alamat</small>
                                <span style="white-space:pre-line; line-height: 1.6;">{{ $submission->alamat_lengkap }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Timestamps --}}
                    <div class="detail-card">
                        <div class="detail-card__title">
                            <i class="fas fa-history me-2"></i> Riwayat
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Diajukan</span>
                            <span class="detail-row__value small">
                                {{ $submission->created_at->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                        @if($submission->reviewed_at)
                        <div class="detail-row">
                            <span class="detail-row__label">Direview</span>
                            <span class="detail-row__value small">
                                {{ $submission->reviewed_at->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                        @endif
                        @if($submission->paid_at)
                        <div class="detail-row">
                            <span class="detail-row__label">Dibeli</span>
                            <span class="detail-row__value small">
                                {{ $submission->paid_at->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Back button --}}
            <div class="mt-5 text-center" data-aos="fade-up">
                <a href="{{ route('submissions.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Pengajuan Saya
                </a>
            </div>

        </div>
    </div>
</section>
@endsection

@push('style')
<style>
/* ═══════════════════════════════════════════════════════════════════════════
   SUBMISSION DETAIL - IMPROVED STYLING
   ═══════════════════════════════════════════════════════════════════════════ */

.foto-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.5);
    border: none;
    color: #fff;
    font-size: 22px;
    padding: 6px 12px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
}

.foto-nav.prev {
    left: 10px;
}

.foto-nav.next {
    right: 10px;
}

/* ─────────────────────────────────────────────────────────────────────────
   Container Wrapper
   ───────────────────────────────────────────────────────────────────────── */
.contact-wrapper {
    position: relative;
    overflow: hidden;
}

.contact-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    /* background: linear-gradient(90deg, var(--primary-color, #6c63ff) 0%, #a29bfe 100%); */
    border-radius: 20px 20px 0 0;
}

/* ─────────────────────────────────────────────────────────────────────────
   Header Section
   ───────────────────────────────────────────────────────────────────────── */
.submission-detail-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

.submission-detail-header h3 {
    font-size: 26px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.3;
}

/* Status Pills */
.status-pill {
    display: inline-block;
    padding: 8px 18px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    flex-shrink: 0;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

.status-pill--pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-pill--under_review {
    background: #cff4fc;
    color: #055160;
    border: 1px solid #9eeaf9;
}

.status-pill--approved {
    background: #d1e7dd;
    color: #0a3622;
    border: 1px solid #a3cfbb;
}

.status-pill--purchased {
    background: #cfe2ff;
    color: #084298;
    border: 1px solid #9ec5fe;
}

.status-pill--rejected {
    background: #f8d7da;
    color: #842029;
    border: 1px solid #f1aeb5;
}

/* ─────────────────────────────────────────────────────────────────────────
   Timeline Section
   ───────────────────────────────────────────────────────────────────────── */
.status-timeline {
    padding: 30px 24px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    border: 1px solid #e9ecef;
}

.timeline-steps {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0;
    position: relative;
    max-width: 700px;
    margin: 0 auto;
}

.timeline-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
}

.timeline-step__dot {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #e9ecef;
    color: #adb5bd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    z-index: 2;
    transition: all 0.3s ease;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.timeline-step.done .timeline-step__dot {
    background: #d1e7dd;
    color: #0a3622;
    border-color: #a3cfbb;
}

.timeline-step.active .timeline-step__dot {
    background: var(--primary-color, #6c63ff);
    color: #fff;
    border-color: var(--primary-color, #6c63ff);
    box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
    transform: scale(1.1);
}

.timeline-step__label {
    font-size: 12px;
    text-align: center;
    margin-top: 12px;
    color: #6c757d;
    max-width: 90px;
    line-height: 1.4;
    font-weight: 500;
}

.timeline-step.done .timeline-step__label {
    color: #0a3622;
    font-weight: 600;
}

.timeline-step.active .timeline-step__label {
    color: var(--primary-color, #6c63ff);
    font-weight: 700;
}

.timeline-step__line {
    position: absolute;
    top: 22px;
    left: 50%;
    width: 100%;
    height: 3px;
    background: #dee2e6;
    z-index: 1;
}

.timeline-step__line.done {
    background: linear-gradient(90deg, #a3cfbb 0%, #d1e7dd 100%);
}

.timeline-rejected {
    text-align: center;
    color: #842029;
    background: #f8d7da;
    border-radius: 10px;
    padding: 16px 20px;
    font-weight: 600;
    font-size: 15px;
    border: 1px solid #f1aeb5;
}

.timeline-rejected i {
    font-size: 18px;
}

/* ─────────────────────────────────────────────────────────────────────────
   Detail Cards
   ───────────────────────────────────────────────────────────────────────── */
.detail-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    border: 1px solid #f0f0f0;
    transition: box-shadow 0.3s ease;
}

.detail-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.detail-card--warning {
    background: linear-gradient(135deg, #fff8e1 0%, #fffbf0 100%);
    border-color: #ffe082;
}

.detail-card--info {
    background: linear-gradient(135deg, #e8f4fd 0%, #f5faff 100%);
    border-color: #b8daff;
}

.detail-card--success {
    background: linear-gradient(135deg, #d1e7dd 0%, #e8f5e9 100%);
    border-color: #a3cfbb;
}

.detail-card--danger {
    background: linear-gradient(135deg, #f8d7da 0%, #ffe6e8 100%);
    border-color: #f1aeb5;
}

.detail-card__title {
    font-weight: 700;
    font-size: 15px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid rgba(0, 0, 0, 0.06);
    color: #1a1a1a;
    display: flex;
    align-items: center;
}

.detail-card__title i {
    font-size: 16px;
}

/* ─────────────────────────────────────────────────────────────────────────
   Detail Rows
   ───────────────────────────────────────────────────────────────────────── */
.detail-row {
    display: flex;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px solid #f5f5f5;
    align-items: flex-start;
}

.detail-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.detail-row__label {
    flex-shrink: 0;
    width: 140px;
    color: #6c757d;
    font-size: 13px;
    font-weight: 500;
}

.detail-row__value {
    font-size: 14px;
    color: #212529;
    line-height: 1.6;
    flex: 1;
}

/* ─────────────────────────────────────────────────────────────────────────
   Photo Gallery
   ───────────────────────────────────────────────────────────────────────── */
.foto-main {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    background: #f8f9fa;
}

.foto-main img {
    width: 100%;
    max-height: 360px;
    object-fit: contain;
    display: block;
}

.foto-thumbs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.foto-thumb {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 10px;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.foto-thumb:hover {
    border-color: var(--primary-color, #6c63ff);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 99, 255, 0.2);
}

.foto-thumb.active {
    border-color: var(--primary-color, #6c63ff);
    box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
}

/* ─────────────────────────────────────────────────────────────────────────
   Contact Items
   ───────────────────────────────────────────────────────────────────────── */
.kontak-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
}

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

.kontak-icon--wa {
    background: #d4edda;
    color: #155724;
}

.kontak-icon--tel {
    background: #cce5ff;
    color: #004085;
}

.kontak-icon--addr {
    background: #f8d7da;
    color: #721c24;
}

/* ─────────────────────────────────────────────────────────────────────────
   Harga Deal Highlight
   ───────────────────────────────────────────────────────────────────────── */
.harga-deal-box {
    background: #fff;
    border-radius: 10px;
    padding: 14px 20px;
    font-size: 22px;
    font-weight: 700;
    color: #0a3622;
    display: inline-block;
    border: 2px solid #a3cfbb;
    box-shadow: 0 2px 8px rgba(10, 54, 34, 0.1);
}

/* ─────────────────────────────────────────────────────────────────────────
   Back Button
   ───────────────────────────────────────────────────────────────────────── */
.btn-back {
    display: inline-flex;
    align-items: center;
    color: var(--primary-color, #6c63ff);
    text-decoration: none;
    font-size: 15px;
    font-weight: 600;
    padding: 10px 24px;
    border-radius: 8px;
    transition: all 0.3s ease;
    background: transparent;
    border: 2px solid var(--primary-color, #6c63ff);
}

.btn-back:hover {
    background: var(--primary-color, #6c63ff);
    color: #fff;
    transform: translateX(-4px);
}

/* ─────────────────────────────────────────────────────────────────────────
   Responsive Adjustments
   ───────────────────────────────────────────────────────────────────────── */
@media (max-width: 768px) {
    .contact-wrapper {
        padding: 30px 20px !important;
        border-radius: 16px !important;
    }

    .submission-detail-header h3 {
        font-size: 22px;
    }

    .timeline-step__dot {
        width: 38px;
        height: 38px;
        font-size: 14px;
    }

    .timeline-step__label {
        font-size: 11px;
        max-width: 70px;
    }

    .detail-row {
        flex-direction: column;
        gap: 6px;
    }

    .detail-row__label {
        width: 100%;
    }

    .detail-card {
        padding: 20px;
    }

    .foto-main img {
        max-height: 280px;
    }

    .foto-thumb {
        width: 64px;
        height: 64px;
    }
}

@media (max-width: 576px) {
    .contact-wrapper {
        padding: 24px 16px !important;
        border-radius: 12px !important;
    }

    .status-pill {
        font-size: 11px;
        padding: 6px 14px;
    }

    .status-timeline {
        padding: 20px 16px;
    }

    .timeline-step__dot {
        width: 36px;
        height: 36px;
        font-size: 13px;
    }

    .timeline-step__label {
        font-size: 10px;
        max-width: 60px;
        margin-top: 8px;
    }
}
</style>
@endpush

@push('script')
<script>
let fotos = @json($submission->foto_barang);
let currentIndex = 0;

function showFoto(index) {
    currentIndex = index;

    document.getElementById('main-foto').src = '/storage/' + fotos[index];

    document.querySelectorAll('.foto-thumb').forEach((el, i) => {
        el.classList.toggle('active', i === index);
    });
}

function nextFoto() {
    let next = (currentIndex + 1) % fotos.length;
    showFoto(next);
}

function prevFoto() {
    let prev = (currentIndex - 1 + fotos.length) % fotos.length;
    showFoto(prev);
}

function switchFoto(el, index) {
    showFoto(index);
}
</script>
<script>
let startX = 0;

const mainFoto = document.getElementById('main-foto');

mainFoto.addEventListener('touchstart', e => {
    startX = e.touches[0].clientX;
});

mainFoto.addEventListener('touchend', e => {
    let endX = e.changedTouches[0].clientX;

    if (startX - endX > 50) nextFoto();
    if (endX - startX > 50) prevFoto();
});
</script>
@endpush
