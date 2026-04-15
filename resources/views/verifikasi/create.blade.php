<!doctype html>

<html lang="en" class="layout-wide customizer-hide" data-assets-path="{{ asset('assets/') }}"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>LelangKu | Website Pelelangan Online</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ route('home.user') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <span class="text-primary">
                                        <img src="{{ asset('icon/iconLblack.png') }}" alt="logo" class="logo-img"
                                            style="padding: 10px 0;max-width: 150px;">
                                    </span>
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1">Verifikasi Data Diri</h4>
                        <p class="mb-6">verifikasi untuk mendapat akses lebih luas.</p>
                        @if (Auth::user()->status == 'Belum Verifikasi')
                            <form id="formAuthentication" class="mb-6" method="POST"
                                action="{{ route('verifikasi.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-6">
                                    <label for="email" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="email" name="nama"
                                        placeholder="Masukkan Nama Lengkap" value="{{ Auth::user()->nama_lengkap }}"
                                        readonly />
                                </div>
                                <div class="mb-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="Enter your email" value="{{ Auth::user()->email }}" readonly />
                                </div>
                                <div class="mb-6">
                                    <label for="email" class="form-label">No Telp Aktif</label>
                                    <input type="text" class="form-control" id="email" name="no_telp"
                                        placeholder="Masukkan No telp aktif" required />
                                </div>
                                <div class="mb-6 form-password-toggle">
                                    <label class="form-label" for="password">Tanggal Lahir</label>
                                    <div class="input-group input-group-merge">
                                        <input type="date" id="password" class="form-control" name="tgl_lahir"
                                            placeholder="" aria-describedby="password" required />
                                    </div>
                                </div>
                                <div class="mb-6">
                                    <label for="formFile" class="form-label">Foto KTP</label>
                                    <input class="form-control" type="file" id="formFile" name="foto" />
                                </div>
                                <div class="mb-6">
                                    <label class="form-label" for="basic-icon-default-message">Alamat</label>
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-message2" class="input-group-text"><i
                                                class="icon-base bx bx-comment"></i></span>
                                        <textarea id="basic-icon-default-message" class="form-control" placeholder="Alamat"
                                            aria-label="Hi, Do you have a moment to talk Joe?" aria-describedby="basic-icon-default-message2" name="alamat"></textarea>
                                    </div>
                                </div>
                                <div class="mb-6">
                                    <button class="btn btn-primary d-grid w-100" type="submit">Verifikasi</button>
                                </div>
                            </form>
                        @elseif(Auth::user()->status == 'diajukan')
                            <div class="text-center">
                                <div class="mb-4">
                                    <div class="avatar avatar-xl d-inline-block">
                                        <span class="avatar-initial rounded-circle bg-label-warning">
                                            <i class="bx bx-time-five bx-lg"></i>
                                        </span>
                                    </div>
                                </div>

                                <h4 class="mb-2">Verifikasi Sedang Diproses! ⏳</h4>
                                <p class="mb-6">
                                    Terima kasih telah melakukan verifikasi. Data Anda sedang dalam antrean peninjauan
                                    oleh tim admin LelangKu.
                                </p>

                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <span class="alert-icon text-warning me-2">
                                        <i class="bx bx-info-circle"></i>
                                    </span>
                                    Proses ini biasanya memakan waktu maksimal 1x24 jam.
                                </div>

                                <div class="mt-6">
                                    <p class="text-muted">Mau cek status lagi nanti?</p>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger w-100">
                                            <i class="bx bx-log-out me-1"></i> Keluar / Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- / Content -->
    <!-- Core JS -->

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->

    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js') }}"></script>
</body>

</html>
