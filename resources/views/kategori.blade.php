@extends('layouts.kerangkafrontend')
@section('content')
<div class="hero-section style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li>
                    <a href="#0">Kategori</a>
                </li>
                <li>
                    <span>{{ $kategori->nama }}</span>
                </li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="{{ asset('sbidu/assets/images/banner/hero-bg.png') }}"></div>
    </div>
    <!--============= Hero Section Ends Here =============-->


    <!--============= Featured Auction Section Starts Here =============-->
    <section class="featured-auction-section padding-bottom mt--240 mt-lg--440 pos-rel">
        <div class="container">
            <div class="section-header cl-white mw-100 left-style">
                <h3 class="title">{{$kategori->nama}}</h3>
            </div>
            <div class="row justify-content-center mb-30-none">
                @foreach($kategori->barang as $barang)
                    @php $lelangs = $barang->lelang->where('status', 'dibuka'); @endphp
                    @foreach($lelangs as $lelang)
                    @php 
                    $bidtertinggi = $lelang->bid->max('bid');
                    $totalbid = $lelang->bid->count();
                    @endphp
                    <div class="col-sm-10 col-md-6 col-lg-4">
                        <div class="auction-item-2" data-aos="zoom-out-up" data-aos-duration="1000">
                            <div class="auction-thumb">
                                <a href="{{ route('lelang.show',$lelang->kode_lelang) }}"><img src="{{ Storage::url($barang->foto) }}" alt="car"></a>
                                <a href="#0" class="rating"><i class="far fa-star"></i></a>
                                <a href="#0" class="bid"><i class="flaticon-auction"></i></a>
                            </div>
                            <div class="auction-content">
                                <h6 class="title">
                                    <a href="#0">{{ $barang->nama }}</a>
                                </h6>
                                <div class="bid-area">
                                    <div class="bid-amount">
                                        <div class="icon">
                                            <i class="flaticon-auction"></i>
                                        </div>
                                        <div class="amount-content">
                                            <div class="current">Tawaran Tertinggi</div>
                                            <div class="amount">{{$bidtertinggi}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="countdown-area">
                                    <div class="countdown">
                                        <div id="bid_counter26"></div>
                                    </div>
                                    <span class="total-bids">{{ $totalbid }} Bids</span>
                                </div>
                                <div class="text-center">
                                    <a href="#0" class="custom-button">Submit a bid</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>
@endsection