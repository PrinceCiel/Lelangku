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
                                <img src="{{ Str::startsWith($userdata->foto, 'http') ? $userdata->foto : Storage::url($userdata->foto) }}" alt="user">
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
                                    <div class="info-value">{{ $userdata->datadiri->tanggal_lahir ?? 'Not set' }}</div>
                                </li>
                                <li>
                                    <div class="info-name">Address</div>
                                    <div class="info-value">{{ $userdata->datadiri->alamat ?? 'Not set' }}</div>
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
                                    <div class="info-value d-flex align-items-center justify-content-between">
                                        <span>{{ $userdata->email }}</span>
                                        @if(is_null($userdata->email_verified_at))
                                            <span class="status-badge unverified">
                                                <i class="flaticon-close"></i> Unverified
                                            </span>
                                        @else
                                            <span class="status-badge verified">
                                                <i class="flaticon-check"></i> Verified
                                            </span>
                                        @endif
                                    </div>
                                </li>
                                @if(is_null($userdata->email_verified_at))
                                <li class="verification-warning">
                                    <div class="warning-content">
                                        <div class="warning-icon">
                                            <i class="flaticon-alarm"></i>
                                        </div>
                                        <div class="warning-text">
                                            <h6>Email Verification Required</h6>
                                            <p>Please verify your email address to unlock all features and ensure account security.</p>
                                        </div>
                                        <div class="warning-action">
                                            <a href="{{ route('verification.notice') }}" class="verify-btn">
                                                <i class="flaticon-right-arrow"></i> Verify Email
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                @endif
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
                                    <div class="info-value">{{ $userdata->datadiri->no_telp ?? 'Not set' }}</div>
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
<style>
    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.verified {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .status-badge.unverified {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ff9800;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    /* Verification Warning */
    .verification-warning {
        padding: 0 !important;
        border: none !important;
    }

    .warning-content {
        display: flex;
        align-items: center;
        gap: 20px;
        background: linear-gradient(135deg, #fff5e6 0%, #fff9f0 100%);
        border-left: 4px solid #ff9800;
        border-radius: 8px;
        padding: 20px;
        margin-top: 15px;
    }

    .warning-icon {
        flex-shrink: 0;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ff9800;
        border-radius: 50%;
        color: #fff;
        font-size: 20px;
    }

    .warning-text {
        flex: 1;
    }

    .warning-text h6 {
        margin: 0 0 5px 0;
        font-size: 15px;
        font-weight: 600;
        color: #333;
    }

    .warning-text p {
        margin: 0;
        font-size: 13px;
        color: #666;
        line-height: 1.5;
    }

    .warning-action {
        flex-shrink: 0;
    }

    .verify-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background-color: #ff9800;
        color: #fff;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
    }

    .verify-btn:hover {
        background-color: #f57c00;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 152, 0, 0.3);
        color: #fff;
    }

    .verify-btn i {
        font-size: 12px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .warning-content {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .info-value {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 10px;
        }
    }
</style>
<!--============= Dashboard Section Ends Here =============-->
@endsection
