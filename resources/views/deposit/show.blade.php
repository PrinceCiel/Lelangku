@extends('layouts.kerangkafrontend')

@section('content')
<div class="hero-section style-2">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home.user') }}">Home</a></li>
            <li><a href="#0">Deposit</a></li>
            <li><span>{{ $deposit->kode_deposit }}</span></li>
        </ul>
    </div>
    <div class="bg_img hero-bg bottom_center" data-background="{{ asset('sbidu/assets/images/banner/hero-bg.png') }}"></div>
</div>

<section class="dashboard-section padding-bottom mt--240 mt-lg--440 pos-rel">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @if($deposit->status == 'belum dibayar')
                <div class="dash-pro-item mb-30 dashboard-widget">
                    <div class="user">
                        <div class="text-center mb-3">
                            <img src="{{ asset('gif/belum.gif') }}" alt="status" style="width: 180px; height: auto;">
                        </div>
                        <div class="content">
                            <h5 class="title"><a href="#0">Menunggu Pembayaran Deposit</a></h5>
                            <span class="username">{{ $deposit->kode_deposit }}</span>
                        </div>
                    </div>
                    <div class="header">
                        <h4 class="title">Detail Deposit</h4>
                    </div>
                    <ul class="dash-pro-body">
                        <li>
                            <div class="info-name">Nama Lengkap</div>
                            <div class="info-value">{{ $deposit->user->nama_lengkap }}</div>
                        </li>
                        <li>
                            <div class="info-name">Lelang</div>
                            <div class="info-value">{{ $deposit->lelang->kode_lelang }} - {{ $deposit->lelang->barang->nama }}</div>
                        </li>
                        <li>
                            <div class="info-name">Harga Awal</div>
                            <div class="info-value">Rp{{ number_format($deposit->lelang->barang->harga, 0, ',', '.') }}</div>
                        </li>
                        <li>
                            <div class="info-name" style="font-size: 20px; font-weight: bold;">Nominal Deposit (10%)</div>
                            <div class="info-value" style="font-size: 20px; font-weight: bold;">Rp{{ number_format($deposit->total, 0, ',', '.') }}</div>
                        </li>
                        <hr>
                        <li>
                            <p class="text-muted" style="font-size: 13px;">
                                * Deposit akan dikembalikan jika Anda tidak memenangkan lelang ini.
                            </p>
                        </li>
                        <li style="text-align: center; margin-top: 20px;">
                            <button id="pay-button" class="btn btn-success">
                                <span id="button-text">Bayar Deposit via Midtrans</span>
                            </button>
                        </li>
                    </ul>
                </div>

                @elseif($deposit->status == 'pending')
                <div class="dash-pro-item mb-30 dashboard-widget">
                    <div class="user">
                        <div class="text-center mb-3">
                            <img src="{{ asset('gif/pending.gif') }}" alt="status" style="width: 180px; height: auto;">
                        </div>
                        <div class="content">
                            <h5 class="title"><a href="#0">Menunggu Konfirmasi Deposit</a></h5>
                            <span class="username">{{ $deposit->kode_deposit }}</span>
                        </div>
                    </div>
                    <div class="header">
                        <h4 class="title">Detail Deposit</h4>
                    </div>
                    <ul class="dash-pro-body">
                        <li>
                            <div class="info-name">Nama Lengkap</div>
                            <div class="info-value">{{ $deposit->user->nama_lengkap }}</div>
                        </li>
                        <li>
                            <div class="info-name">Lelang</div>
                            <div class="info-value">{{ $deposit->lelang->kode_lelang }} - {{ $deposit->lelang->barang->nama }}</div>
                        </li>
                        <li>
                            <div class="info-name" style="font-size: 20px; font-weight: bold;">Nominal Deposit (10%)</div>
                            <div class="info-value" style="font-size: 20px; font-weight: bold;">Rp{{ number_format($deposit->total, 0, ',', '.') }}</div>
                        </li>
                        <hr>
                        <li>
                            <p>Pembayaran deposit sedang diverifikasi.</p>
                        </li>
                    </ul>
                </div>

                @elseif($deposit->status == 'berhasil')
                <div class="dash-pro-item mb-30 dashboard-widget">
                    <div class="user">
                        <div class="text-center mb-3">
                            <img src="{{ asset('gif/success.gif') }}" alt="status" style="width: 180px; height: auto;">
                        </div>
                        <div class="content">
                            <h5 class="title"><a href="#0">Deposit Berhasil</a></h5>
                            <span class="username">{{ $deposit->kode_deposit }}</span>
                        </div>
                    </div>
                    <div class="header">
                        <h4 class="title">Detail Deposit</h4>
                    </div>
                    <ul class="dash-pro-body">
                        <li>
                            <div class="info-name">Nama Lengkap</div>
                            <div class="info-value">{{ $deposit->user->nama_lengkap }}</div>
                        </li>
                        <li>
                            <div class="info-name">Lelang</div>
                            <div class="info-value">{{ $deposit->lelang->kode_lelang }} - {{ $deposit->lelang->barang->nama }}</div>
                        </li>
                        <li>
                            <div class="info-name" style="font-size: 20px; font-weight: bold;">Nominal Deposit</div>
                            <div class="info-value" style="font-size: 20px; font-weight: bold;">Rp{{ number_format($deposit->total, 0, ',', '.') }}</div>
                        </li>
                        <hr>
                        <li>
                            <div class="info-name">Dibayar Pada</div>
                            <div class="info-value">{{ $deposit->paid_at->format('H:i, d-m-Y') }}</div>
                        </li>
                        <hr>
                        <li style="text-align: center; margin-top: 10px;">
                            <a href="{{ route('lelang.show', $deposit->lelang->kode_lelang) }}" class="custom-button">
                                Kembali ke Lelang
                            </a>
                        </li>
                    </ul>
                </div>

                @elseif($deposit->status == 'gagal')
                <div class="dash-pro-item mb-30 dashboard-widget">
                    <div class="user">
                        <div class="text-center mb-3">
                            <img src="{{ asset('gif/gagal.gif') }}" alt="status" style="width: 180px; height: auto;">
                        </div>
                        <div class="content">
                            <h5 class="title"><a href="#0">Deposit Gagal</a></h5>
                            <span class="username">{{ $deposit->kode_deposit }}</span>
                        </div>
                    </div>
                    <div class="header">
                        <h4 class="title">Detail Deposit</h4>
                    </div>
                    <ul class="dash-pro-body">
                        <li>
                            <div class="info-name">Lelang</div>
                            <div class="info-value">{{ $deposit->lelang->kode_lelang }} - {{ $deposit->lelang->barang->nama }}</div>
                        </li>
                        <li>
                            <div class="info-name" style="font-size: 20px; font-weight: bold;">Nominal Deposit</div>
                            <div class="info-value" style="font-size: 20px; font-weight: bold;">Rp{{ number_format($deposit->total, 0, ',', '.') }}</div>
                        </li>
                        <hr>
                        <li style="text-align: center; margin-top: 10px;">
                            <a href="{{ route('lelang.show', $deposit->lelang->kode_lelang) }}" class="custom-button">
                                Coba Lagi
                            </a>
                        </li>
                    </ul>
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
        <h3 id="loading-title">Memproses Pembayaran...</h3>
        <p id="loading-text">Mohon tunggu sebentar</p>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const payButton = document.getElementById('pay-button');
    const loadingOverlay = document.getElementById('loading-overlay');

    if (payButton) {
        payButton.addEventListener('click', function () {
            payButton.disabled = true;
            document.getElementById('button-text').textContent = 'Memproses...';
            loadingOverlay.classList.remove('hidden');

            snap.pay('{{ $deposit->snap_token }}', {
                onSuccess: function (result) {
                    showOverlayStatus('success');
                    setTimeout(() => {
                        window.location.href = '{{ route('deposit.show', $deposit->kode_deposit) }}';
                    }, 2000);
                },
                onPending: function (result) {
                    showOverlayStatus('pending');
                    setTimeout(() => {
                        window.location.href = '{{ route('deposit.show', $deposit->kode_deposit) }}';
                    }, 2000);
                },
                onError: function (result) {
                    loadingOverlay.classList.add('hidden');
                    payButton.disabled = false;
                    document.getElementById('button-text').textContent = 'Bayar Deposit via Midtrans';
                    showErrorToast();
                },
                onClose: function () {
                    loadingOverlay.classList.add('hidden');
                    payButton.disabled = false;
                    document.getElementById('button-text').textContent = 'Bayar Deposit via Midtrans';
                }
            });
        });
    }

    function showOverlayStatus(type) {
        const content = document.querySelector('.loading-content');
        if (type === 'success') {
            content.innerHTML = `
                <div style="text-align:center;">
                    <div style="width:80px;height:80px;background:#28a745;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:20px;">
                        <svg style="width:40px;height:40px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 style="color:#28a745;">Deposit Berhasil!</h3>
                    <p style="color:#666;">Mengalihkan...</p>
                </div>`;
        } else {
            content.innerHTML = `
                <div style="text-align:center;">
                    <div style="width:80px;height:80px;background:#ffc107;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:20px;">
                        <svg style="width:40px;height:40px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 style="color:#ffc107;">Menunggu Konfirmasi</h3>
                    <p style="color:#666;">Mengalihkan...</p>
                </div>`;
        }
    }

    function showErrorToast() {
        const toast = document.createElement('div');
        toast.innerHTML = `
            <div style="background:#f8d7da;border:2px solid #dc3545;border-radius:10px;padding:20px;position:fixed;top:20px;right:20px;z-index:9999;max-width:400px;">
                <strong style="color:#721c24;">Pembayaran Gagal</strong>
                <p style="color:#721c24;margin:5px 0 0;">Silakan coba lagi.</p>
                <button onclick="this.parentElement.remove()" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:18px;cursor:pointer;">×</button>
            </div>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }
});
</script>

<style>
.loading-overlay { position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.8);backdrop-filter:blur(5px);z-index:9998;display:flex;align-items:center;justify-content:center; }
.loading-overlay.hidden { display:none; }
.loading-content { background:white;border-radius:15px;padding:40px;text-align:center;max-width:400px;width:100%; }
.loading-content h3 { color:#333;font-weight:bold;margin:20px 0 10px; }
.loading-content p { color:#666;margin:0;font-size:14px; }
.spinner-border { width:3rem;height:3rem; }
</style>

@endsection
