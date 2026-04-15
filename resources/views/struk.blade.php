@extends('layouts.kerangkafrontend')
@section('content')

    <div class="hero-section style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home.user') }}">Home</a></li>
                <li><a href="#0">Pembayaran</a></li>
                <li><span>{{ $struk->kode_struk }}</span></li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center"
            data-background="{{ asset('sbidu/assets/images/banner/hero-bg.png') }}"></div>
    </div>

    <section class="dashboard-section padding-bottom mt--240 mt-lg--440 pos-rel">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">

                    @php
                        $bidakhir = $pemenang->bid;
                        $adminfee = $struk->total * 0.05;
                        $total    = $struk->total + $adminfee;
                        $batas    = $struk->tgl_trx->addHour();
                    @endphp

                    {{-- ======================================================
                         STATUS: BELUM DIBAYAR
                    ====================================================== --}}
                    @if ($struk->status == 'belum dibayar')

                        <div class="payment-card">

                            {{-- Status Banner --}}
                            <div class="payment-status-banner banner-waiting">
                                <div class="status-icon">
                                    <img src="{{ asset('gif/belum.gif') }}" alt="Menunggu" style="width:90px;">
                                </div>
                                <div class="status-text">
                                    <h4>Menunggu Pembayaran</h4>
                                    <span class="status-badge badge-warning">Belum Dibayar</span>
                                </div>
                            </div>

                            {{-- Kode Struk --}}
                            <div class="payment-code-bar">
                                <span class="code-label">Kode Transaksi</span>
                                <span class="code-value">{{ $struk->kode_struk }}</span>
                            </div>

                            {{-- Info Pemenang & Lelang --}}
                            <div class="payment-section">
                                <p class="section-label">Informasi Pemenang</p>
                                <div class="info-grid">
                                    <div class="info-row">
                                        <span class="info-key">Nama</span>
                                        <span class="info-val">{{ $pemenang->user->nama_lengkap }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-key">Lelang</span>
                                        <span class="info-val">{{ $struk->lelang->kode_lelang }} — {{ $struk->lelang->barang->nama }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-divider"></div>

                            {{-- Rincian Biaya --}}
                            <div class="payment-section">
                                <p class="section-label">Rincian Pembayaran</p>
                                <div class="info-grid">
                                    <div class="info-row">
                                        <span class="info-key">Bid Akhir</span>
                                        <span class="info-val">Rp{{ number_format($bidakhir, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-key">Biaya Admin (5%)</span>
                                        <span class="info-val">Rp{{ number_format($adminfee, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-key">Deposit</span>
                                        <span class="info-val" style="color: red">- Rp{{ number_format($deposit->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="total-row">
                                    <span>Total Tagihan</span>
                                    <span class="total-amount">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="payment-divider"></div>

                            {{-- Batas Waktu --}}
                            <div class="payment-section">
                                <div class="deadline-box">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <p class="deadline-label">Selesaikan pembayaran sebelum</p>
                                        <p class="deadline-time">{{ $batas->format('H:i') }} &bull; {{ $batas->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-divider"></div>

                            {{-- Aksi --}}
                            <div class="payment-section payment-actions">
                                <button id="pay-button" class="btn-pay-primary">
                                    <span id="button-text">
                                        <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
                                    </span>
                                </button>
                                <div class="fallback-check">
                                    <span>Sudah bayar tapi status belum berubah?</span>
                                    <form method="POST" action="{{ route('check.status', $struk->kode_struk) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-check-status">Cek Status</button>
                                    </form>
                                </div>
                            </div>

                        </div>

                    {{-- ======================================================
                         STATUS: PENDING (VA / Transfer Bank belum settle)
                    ====================================================== --}}
                    @elseif ($struk->status == 'pending')

                        <div class="payment-card">

                            <div class="payment-status-banner banner-pending">
                                <div class="status-icon">
                                    <img src="{{ asset('gif/pending.gif') }}" alt="Pending" style="width:90px;">
                                </div>
                                <div class="status-text">
                                    <h4>Pembayaran Sedang Diproses</h4>
                                    <span class="status-badge badge-pending">Menunggu Konfirmasi Bank</span>
                                </div>
                            </div>

                            <div class="payment-code-bar">
                                <span class="code-label">Kode Transaksi</span>
                                <span class="code-value">{{ $struk->kode_struk }}</span>
                            </div>

                            <div class="payment-section">
                                <p class="section-label">Informasi Pemenang</p>
                                <div class="info-grid">
                                    <div class="info-row">
                                        <span class="info-key">Nama</span>
                                        <span class="info-val">{{ $pemenang->user->nama_lengkap }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-key">Lelang</span>
                                        <span class="info-val">{{ $struk->lelang->kode_lelang }} — {{ $struk->lelang->barang->nama }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-divider"></div>

                            <div class="payment-section">
                                <p class="section-label">Rincian Pembayaran</p>
                                <div class="info-grid">
                                    <div class="info-row">
                                        <span class="info-key">Bid Akhir</span>
                                        <span class="info-val">Rp{{ number_format($bidakhir, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-key">Biaya Admin (5%)</span>
                                        <span class="info-val">Rp{{ number_format($adminfee, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-key">Deposit</span>
                                        <span class="info-val" style="color: red">- Rp{{ number_format($deposit->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="total-row">
                                    <span>Total Tagihan</span>
                                    <span class="total-amount">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="payment-divider"></div>

                            <div class="payment-section">
                                <div class="info-notice notice-pending">
                                    <i class="fas fa-info-circle"></i>
                                    <p>Pembayaran Anda sedang diproses oleh bank. Status akan otomatis diperbarui setelah dana masuk. Proses ini biasanya memakan waktu beberapa menit hingga 1 jam.</p>
                                </div>
                                <div class="fallback-check" style="margin-top: 16px;">
                                    <span>Status belum berubah?</span>
                                    <form method="POST" action="{{ route('check.status', $struk->kode_struk) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-check-status">Cek Status</button>
                                    </form>
                                </div>
                            </div>

                        </div>

                    {{-- ======================================================
                         STATUS: BERHASIL
                    ====================================================== --}}
                    @elseif ($struk->status == 'berhasil')

                        <div class="payment-card">

                            <div class="payment-status-banner banner-success">
                                <div class="status-icon">
                                    <img src="{{ asset('gif/success.gif') }}" alt="Berhasil" style="width:150px;">
                                </div>
                                <div class="status-text">
                                    <h4>Pembayaran Berhasil!</h4>
                                    <span class="status-badge badge-success">Lunas</span>
                                </div>
                            </div>

                            <div class="payment-code-bar">
                                <span class="code-label">Kode Transaksi</span>
                                <span class="code-value">{{ $struk->kode_struk }}</span>
                            </div>

                            <div class="payment-section">
                                <p class="section-label">Informasi Pemenang</p>
                                <div class="info-grid">
                                    <div class="info-row">
                                        <span class="info-key">Nama</span>
                                        <span class="info-val">{{ $pemenang->user->nama_lengkap }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-key">Lelang</span>
                                        <span class="info-val">{{ $struk->lelang->kode_lelang }} — {{ $struk->lelang->barang->nama }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-divider"></div>

                            <div class="payment-section">
                                <p class="section-label">Rincian Pembayaran</p>
                                <div class="info-grid">
                                    <div class="info-row">
                                        <span class="info-key">Bid Akhir</span>
                                        <span class="info-val">Rp{{ number_format($bidakhir, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-key">Biaya Admin (5%)</span>
                                        <span class="info-val">Rp{{ number_format($adminfee, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-key">Deposit</span>
                                        <span class="info-val" style="color: red">- Rp{{ number_format($deposit->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="total-row">
                                    <span>Total Dibayar</span>
                                    <span class="total-amount">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="payment-divider"></div>

                            {{-- Kode Unik / Kode Pengambilan --}}
                            <div class="payment-section">
                                <p class="section-label">Kode Pengambilan Barang</p>
                                <div class="receipt-code-box">
                                    <span class="receipt-code">{{ $struk->kode_unik }}</span>
                                    <small>Tunjukkan kode ini saat pengambilan barang</small>
                                </div>
                            </div>

                            <div class="payment-divider"></div>

                            <div class="payment-section">
                                <div class="info-notice notice-success">
                                    <i class="fas fa-check-circle"></i>
                                    <p>Pembayaran Anda telah kami terima. Hubungi kami via WhatsApp di <strong>+62-895-0998-3660</strong> untuk informasi pengiriman lebih lanjut.</p>
                                </div>
                                <div style="text-align:center; margin-top: 20px;">
                                    <a href="https://wa.me/6289509983660?text=Halo,%20saya%20ingin%20konfirmasi%20pengiriman%20untuk%20kode%20{{ $struk->kode_unik }}"
                                        target="_blank" class="btn-whatsapp">
                                        <i class="fab fa-whatsapp me-2"></i> Hubungi via WhatsApp
                                    </a>
                                </div>
                            </div>

                        </div>

                    @endif

                </div>
            </div>
        </div>
    </section>

    {{-- Loading Overlay --}}
    <div id="loading-overlay" class="loading-overlay hidden">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <h3>Memproses Pembayaran...</h3>
            <p>Mohon tunggu sebentar</p>
        </div>
    </div>

    {{-- Midtrans --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton     = document.getElementById('pay-button');
            const buttonText    = document.getElementById('button-text');
            const loadingOverlay = document.getElementById('loading-overlay');

            if (payButton) {
                payButton.addEventListener('click', function() {
                    payButton.disabled = true;
                    buttonText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';
                    loadingOverlay.classList.remove('hidden');

                    snap.pay('{{ $struk->snap_token }}', {
                        onSuccess: function(result) {
                            showOverlay('success');
                            setTimeout(() => {
                                window.location.href = '{{ route('struk.detail', $struk->kode_struk) }}';
                            }, 2000);
                        },
                        onPending: function(result) {
                            showOverlay('pending');
                            setTimeout(() => {
                                window.location.href = '{{ route('struk.detail', $struk->kode_struk) }}';
                            }, 2000);
                        },
                        onError: function(result) {
                            loadingOverlay.classList.add('hidden');
                            payButton.disabled = false;
                            buttonText.innerHTML = '<i class="fas fa-credit-card me-2"></i> Bayar Sekarang';
                            showErrorToast('Pembayaran gagal. Silakan coba lagi.');
                        },
                        onClose: function() {
                            loadingOverlay.classList.add('hidden');
                            payButton.disabled = false;
                            buttonText.innerHTML = '<i class="fas fa-credit-card me-2"></i> Bayar Sekarang';
                        }
                    });
                });
            }

            function showOverlay(type) {
                const content = document.querySelector('.loading-content');
                if (type === 'success') {
                    content.innerHTML = `
                        <div style="text-align:center;">
                            <div style="width:80px;height:80px;background:#28a745;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                                <svg style="width:40px;height:40px;" fill="none" stroke="white" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h3 style="color:#28a745;">Pembayaran Berhasil!</h3>
                            <p>Mengalihkan ke halaman struk...</p>
                        </div>`;
                } else {
                    content.innerHTML = `
                        <div style="text-align:center;">
                            <div style="width:80px;height:80px;background:#ffc107;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                                <svg style="width:40px;height:40px;" fill="none" stroke="white" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 style="color:#e6a817;">Sedang Diproses</h3>
                            <p>Menunggu konfirmasi dari bank...</p>
                        </div>`;
                }
            }

            function showErrorToast(msg) {
                const toast = document.createElement('div');
                toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;background:#f8d7da;border:2px solid #dc3545;border-radius:12px;padding:16px 20px;max-width:360px;box-shadow:0 4px 20px rgba(0,0,0,.15);display:flex;align-items:center;gap:12px;';
                toast.innerHTML = `
                    <svg style="width:22px;height:22px;color:#dc3545;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div style="flex:1;">
                        <strong style="color:#721c24;font-size:14px;">Pembayaran Gagal</strong>
                        <p style="color:#721c24;margin:2px 0 0;font-size:13px;">${msg}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" style="background:none;border:none;color:#dc3545;font-size:20px;cursor:pointer;line-height:1;">×</button>`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 5000);
            }
        });
    </script>

    <style>
        /* ── Card wrapper ─────────────────────────────────────────── */
        .payment-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            overflow: hidden;
            margin-bottom: 40px;
        }

        /* ── Status banner ────────────────────────────────────────── */
        .payment-status-banner {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 28px 32px;
        }
        .banner-waiting  { background: linear-gradient(135deg, #fff8e1, #fff3cd); }
        .banner-pending  { background: linear-gradient(135deg, #e3f2fd, #bbdefb); }
        .banner-success  { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); }

        .status-text h4  { margin: 0 0 8px; font-size: 1.2rem; font-weight: 700; color: #1a1a2e; }

        /* ── Badges ───────────────────────────────────────────────── */
        .status-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 50px;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .03em;
        }
        .badge-warning { background: #ffc107; color: #5a3e00; }
        .badge-pending { background: #2196f3; color: #fff; }
        .badge-success { background: #43a047; color: #fff; }

        /* ── Kode bar ─────────────────────────────────────────────── */
        .payment-code-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f4f6fa;
            padding: 12px 32px;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }
        .code-label { font-size: .8rem; color: #888; text-transform: uppercase; letter-spacing: .06em; }
        .code-value { font-size: .95rem; font-weight: 700; color: #1a1a2e; letter-spacing: .04em; }

        /* ── Sections ─────────────────────────────────────────────── */
        .payment-section  { padding: 24px 32px; }
        .payment-divider  { height: 1px; background: #f0f0f0; margin: 0 32px; }
        .section-label    { font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; color: #aaa; font-weight: 600; margin-bottom: 14px; }

        /* ── Info grid ────────────────────────────────────────────── */
        .info-grid   { display: flex; flex-direction: column; gap: 10px; }
        .info-row    { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
        .info-key    { font-size: .88rem; color: #888; flex-shrink: 0; }
        .info-val    { font-size: .88rem; color: #1a1a2e; font-weight: 500; text-align: right; }

        /* ── Total row ────────────────────────────────────────────── */
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 2px dashed #e0e0e0;
        }
        .total-row span:first-child { font-size: .95rem; font-weight: 600; color: #444; }
        .total-amount { font-size: 1.3rem; font-weight: 800; color: #1a1a2e; }

        /* ── Deadline box ─────────────────────────────────────────── */
        .deadline-box {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: 10px;
            padding: 14px 18px;
        }
        .deadline-box i      { color: #f9a825; font-size: 1.4rem; }
        .deadline-label      { font-size: .8rem; color: #888; margin: 0; }
        .deadline-time       { font-size: 1rem; font-weight: 700; color: #e65100; margin: 2px 0 0; }

        /* ── Actions ──────────────────────────────────────────────── */
        .payment-actions { display: flex; flex-direction: column; gap: 14px; }

        .btn-pay-primary {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
            letter-spacing: .02em;
        }
        .btn-pay-primary:hover    { opacity: .92; }
        .btn-pay-primary:active   { transform: scale(.98); }
        .btn-pay-primary:disabled { opacity: .65; cursor: not-allowed; }

        .fallback-check {
            text-align: center;
            font-size: .82rem;
            color: #999;
        }
        .btn-check-status {
            background: none;
            border: none;
            color: #1a73e8;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
            text-decoration: underline;
        }

        /* ── Notice boxes ─────────────────────────────────────────── */
        .info-notice {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            border-radius: 10px;
            padding: 14px 16px;
        }
        .info-notice i  { font-size: 1.1rem; flex-shrink: 0; margin-top: 2px; }
        .info-notice p  { margin: 0; font-size: .87rem; line-height: 1.6; }
        .notice-pending { background: #e3f2fd; border: 1px solid #90caf9; color: #0d47a1; }
        .notice-pending i { color: #1565c0; }
        .notice-success { background: #e8f5e9; border: 1px solid #a5d6a7; color: #1b5e20; }
        .notice-success i { color: #2e7d32; }

        /* ── Receipt code ─────────────────────────────────────────── */
        .receipt-code-box {
            text-align: center;
            padding: 20px;
            background: #f4f6fa;
            border-radius: 12px;
            border: 2px dashed #c5cae9;
        }
        .receipt-code {
            display: block;
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: .15em;
            color: #1a1a2e;
            margin-bottom: 6px;
        }
        .receipt-code-box small { color: #888; font-size: .8rem; }

        /* ── WhatsApp button ──────────────────────────────────────── */
        .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            padding: 12px 28px;
            background: #25d366;
            color: #fff;
            border-radius: 10px;
            font-weight: 700;
            font-size: .95rem;
            text-decoration: none;
            transition: background .2s;
        }
        .btn-whatsapp:hover { background: #1ebe5d; color: #fff; }

        /* ── Loading overlay ──────────────────────────────────────── */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.75);
            backdrop-filter: blur(4px);
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .loading-overlay.hidden { display: none; }
        .loading-content {
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            max-width: 380px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0,0,0,.2);
        }
        .loading-content h3 { color: #333; font-weight: 700; margin: 18px 0 8px; font-size: 1.1rem; }
        .loading-content p  { color: #888; margin: 0; font-size: .88rem; }
        .spinner-border { width: 3rem; height: 3rem; }

        /* ── Responsive ───────────────────────────────────────────── */
        @media (max-width: 576px) {
            .payment-status-banner { flex-direction: column; text-align: center; padding: 24px 20px; }
            .payment-section       { padding: 20px; }
            .payment-code-bar      { padding: 12px 20px; }
            .payment-divider       { margin: 0 20px; }
        }
    </style>

@endsection
