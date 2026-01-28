@extends('layouts.kerangkafrontend')
@section('content')
    <!--============= Banner Section Starts Here =============-->
    <section class="banner-section bg_img" data-background="{{ asset('sbidu/assets/images/banner/banner-bg-1.png') }}">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-6 col-xl-12">
                    <div class="banner-content cl-white">
                        <h1 class="title" data-aos="zoom-out-up" data-aos-duration="1200"><span class="d-xl-block">Selamat Datang</span> di LelangKu!</h1>
                        <p class="pras" data-aos="zoom-out-down" data-aos-duration="1300">
                            LelangKu adalah platform lelang digital yang menyajikan berbagai kategori aset mulai dari kendaraan, properti, hingga produk elektronik pilihan. Kami mengelola seluruh proses secara transparan dan akuntabel. Setiap unit yang kami tawarkan telah melewati tahap verifikasi ketat demi menjamin keamanan dan kepuasan Anda dalam bertransaksi.
                        </p>
                        <a href="#0" class="custom-button yellow btn-large" data-aos="zoom-out-up" data-aos-duration="1500">Get Started</a>
                    </div>
                </div>
                <div class="d-none d-lg-block col-lg-6" data-aos="fade-right" data-aos-duration="1200">
                    <div class="banner-thumb-2">
                        <!-- <img src="{{ asset('sbidu/assets/images/banner/banner-1.png') }}" alt="banner"> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="container" style="padding-top: 30px; margin-bottom: 60px;">
            <div class="news-slider-wrapper">
                <div class="owl-carousel owl-theme news-slider">
                    <!-- Slide 1 -->
                    <div class="news-slide-item">
                        <div class="news-content">
                            <img src="{{ asset('sbidu/assets/images/news/auction-banner-1.jpg') }}" alt="New Year Auction">
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="news-slide-item">
                        <div class="news-content">
                            <img src="{{ asset('sbidu/assets/images/news/auction-banner-1.jpg') }}" alt="Special Event">
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="news-slide-item">
                        <div class="news-content">
                            <img src="{{ asset('sbidu/assets/images/news/auction-banner-1.jpg') }}" alt="Hot Deals">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Banner Section Ends Here =============-->


    <div class="browse-section ash-bg" style="background-color: #0f302d;">
        <!--============= Hightlight Slider Section Starts Here =============-->
        <div class="browse-slider-section mt--140">
            <div class="container">
                <div class="section-header-2 cl-white mb-4">
                    <div class="left" data-aos="flip-up" data-aos-duration="1500">
                        <h6 class="title pl-0 w-100">Browse the highlights</h6>
                    </div>
                    <div class="slider-nav">
                        <a href="#0" class="bro-prev"><i class="flaticon-left-arrow"></i></a>
                        <a href="#0" class="bro-next active"><i class="flaticon-right-arrow"></i></a>
                    </div>
                </div>
                <div class="m--15">
                    <div class="browse-slider owl-theme owl-carousel">
                        @foreach($kategori as $item)
                        <a href="{{ route('kategori.show', $item->slug) }}" class="browse-item">
                            <img src="{{ Storage::url($item->foto) }}" alt="auction">
                            <span class="info">{{ $item->nama }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!--============= Hightlight Slider Section Ends Here =============-->
    </div>

    <!--============= Watches Auction Section Starts Here =============-->
    @foreach($kategori as $item)
    @php
        $barangLelangAktif = $item->barang->filter(function($barang) {
            return $barang->lelang()->where('status', 'dibuka')->count() > 0;
        });
    @endphp
    @if($barangLelangAktif->count() > 0)
        <section class="watches-auction-section padding-bottom padding-top">
            <div class="container">
                <div class="section-header-3" data-aos="zoom-out-down" data-aos-duration="1200">
                    <div class="left">
                        <div class="thumb">
                            <img src="{{ asset('sbidu/assets/images/header-icons/coin-1.png') }}" alt="header-icons">
                        </div>
                        <div class="title-area">
                            <h2 class="title">{{$item->nama}}</h2>
                            <p></p>
                        </div>
                    </div>
                    <a href="{{ route('kategori.show', $item->slug) }}" class="normal-button">View All</a>
                </div>
                <div class="row justify-content-center mb-30-none">
                    @foreach($item->barang as $barang)
                        @php 
                            $lelangs = $barang->lelang()->where('status', 'dibuka')->get(); 
                            $total = $lelangs->count();    
                        @endphp
                        @foreach($lelangs as $lelang)
                            @php 
                                $bidtertinggi = $lelang->bid->max('bid');
                                if($bidtertinggi == 0)
                                    $bidtertinggi = $lelang->barang->harga;
                            @endphp
                        <div class="col-sm-10 col-md-6 col-lg-4">
                            <div class="auction-item-2" data-aos="zoom-out-up" data-aos-duration="1600">
                                <div class="auction-thumb">
                                    <a href="{{ route('lelang.show', $lelang->kode_lelang) }}"><img src="{{ Storage::url($barang->foto) }}" alt="watches"></a>
                                    <a href="#0" class="rating"><i class="far fa-star"></i></a>
                                    <a href="#0" class="bid"><i class="flaticon-auction"></i></a>
                                </div>
                                <div class="auction-content">
                                    <h6 class="title">
                                        <a href="{{ route('lelang.show', $lelang->kode_lelang) }}">{{$barang->nama}}</a>
                                    </h6>
                                    <div class="bid-area">
                                        <div class="bid-amount">
                                            <div class="icon">
                                                <i class="flaticon-auction"></i>
                                            </div>
                                            <div class="amount-content">
                                                <div class="current">Current Bid</div>
                                                <div class="amount">Rp{{ number_format($bidtertinggi, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="countdown-area">
                                        <div class="countdown">
                                            <div id="bid_counter_{{ $lelang->id }}"></div>
                                        </div>
                                        <span class="total-bids">{{$total}} Bid</span>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('lelang.show', $lelang->kode_lelang) }}" class="custom-button">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
        <script>
            @foreach($kategori as $item)
                @foreach($item->barang as $barang)
                    @php $lelangs = $barang->lelang()->where('status', 'dibuka')->get(); @endphp
                    @foreach($lelangs as $lelang)
                        const endTime{{ $lelang->id }} = new Date("{{ \Carbon\Carbon::parse($lelang->jadwal_berakhir)->format('Y-m-d\TH:i:s') }}").getTime();
                        const countdown{{ $lelang->id }} = setInterval(() => {
                            const now = new Date().getTime();
                            const distance = endTime{{ $lelang->id }} - now;
                            const target = document.getElementById("bid_counter_{{ $lelang->id }}");

                            if (!target) return;

                            if (distance < 0) {
                                clearInterval(countdown{{ $lelang->id }});
                                target.innerHTML = "<span style='color:red;'>Selesai</span>";
                            } else {
                                const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                                const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                const s = Math.floor((distance % (1000 * 60)) / 1000);
                                target.innerHTML = `${d}d ${h}h ${m}m ${s}s`;
                            }
                        }, 1000);
                    @endforeach
                @endforeach
            @endforeach
        </script>
    @endif
    @endforeach
@endsection