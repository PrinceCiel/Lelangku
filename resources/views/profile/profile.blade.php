@extends('layouts.kerangkafrontend')
@section('content')
<!--============= Hero Section Starts Here =============-->
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
<!--============= Hero Section Ends Here =============-->


<!--============= Dashboard Section Starts Here =============-->
<section class="dashboard-section padding-bottom mt--240 mt-lg--440 pos-rel">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-7 col-lg-4">
                <div class="dashboard-widget mb-30 mb-lg-0 sticky-menu">
                    <div class="user">
                        <div class="thumb-area">
                            <div class="thumb">
                                <img src="{{ Storage::url($userdata->foto) }}" alt="user">
                            </div>
                            <label for="profile-pic" class="profile-pic-edit"><i class="flaticon-pencil"></i></label>
                            <input type="file" id="profile-pic" class="d-none">
                        </div>
                        <div class="content">
                            <h5 class="title"><a href="#0">{{ $userdata->nama_lengkap }}</a></h5>
                            <span class="username">{{ $userdata->email }}</span>
                        </div>
                    </div>
                    <ul class="dashboard-menu">
                        <li>
                            <a href="{{ route('dashboard.user') }}"><i class="flaticon-dashboard"></i>Dashboard</a>
                        </li>
                        <li>
                            <a href="#0" class="active"><i class="flaticon-settings"></i>Personal Profile </a>
                        </li>
                        <li>
                            <a href="my-bid.html"><i class="flaticon-auction"></i>My Bids</a>
                        </li>
                        <li>
                            <a href="winning-bids.html"><i class="flaticon-best-seller"></i>Winning Bids</a>
                        </li>
                        <li>
                            <a href="notifications.html"><i class="flaticon-alarm"></i>My Alerts</a>
                        </li>
                        <li>
                            <a href="my-favorites.html"><i class="flaticon-star"></i>My Favorites</a>
                        </li>
                        <li>
                            <a href="referral.html"><i class="flaticon-shake-hand"></i>Referrals</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-12">
                        <div class="dash-pro-item mb-30 dashboard-widget">
                            <div class="header">
                                <h4 class="title">Personal Details</h4>
                                {{-- <span class="edit"><i class="flaticon-edit"></i> Edit</span> --}}
                            </div>
                            <ul class="dash-pro-body">
                                <li>
                                    <div class="info-name">Name</div>
                                    <div class="info-value">{{ $userdata->nama_lengkap }}</div>
                                </li>
                                <li>
                                    <div class="info-name">Date of Birth</div>
                                    <div class="info-value">{{ $userdata->datadiri->tanggal_lahir }}</div>
                                </li>
                                <li>
                                    <div class="info-name">Address</div>
                                    <div class="info-value">{{ $userdata->datadiri->alamat }}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {{-- <div class="col-12">
                        <div class="dash-pro-item mb-30 dashboard-widget">
                            <div class="header">
                                <h4 class="title">Account Settings</h4>
                                <span class="edit"><i class="flaticon-edit"></i> Edit</span>
                            </div>
                            <ul class="dash-pro-body">
                                <li>
                                    <div class="info-name">Language</div>
                                    <div class="info-value">English (United States)</div>
                                </li>
                                <li>
                                    <div class="info-name">Time Zone</div>
                                    <div class="info-value">(GMT-06:00) Central America</div>
                                </li>
                                <li>
                                    <div class="info-name">Status</div>
                                    <div class="info-value"><i class="flaticon-check text-success"></i> Active</div>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                    <div class="col-12">
                        <div class="dash-pro-item mb-30 dashboard-widget">
                            <div class="header">
                                <h4 class="title">Email Address</h4>
                                {{-- <span class="edit"><i class="flaticon-edit"></i> Edit</span> --}}
                            </div>
                            <ul class="dash-pro-body">
                                <li>
                                    <div class="info-name">Email</div>
                                    <div class="info-value">{{ $userdata->email }}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="dash-pro-item mb-30 dashboard-widget">
                            <div class="header">
                                <h4 class="title">Phone</h4>
                                <span class="edit"><i class="flaticon-edit"></i> Edit</span>
                            </div>
                            <ul class="dash-pro-body">
                                <li>
                                    <div class="info-name">Mobile</div>
                                    <div class="info-value">{{ $userdata->datadiri->no_telp }}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="dash-pro-item dashboard-widget">
                            <div class="header">
                                <h4 class="title">Security</h4>
                                <span class="edit"><i class="flaticon-edit"></i> Edit</span>
                            </div>
                            <ul class="dash-pro-body">
                                <li>
                                    <div class="info-name">Password</div>
                                    <div class="info-value">••••••••</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Dashboard Section Ends Here =============-->
@endsection
