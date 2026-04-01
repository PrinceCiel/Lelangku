<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>LelangKu | Website Pelelangan Online</title>
    <link rel="stylesheet" href="{{ asset('sbidu/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sbidu/assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sbidu/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('sbidu/assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('sbidu/assets/css/owl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sbidu/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('sbidu/assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('sbidu/assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sbidu/assets/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('sbidu/assets/css/main.css') }}">
    @stack('style')

    <link rel="shortcut icon" href="{{ asset('sbidu/assets/images/favicon.png') }}" type="image/x-icon">
</head>

<body>
    <!--============= ScrollToTop Section Starts Here =============-->
    <div class="overlayer" id="overlayer">
        <div class="loader">
            <div class="loader-inner"></div>
        </div>
    </div>
    <a href="#0" class="scrollToTop"><i class="fas fa-angle-up"></i></a>
    <div class="overlay"></div>
    <!--============= ScrollToTop Section Ends Here =============-->


    <!--============= Header Section Starts Here =============-->
    @include('layouts.frontend.header')
    <!--============= Header Section Ends Here =============-->
    @yield('content')

    <!--============= Footer Section Starts Here =============-->
    <footer class="bg_img padding-top oh" data-background="{{ asset('sbidu/assets/images/footer/footer-bg.png') }}" style="padding-top: 450px;">
        <div class="footer-top-shape">
            <img src="{{ asset('sbidu/assets/css/img/footer-top-shape.png') }}" alt="css">
        </div>
        <div class="anime-wrapper">
            <div class="anime-1 plus-anime">
                <img src="{{ asset('sbidu/assets/images/footer/p1.png') }}" alt="footer">
            </div>
            <div class="anime-2 plus-anime">
                <img src="{{ asset('sbidu/assets/images/footer/p2.png') }}" alt="footer">
            </div>
            <div class="anime-3 plus-anime">
                <img src="{{ asset('sbidu/assets/images/footer/p3.png') }}" alt="footer">
            </div>
            <div class="anime-5 zigzag">
                <img src="{{ asset('sbidu/assets/images/footer/c2.png') }}" alt="footer">
            </div>
            <div class="anime-6 zigzag">
                <img src="{{ asset('sbidu/assets/images/footer/c3.png') }}" alt="footer">
            </div>
            <div class="anime-7 zigzag">
                <img src="{{ asset('sbidu/assets/images/footer/c4.png') }}" alt="footer">
            </div>
        </div
        <div class="footer-top padding-bottom padding-top">
            <div class="container">
                <div class="row mb--60">
                    <div class="col-sm-6 col-lg-6" data-aos="fade-down" data-aos-duration="1000">
                        <div class="footer-widget widget-links">
                            <h5 class="title">Kategori Lelang</h5>
                            <ul class="links-list">
                                @foreach($kategoris as $data)
                                <li>
                                    <a href="{{ route('kategori.show', $data->slug) }}">{{ $data->nama }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6" data-aos="fade-down" data-aos-duration="1800">
                        <div class="footer-widget widget-follow">
                            <h5 class="title">Follow Us</h5>
                            <ul class="links-list">
                                <li>
                                    <a href="#0"><i class="fas fa-phone-alt"></i>+62 895-0998-3660</a>
                                </li>
                                <li>
                                    <a href="#0"><i class="fas fa-blender-phone"></i>+62 895-0998-3660</a>
                                </li>
                                <li>
                                    <a href="#0"><i class="fas fa-envelope-open-text"></i>LelangKu@sch.id</a>
                                </li>
                                <li>
                                    <a href="#0"><i class="fas fa-location-arrow"></i>1201 Broadway Suite</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="copyright-area">
                    <div class="footer-bottom-wrapper">
                        <div class="logo">
                            <a href="{{ url('/') }}"><img src="{{ asset('icon/iconL.png') }}" alt="logo" class="logo-img" style="padding: 10px 0;width:150px"></a>
                        </div>
                        <div class="copyright"><p>&copy; Copyright 2024 | <a href="#0">Sbidu</a> By <a href="#0">Uiaxis</a></p></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--============= Footer Section Ends Here =============-->



    <script src="{{ asset('sbidu/assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/aos.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/waypoints.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/nice-select.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/counterup.min.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/owl.min.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/yscountdown.min.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('sbidu/assets/js/main.js') }}"></script>
    @include('sweetalert::alert')
    <style>
        .news-slide-item {
            padding: 0 10px;
        }

        .news-content {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            max-height: 250px;
        }

        .news-content:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
        }

        .news-content img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
            border-radius: 12px;
        }

        .news-slider .owl-nav {
            display: none;
        }

        .news-slider .owl-dots {
            text-align: center;
            margin-top: 15px;
        }

        .news-slider .owl-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            margin: 0 4px;
            transition: all 0.3s ease;
        }

        .news-slider .owl-dot span {
            display: none !important;
        }

        .news-slider .owl-dot.active {
            background: #ffc107;
            width: 24px;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .news-content,
            .news-content img {
                max-height: 180px;
                height: 180px;
            }
        }

        @media (max-width: 480px) {
            .news-content,
            .news-content img {
                max-height: 150px;
                height: 150px;
            }
        }
    </style>

    <script>
        $(document).ready(function(){
            $('.news-slider').owlCarousel({
                loop: true,
                margin: 20,
                nav: false,
                dots: true,
                dotsData: false,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 1
                    },
                    1024: {
                        items: 1
                    }
                }
            });
        });
    </script>
    @stack('script')
</body>

</html>
