@extends('layouts.kerangkafrontend')
@section('content')

<div class="hero-section">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><span>Pengajuan Saya</span></li>
        </ul>
    </div>
    <div class="bg_img hero-bg bottom_center"
         data-background="{{ asset('sbidu/assets/images/banner/hero-bg.png') }}"></div>
</div>

<section class="contact-section padding-bottom">
    <div class="container">
        <div class="contact-wrapper padding-top padding-bottom mt--100 mt-lg--440">

            <div class="section-header" data-aos="zoom-out-down" data-aos-duration="1200">
                <h5 class="cate">Riwayat</h5>
                <h2 class="title">Pengajuan Saya</h2>
                <p>Pantau status barang yang kamu ajukan ke platform.</p>
            </div>

            {{-- Tombol ajukan baru --}}
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('submissions.create') }}" class="custom-button">
                    <i class="fas fa-plus me-2"></i> Ajukan Barang Baru
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @forelse($submissions as $sub)
                <div class="submission-card mb-3" data-aos="fade-up">
                    <div class="submission-card__foto">
                        @if(!empty($sub->foto_barang[0]))
                            <img src="{{ Storage::url($sub->foto_barang[0]) }}" alt="{{ $sub->nama_barang }}">
                        @else
                            <div class="no-foto"><i class="fas fa-image"></i></div>
                        @endif
                    </div>

                    <div class="submission-card__body">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h5 class="mb-0">{{ $sub->nama_barang }}</h5>
                            <span class="badge bg-{{ $sub->status_badge }}">{{ $sub->status_label }}</span>
                        </div>

                        <p class="text-muted small mb-2">
                            Diajukan: {{ $sub->created_at->diffForHumans() }}
                        </p>

                        <div class="submission-card__meta">
                            <div>
                                <small class="label">Harga Ditawarkan</small>
                                <strong>Rp {{ number_format($sub->harga_ditawarkan, 0, ',', '.') }}</strong>
                            </div>
                            @if($sub->harga_deal)
                            <div>
                                <small class="label">Harga Deal</small>
                                <strong class="text-success">Rp {{ number_format($sub->harga_deal, 0, ',', '.') }}</strong>
                            </div>
                            @endif
                        </div>

                        @if($sub->catatan_admin)
                            <div class="catatan-admin mt-2">
                                <i class="fas fa-comment-dots me-1"></i>
                                <strong>Catatan Admin:</strong> {{ $sub->catatan_admin }}
                            </div>
                        @endif
                    </div>

                    <div class="submission-card__action">
                        <a href="{{ route('submissions.show', $sub) }}" class="btn-detail">
                            <i class="fas fa-eye me-1"></i> Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada pengajuan</h5>
                    <p class="text-muted">Kamu belum pernah mengajukan barang. Yuk mulai!</p>
                    <a href="{{ route('submissions.create') }}" class="custom-button mt-2">
                        Ajukan Sekarang
                    </a>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
</section>

<style>
.submission-card {
    display: flex;
    align-items: stretch;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    overflow: hidden;
    border: 1px solid #f0f0f0;
    transition: box-shadow 0.3s;
}
.submission-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.12); }

.submission-card__foto {
    width: 130px;
    flex-shrink: 0;
    background: #f5f5f5;
}
.submission-card__foto img,
.submission-card__foto .no-foto {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #ccc;
}

.submission-card__body {
    flex: 1;
    padding: 16px 20px;
}

.submission-card__meta {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
}
.submission-card__meta div { display: flex; flex-direction: column; }
.submission-card__meta .label { font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; }

.catatan-admin {
    font-size: 13px;
    background: #fff3cd;
    border-radius: 6px;
    padding: 8px 12px;
    color: #856404;
}

.submission-card__action {
    display: flex;
    align-items: center;
    padding: 16px;
    border-left: 1px solid #f0f0f0;
}
.btn-detail {
    display: inline-block;
    padding: 8px 18px;
    border-radius: 8px;
    background: var(--primary-color, #6c63ff);
    color: #fff;
    font-size: 13px;
    text-decoration: none;
    white-space: nowrap;
    transition: opacity 0.2s;
}
.btn-detail:hover { opacity: 0.85; color: #fff; }

@media (max-width: 576px) {
    .submission-card { flex-direction: column; }
    .submission-card__foto { width: 100%; height: 180px; }
    .submission-card__action { border-left: none; border-top: 1px solid #f0f0f0; }
}
</style>

@endsection
