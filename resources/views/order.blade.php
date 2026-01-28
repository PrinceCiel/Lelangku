@extends('layouts.kerangkafrontend')
@section('content')
<div class="hero-section style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li>
                    <a href="#0">My Account</a>
                </li>
                <li>
                    <span>My Bids</span>
                </li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="{{ asset('sbidu/assets/images/banner/hero-bg.png') }}"></div>
    </div>
    <!--============= Hero Section Ends Here =============-->


    <!--============= Dashboard Section Starts Here =============-->
    <section class="dashboard-section padding-bottom mt--240 mt-lg--440 pos-rel">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="dash-bid-item dashboard-widget mb-4">
                        <div class="header">
                            <h4 class="title">Lelang yang Dimenangkan</h4>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active">
                            <div class="row mb-30-none justify-content-center">
                                @foreach($lelang as $item)
                                @php 
                                $bid = $item->pemenang->bid;
                                $total = $item->bid->count();
                                @endphp
                                <div class="col-sm-10 col-md-6">
                                    <div class="auction-item-2" data-aos="zoom-out-up" data-aos-duration="1000">
                                        <div class="auction-thumb">
                                            <a href="{{ route('struk.detail', $item->struk->kode_struk)}}"><img src="{{ Storage::url($item->barang->foto)}}" alt="car"></a>
                                            <a href="#0" class="rating"><i class="far fa-star"></i></a>
                                            <a href="#0" class="bid"><i class="flaticon-auction"></i></a>
                                        </div>
                                        <div class="auction-content">
                                            <h6 class="title">
                                                <a href="{{ route('struk.detail', $item->struk->kode_struk)}}">{{$item->barang->nama}}</a>
                                            </h6>
                                            <div class="bid-area">
                                                <div class="bid-amount">
                                                    <div class="icon">
                                                        <i class="flaticon-auction"></i>
                                                    </div>
                                                    <div class="amount-content">
                                                        <div class="current">BID Anda</div>
                                                        <div class="amount">Rp{{ number_format($bid, 0, ',', '.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="countdown-area">
                                                <div class="countdown">
                                                    <div id="bid_counter_{{ $item->id }}"></div>
                                                </div>
                                                <span class="total-bids">{{$total}} Bids</span>
                                            </div>
                                            <div class="text-center">
                                                <a href="{{ route('struk.detail', $item->struk->kode_struk)}}" class="custom-button">Lihat Struk</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
    @foreach($lelang as $item)
        const endTime{{ $item->id }} = new Date("{{ \Carbon\Carbon::parse($item->jadwal_berakhir)->format('Y-m-d\TH:i:s') }}").getTime();
        const countdown{{ $item->id }} = setInterval(() => {
            const now = new Date().getTime();
            const distance = endTime{{ $item->id }} - now;
            const target = document.getElementById("bid_counter_{{ $item->id }}");

            if (!target) return;

            if (distance < 0) {
                clearInterval(countdown{{ $item->id }});
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
</script>

@endsection