@extends('layouts.kerangkafrontend')

@section('content')
<div class="hero-section style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('home.user')}}">Home</a>
                </li>
                <li>
                    <a href="#0">{{$lelang->barang->kategori->nama}}</a>
                </li>a
                <li>
                    <span>{{$lelang->barang->nama}}</span>
                </li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="{{ asset('sbidu/assets/images/banner/hero-bg.png') }}"></div>
    </div>
    <!--============= Hero Section Ends Here =============-->


    <!--============= Product Details Section Starts Here =============-->
    <section class="product-details padding-bottom mt--240 mt-lg--440">
        <div class="container">
            <div class="product-details-slider-top-wrapper">
                <div class="product-details-slider owl-theme owl-carousel" id="sync1">
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product" height="400px" style="object-fit: contain;">
                        </div>
                    </div>
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product" height="400px" style="object-fit: contain;">
                        </div>
                    </div>
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product" height="400px" style="object-fit: contain;">
                        </div>
                    </div>
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product" height="400px" style="object-fit: contain;">
                        </div>
                    </div>
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product" height="400px" style="object-fit: contain;">
                        </div>
                    </div>
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product" height="400px" style="object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-details-slider-wrapper">
                <div class="product-bottom-slider owl-theme owl-carousel" id="sync2">
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product">
                        </div>
                    </div>
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product">
                        </div>
                    </div>
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product">
                        </div>
                    </div>
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product">
                        </div>
                    </div>
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product">
                        </div>
                    </div>
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="{{ Storage::url($lelang->barang->foto) }}" alt="product">
                        </div>
                    </div>
                </div>
                <span class="det-prev det-nav">
                    <i class="fas fa-angle-left"></i>
                </span>
                <span class="det-next det-nav active">
                    <i class="fas fa-angle-right"></i>
                </span>
            </div>
            <div class="row mt-40-60-80">
                <div class="col-lg-8">
                    <div class="product-details-content">
                        <div class="product-details-header">
                            <h2 class="title">{{$lelang->barang->nama}}</h2>
                            <ul>
                                <li>Listing ID: 14076242</li>
                                <li>Item #: 7300-3356862</li>
                            </ul>
                        </div>
                        <ul class="price-table mb-30">
                            @if($lelang->status === 'selesai')
                            <li class="header">
                                <h5 class="current">Tawaran Pemenang</h5>
                                <h3 class="price">Rp{{ number_format($lelang->pemenang->bid, 0, ',', '.') }}</h3>
                            </li>
                            @else
                            <li class="header">
                                <h5 class="current">Tawaran Tertinggi Sementara</h5>
                                <h3 class="price" id="currentBid">Rp{{ number_format($bidtertinggi, 0, ',', '.') }}</h3>
                            </li>
                            <li>
                                <span class="details">Kenaikan Tawaran (IDR)</span>
                                <h5 class="info">10%</h5>
                            </li>
                            @endif
                        </ul>
                        @if($lelang->status === 'dibuka')
                            @php
                                $increment = $bidtertinggi * 0.10;
                                $min = $increment + $bidtertinggi;
                            @endphp
                            <div class="product-bid-area">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if($sudahDeposit)
                                    {{-- Sudah deposit, bisa bid --}}
                                    <div class="alert alert-success mb-3">
                                        ✅ Anda sudah melakukan deposit untuk lelang ini.
                                    </div>
                                    <form class="product-bid-form" action="{{ route('lelang.store') }}" method="post">
                                        @csrf
                                        <div class="search-icon">
                                            <img src="{{ asset('sbidu/assets/images/product/search-icon.png') }}" alt="product">
                                        </div>
                                        <input type="hidden" name="kode_lelang" value="{{$lelang->kode_lelang}}">
                                        <input type="integer" placeholder="Masukkan Tawaran anda" name="bid" min={{$min}} id="bidInput">
                                        <button type="submit" class="custom-button">Ajukan Tawaran</button>
                                    </form>

                                @else
                                    {{-- Belum deposit --}}
                                    <div class="alert alert-warning mb-3">
                                        ⚠️ Anda harus melakukan deposit terlebih dahulu untuk bisa mengajukan tawaran.
                                    </div>
                                    <button class="custom-button" data-toggle="modal" data-target="#modalDeposit">
                                        Bayar Deposit
                                    </button>
                                @endif
                            </div>

                            {{-- Modal Deposit --}}
                            @if(!$sudahDeposit)
                            <div class="modal fade" id="modalDeposit" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Deposit</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td>Lelang</td>
                                                    <td><strong>{{ $lelang->kode_lelang }} - {{ $lelang->barang->nama }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Harga Awal</td>
                                                    <td>Rp{{ number_format($lelang->harga_awal, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Deposit (10%)</td>
                                                    <td><strong>Rp{{ number_format($nominalDeposit, 0, ',', '.') }}</strong></td>
                                                </tr>
                                            </table>
                                            <p class="text-muted" style="font-size: 13px;">
                                                * Deposit akan dikembalikan jika Anda tidak memenangkan lelang ini.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <form action="{{ route('deposit.create') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="kode_lelang" value="{{ $lelang->kode_lelang }}">
                                                <button type="submit" class="btn btn-success" id="btn-deposit">
                                                    Bayar Sekarang via Midtrans
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="product-sidebar-area">
                        @if($lelang->status == 'selesai')
                        <div class="product-single-sidebar mb-3">
                            <h4 class="title">Lelang Berakhir</h4>
                            <h6 class="title">Pemenang : {{$lelang->pemenang->user->nama_lengkap}}</h6>
                        </div>
                        @else
                        <div class="product-single-sidebar mb-3">
                            <h6 class="title">Lelang ini berakhir pada :</h6>
                            <div id="countdown" class="countdown-timer"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="product-tab-menu-area mb-40-60 mt-70-100">
            <div class="container">
                <ul class="product-tab-menu nav nav-tabs">
                    <li>
                        <a href="#details" class="active" data-toggle="tab">
                            <div class="thumb">
                                <img src="{{ asset('sbidu/assets/images/product/tab1.png') }}" alt="product">
                            </div>
                            <div class="content">Deskripsi Barang</div>
                        </a>
                    </li>
                    <li>
                        <a href="#history" data-toggle="tab">
                            <div class="thumb">
                                <img src="{{ asset('sbidu/assets/images/product/tab3.png') }}" alt="product">
                            </div>
                            <div class="content">Riwayat Tawaran ({{$countBid}})</div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="details">
                    <div class="tab-details-content">
                        <div class="header-area">
                            <h3 class="title">{{$lelang->barang->nama}}</h3>
                            <div class="item">
                                <table class="product-info-table">
                                    <tbody>
                                        <tr>
                                            <th>Kondisi</th>
                                            <td>{{ $lelang->barang->kondisi }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Barang</th>
                                            <td>{{ $lelang->barang->jenis_barang }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="item">
                                <h5 class="subtitle">Deskripsi Barang</h5>
                                <p>{{$lelang->barang->deskripsi}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="history">
                    <div class="history-wrapper">
                        <div class="item">
                            <h5 class="title">Histori Bid</h5>
                            <div class="history-table-area">
                                <table class="history-table">
                                    <thead>
                                        <tr>
                                            <th>Penawar</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Penawaran</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bidHistoryBody">
                                        @foreach($bid as $item)
                                            <tr>
                                                <td>
                                                    <div class="user-info">
                                                        <div class="thumb">
                                                            <img src="{{ Storage::url($item->users->foto) }}">
                                                        </div>
                                                        <div class="content">
                                                            {{ $item->users->nama_lengkap }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                                <td>{{ $item->created_at->format('H:i') }}</td>
                                                <td>Rp{{ number_format($item->bid, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        const endTime = new Date("{{ \Carbon\Carbon::parse($lelang->jadwal_berakhir)->format('Y-m-d\TH:i:s') }}").getTime();

        const countdown = setInterval(() => {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(countdown);
                document.getElementById("countdown").innerHTML = "<span style='color:red;'>Lelang selesai</span>";
            } else {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("countdown").innerHTML =
                    `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }
        }, 1000);
    </script>
    <script>
        const bidInput = document.getElementById('bidInput');
        bidInput.addEventListener('input', function (e) {
            // Remove semua selain angka
            let value = this.value.replace(/[^,\d]/g, '').toString();

            // Format angka jadi Rupiah
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

            this.value = 'Rp' + rupiah;
        });

        // Convert ke angka sebelum submit
        document.querySelector('form.product-bid-form').addEventListener('submit', function (e) {
            const cleaned = bidInput.value.replace(/[^\d]/g, '');
            bidInput.value = cleaned;
        });
        setInterval(() => {
            fetch('/lelang/{{ $lelang->kode_lelang }}/poll')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('currentBid').innerText =
                        'Rp' + Number(data.bidtertinggi).toLocaleString('id-ID');

                    document.getElementById('countBid').innerText =
                        data.countBid;

                    if (data.status === 'selesai') {
                        alert('Lelang selesai');
                    }
                });
        }, 5000);
        function loadBidHistory() {
            fetch('/lelang/{{ $lelang->kode_lelang }}/history')
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('bidHistoryBody');
                    tbody.innerHTML = '';

                    data.forEach(item => {
                        tbody.innerHTML += `
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="thumb">
                                            <img src="${item.foto}">
                                        </div>
                                        <div class="content">
                                            ${item.nama}
                                        </div>
                                    </div>
                                </td>
                                <td>${item.tanggal}</td>
                                <td>${item.jam}</td>
                                <td>Rp${item.bid}</td>
                            </tr>
                        `;
                    });
                });
        }

        setInterval(loadBidHistory, 5000);
    </script>

@endsection
@section('scripts')

@endsection
