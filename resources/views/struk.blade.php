@extends('layouts.kerangkafrontend')
@section('content')
<div class="hero-section style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="./index.html">Home</a>
                </li>
                <li>
                    <a href="#0">My Account</a>
                </li>
                <li>
                    <span>Personal profile</span>
                </li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="{{ asset('sbidu/assets/images/banner/hero-bg.png') }}"></div>
    </div>
    <section class="dashboard-section padding-bottom mt--240 mt-lg--440 pos-rel">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-12">
                            @if($struk->status == 'belum dibayar')
                            <div class="dash-pro-item mb-30 dashboard-widget">
                                <div class="user">
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('gif/belum.gif') }}" alt="user" style="width: 180px; height: auto;">
                                    </div>
                                    <div class="content">
                                        <h5 class="title"><a href="#0">Menunggu Pembayaran</a></h5>
                                        <span class="username">{{$struk->kode_struk}}</span>
                                    </div>
                                </div>
                                <div class="header">
                                    <h4 class="title">Detail Pembayaran</h4>
                                </div>
                                <ul class="dash-pro-body">
                                    <li>
                                        <div class="info-name">Nama Lengkap</div>
                                        <div class="info-value">{{$pemenang->user->nama_lengkap}}</div>
                                    </li>
                                    <li>
                                        <div class="info-name">Nama Lelang</div>
                                        <div class="info-value">{{$struk->lelang->kode_lelang}}-{{$struk->lelang->barang->nama}}</div>
                                    </li>
                                    @php $bidakhir = $pemenang->bid; @endphp
                                    <li>
                                        <div class="info-name">Bid Akhir</div>
                                        <div class="info-value">Rp{{ number_format($bidakhir, 0, ',', '.') }}</div>
                                    </li>
                                    @php
                                    $adminfee = $bidakhir * 0.05;
                                    $total = $adminfee + $bidakhir;
                                    @endphp
                                    <li>
                                        <div class="info-name">Biaya Admin</div>
                                        <div class="info-value">Rp{{ number_format($adminfee, 0, ',', '.') }}</div>
                                    </li>
                                    <li>
                                        <div class="info-name" style="font-size: 25px ; font-weight: bold;">Total</div>
                                        <div class="info-value" style="font-size: 25px ; font-weight: bold;">Rp{{ number_format($total, 0, ',', '.') }}</div>
                                    </li>
                                    <hr>
                                    <li>
                                        <div class="info-name" style="font-size: 20px;" align=center>Transfer Ke</div>
                                    </li>
                                    <li>
                                        <ul class="list-unstyled info-value">
                                            <li>
                                                <div class="info-name"><strong>Bank</strong></div>
                                                <div class="info-value"><strong>BCA</strong></div>
                                            </li>
                                            <li>
                                                <div class="info-name"><strong>No. Rekening</strong></div>
                                                <div class="info-value"><strong>1234567890</strong></div>
                                            </li>
                                            <li>
                                                <div class="info-name"><strong>Atas Nama</strong></div>
                                                <div class="info-value"><strong>PT Lelang Makmur</strong></div>
                                            </li>
                                        </ul>
                                    </li>
                                    <hr>
                                    <li>
                                        <p>Lakukan Pembayaran Sebelum {{ $struk->tgl_trx->addHour()->format('H:i d-m-Y') }}</p>
                                    </li>
                                    <li style="text-align: center; margin-top: 20px;">
                                        <button id="pay-button" class="btn btn-success">
                                            <span id="button-text">Bayar Pakai Midtrans</span>
                                        </button>
                                    </li>
                                    <li style="text-align: center; margin-top: 20px;">
                                        <form method="POST" action="{{ route('check.status', $struk->kode_struk) }}">
                                            @csrf
                                            <button class="btn btn-warning">Cek Status Pembayaran</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            @elseif($struk->status == 'pending')
                            <div class="dash-pro-item mb-30 dashboard-widget">
                                <div class="user">
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('gif/pending.gif') }}" alt="user" style="width: 180px; height: auto;">
                                    </div>
                                    <div class="content">
                                        <h5 class="title"><a href="#0">Menunggu Konfirmasi Pembayaran</a></h5>
                                        <span class="username">{{$struk->kode_struk}}</span>
                                    </div>
                                </div>
                                <div class="header">
                                    <h4 class="title">Detail Pembayaran</h4>
                                </div>
                                <ul class="dash-pro-body">
                                    <li>
                                        <div class="info-name">Nama Lengkap</div>
                                        <div class="info-value">{{$pemenang->user->nama_lengkap}}</div>
                                    </li>
                                    <li>
                                        <div class="info-name">Nama Lelang</div>
                                        <div class="info-value">{{$struk->lelang->kode_lelang}}-{{$struk->lelang->barang->nama}}</div>
                                    </li>
                                    @php $bidakhir = $pemenang->bid;@endphp
                                    <li>
                                        <div class="info-name">Bid Akhir</div>
                                        <div class="info-value">Rp{{ number_format($bidakhir, 0, ',', '.') }}</div>
                                    </li>
                                    @php
                                    $adminfee = $bidakhir * 0.05;
                                    $total = $adminfee + $bidakhir;
                                    @endphp
                                    <li>
                                        <div class="info-name">Biaya Admin</div>
                                        <div class="info-value">Rp{{ number_format($adminfee, 0, ',', '.') }}</div>
                                    </li>
                                    <li>
                                        <div class="info-name" style="font-size: 25px ; font-weight: bold;">Total</div>
                                        <div class="info-value" style="font-size: 25px ; font-weight: bold;">Rp{{ number_format($total, 0, ',', '.') }}</div>
                                    </li>
                                    <hr>
                                    <li>
                                        <p>Pembayaran sedang di verifikasi oleh admin.</p>
                                    </li>
                                </ul>
                            </div>
                            @elseif($struk->status == 'berhasil')
                            <div class="dash-pro-item mb-30 dashboard-widget">
                                <div class="user">
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('gif/success.gif') }}" alt="user" style="width: 180px; height: auto;">
                                    </div>
                                    <div class="content">
                                        <h5 class="title"><a href="#0">Pembayaran Telah Diterima</a></h5>
                                        <span class="username">{{$struk->kode_struk}}</span>
                                    </div>
                                </div>
                                <div class="header">
                                    <h4 class="title">Detail Pembayaran</h4>
                                </div>
                                <ul class="dash-pro-body">
                                    <li>
                                        <div class="info-name">Nama Lengkap</div>
                                        <div class="info-value">{{$pemenang->user->nama_lengkap}}</div>
                                    </li>
                                    <li>
                                        <div class="info-name">Nama Lelang</div>
                                        <div class="info-value">{{$struk->lelang->kode_lelang}}-{{$struk->lelang->barang->nama}}</div>
                                    </li>
                                    @php $bidakhir = $pemenang->bid; @endphp
                                    <li>
                                        <div class="info-name">Bid Akhir</div>
                                        <div class="info-value">Rp{{ number_format($bidakhir, 0, ',', '.') }}</div>
                                    </li>
                                    @php
                                    $adminfee = $bidakhir * 0.05;
                                    $total = $adminfee + $bidakhir;
                                    @endphp
                                    <li>
                                        <div class="info-name">Biaya Admin</div>
                                        <div class="info-value">Rp{{ number_format($adminfee, 0, ',', '.') }}</div>
                                    </li>
                                    <li>
                                        <div class="info-name" style="font-size: 25px ; font-weight: bold;">Total</div>
                                        <div class="info-value" style="font-size: 25px ; font-weight: bold;">Rp{{ number_format($total, 0, ',', '.') }}</div>
                                    </li>
                                    <hr>
                                    <li>
                                        <div class="info-name" style="font-size: 25px ; font-weight: bold;">Kode Unik</div>
                                        <div class="info-value" style="font-size: 25px ; font-weight: bold;">{{ $struk->kode_unik}}</div>
                                    </li>
                                    <hr>
                                    <li>
                                        <p>Hubungi +62-895-0998-3660 via WhatsApp untuk Pengiriman lebih lanjut.</p>
                                    </li>
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
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

    {{-- Midtrans Snap Script --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" 
            data-client-key="Mid-client-9VUnrPJpEEUKQZXG"></script>
    
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        const buttonText = document.getElementById('button-text');
        const loadingOverlay = document.getElementById('loading-overlay');
        
        if (payButton) {
            payButton.addEventListener('click', function () {
                // Disable button dan ubah text
                payButton.disabled = true;
                buttonText.textContent = 'Memproses...';
                loadingOverlay.classList.remove('hidden');
                
                // Panggil Midtrans Snap dengan callbacks
                snap.pay('{{$struk->snap_token}}', {
                    onSuccess: function(result) {
                        console.log('success', result);
                        showSuccessAnimation();
                        setTimeout(() => {
                            redirectPost('{{ route("check.status", $struk->kode_struk) }}', {});
                        }, 2000);
                    },
                    onPending: function(result) {
                        console.log('pending', result);
                        showPendingAnimation();
                        setTimeout(() => {
                            redirectPost('{{ route("check.status", $struk->kode_struk) }}', {});
                        }, 2000);
                    },
                    onError: function(result) {
                        console.log('error', result);
                        loadingOverlay.classList.add('hidden');
                        payButton.disabled = false;
                        buttonText.textContent = 'Bayar Pakai Midtrans';
                        showErrorAlert();
                    },
                    onClose: function() {
                        console.log('customer closed the popup without finishing the payment');
                        loadingOverlay.classList.add('hidden');
                        payButton.disabled = false;
                        buttonText.textContent = 'Bayar Pakai Midtrans';
                    }
                });
            });
        }
        
        function showSuccessAnimation() {
            const loadingContent = document.querySelector('.loading-content');
            loadingContent.innerHTML = `
                <div style="text-align: center;">
                    <div style="width: 80px; height: 80px; background: #28a745; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; animation: bounce 0.5s;">
                        <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 style="color: #28a745; font-size: 24px; font-weight: bold; margin-bottom: 10px;">Pembayaran Berhasil!</h3>
                    <p style="color: #666; font-size: 14px;">Mengalihkan ke halaman status...</p>
                </div>
            `;
        }
        
        function showPendingAnimation() {
            const loadingContent = document.querySelector('.loading-content');
            loadingContent.innerHTML = `
                <div style="text-align: center;">
                    <div style="width: 80px; height: 80px; background: #ffc107; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; animation: pulse 1s infinite;">
                        <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 style="color: #ffc107; font-size: 24px; font-weight: bold; margin-bottom: 10px;">Pembayaran Tertunda</h3>
                    <p style="color: #666; font-size: 14px;">Menunggu konfirmasi...</p>
                </div>
            `;
        }
        
        function showErrorAlert() {
            // Buat alert element
            const alert = document.createElement('div');
            alert.className = 'custom-alert';
            alert.innerHTML = `
                <div style="background: #f8d7da; border: 2px solid #dc3545; border-radius: 10px; padding: 20px; margin: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px; animation: slideInRight 0.3s;">
                    <div style="display: flex; align-items: start; gap: 15px;">
                        <svg style="width: 24px; height: 24px; color: #dc3545; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div style="flex: 1;">
                            <h4 style="color: #721c24; font-weight: bold; margin: 0 0 5px 0; font-size: 16px;">Pembayaran Gagal</h4>
                            <p style="color: #721c24; margin: 0; font-size: 14px;">Silakan coba lagi.</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 20px; line-height: 1;">×</button>
                    </div>
                </div>
            `;
            document.body.appendChild(alert);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.remove();
                }
            }, 5000);
        }
    });
    function redirectPost(url, data) {
        // Buat element form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        // Tambahkan CSRF Token (Wajib di Laravel)
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        // Tambahkan data tambahan jika perlu (misal kode_struk)
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = data[key];
                form.appendChild(input);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }
    </script>

    <style>
    /* Loading Overlay Styles */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
        z-index: 9998;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .loading-overlay.hidden {
        display: none;
    }

    .loading-content {
        background: white;
        border-radius: 15px;
        padding: 40px;
        text-align: center;
        max-width: 400px;
        width: 100%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .loading-content h3 {
        color: #333;
        font-weight: bold;
        margin: 20px 0 10px 0;
        font-size: 20px;
    }

    .loading-content p {
        color: #666;
        margin: 0;
        font-size: 14px;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Animations */
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    </style>
@endsection