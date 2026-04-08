<style>
    /* Keadaan Normal (Sidebar Terbuka) */
    .logo-mini {
        display: none !important;
        opacity: 0;
    }

    .logo-full {
        display: block;
        height: 30px;
        opacity: 1;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    /* Keadaan Sidebar Tertutup (Collapsed) */
    .layout-menu-collapsed .logo-full {
        display: none !important;
        opacity: 0;
    }

    .layout-menu-collapsed .logo-mini {
        display: block !important;
        margin: 0 auto;
        height: 30px;
        opacity: 1;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    /* Hover Effect - Hover di SIDEBAR, yang berubah LOGO aja */
    .layout-menu-collapsed #layout-menu:hover .logo-mini {
        display: none !important;
        opacity: 0;
    }

    .layout-menu-collapsed #layout-menu:hover .logo-full {
        display: block !important;
        opacity: 1;
        transform: scale(1);
    }

    /* Kasih z-index biar logo keluar di atas konten */
    .layout-menu-collapsed #layout-menu:hover .app-brand {
        z-index: 999;
    }
</style>
<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="{{ route('backend.home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <img src="{{ asset('icon/iconLblack.png') }}" alt="logo" class="logo-img app-brand-logo logo-full"
                        data-app-light-img="icons/iconLblack.png" data-app-dark-img="icons/iconL.png"
                        style="width: 150px; object-fit: cover; left: 0;">
                    <img src="{{ asset('assets/img/icons/logomini.png') }}" alt="logo"
                        class="logo-img app-brand-logo logo-mini" style="object-fit: cover"; width="43px">
                </span>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.47365 11.7183C8.11707 12.0749 8.11707 12.6531 8.47365 13.0097L12.071 16.607C12.4615 16.9975 12.4615 17.6305 12.071 18.021C11.6805 18.4115 11.0475 18.4115 10.657 18.021L5.83009 13.1941C5.37164 12.7356 5.37164 11.9924 5.83009 11.5339L10.657 6.707C11.0475 6.31653 11.6805 6.31653 12.071 6.707C12.4615 7.09747 12.4615 7.73053 12.071 8.121L8.47365 11.7183Z"
                    fill-opacity="0.9" />
                <path
                    d="M14.3584 11.8336C14.0654 12.1266 14.0654 12.6014 14.3584 12.8944L18.071 16.607C18.4615 16.9975 18.4615 17.6305 18.071 18.021C17.6805 18.4115 17.0475 18.4115 16.657 18.021L11.6819 13.0459C11.3053 12.6693 11.3053 12.0587 11.6819 11.6821L16.657 6.707C17.0475 6.31653 17.6805 6.31653 18.071 6.707C18.4615 7.09747 18.4615 7.73053 18.071 8.121L14.3584 11.8336Z"
                    fill-opacity="0.4" />
            </svg>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->routeIs('backend.home') ? 'active' : '' }} ">
            <a href="{{ route('backend.home') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-home-smile-line"></i>
                <div data-i18n="Dashboards">Dashboards</div>
                <div class="badge badge-center text-bg-danger rounded-pill ms-auto">5</div>
            </a>
        </li>

        <!-- Apps & Pages -->
        <li class="menu-header small mt-5">
            <span class="menu-header-text" data-i18n="Aset & Barang">Aset &amp; Barang</span>
        </li>
        <li class="menu-item {{ request()->routeIs('backend.kategori.index') ? 'active' : '' }}">
            <a href="{{ route('backend.kategori.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-function-line"></i>
                <div data-i18n="Kategori Barang">Kategori Barang</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('backend.submissions.index', 'backend.submissions.show') ? 'active' : '' }}">
            <a href="{{ route('backend.submissions.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-box-3-line"></i>
                <div data-i18n="Pengajuan Barang">Pengajuan Barang</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('backend.barang.index') ? 'active' : '' }}">
            <a href="{{ route('backend.barang.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-archive-line"></i>
                <div data-i18n="Barang">Barang</div>
            </a>
        </li>
        <!-- Components -->
        <li class="menu-header small mt-5">
            <span class="menu-header-text" data-i18n="Lelang">Lelang</span>
        </li>
        <!-- Icons -->
        <li class="menu-item {{ request()->routeIs('backend.lelang.index') ? 'active' : '' }}">
            <a href="{{ route('backend.lelang.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-auction-line"></i>
                <div data-i18n="Lelang">Lelang</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('backend.bid.index') ? 'active' : '' }} ">
            <a href="{{ route('backend.bid.index') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-history-line"></i>
                <div data-i18n="Log Penawaran">Log Penawaran</div>
            </a>
        </li>

        <!-- Forms & Tables -->
        <li class="menu-header small mt-5">
            <span class="menu-header-text" data-i18n="Status Transaksi">Status Transaksi</span>
        </li>
        <li class="menu-item {{ request()->routeIs('backend.struk.belum-bayar') ? 'active' : '' }}">
            <a href="{{ route('backend.struk.belum-bayar') }}" class="menu-link">
                <i class="menu-icon icon-base ri ri-money-dollar-circle-line"></i>
                <div data-i18n="Menunggu Bayar">Menunggu Bayar</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ri ri-user-unfollow-line"></i>
                <div data-i18n="Gagal Bayar">Gagal Bayar</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="layouts-collapsed-menu.html" class="menu-link">
                        <div data-i18n="Riwayat Gagal">Riwayat Gagal</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="layouts-content-navbar.html" class="menu-link">
                        <div data-i18n="Penyelesaian">Penyelesaian</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-header small mt-5">
            <span class="menu-header-text" data-i18n="Pengiriman">Pengiriman</span>
        </li>
        <li class="menu-item">
            <a href="icons-ri.html" class="menu-link">
                <i class="menu-icon icon-base ri ri-inbox-archive-line"></i>
                <div data-i18n="Siap Kirim">Siap Kirim</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="icons-ri.html" class="menu-link">
                <i class="menu-icon icon-base ri ri-truck-line"></i>
                <div data-i18n="Dalam Perjalanan">Dalam Perjalanan</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="icons-ri.html" class="menu-link">
                <i class="menu-icon icon-base ri ri-checkbox-circle-line"></i>
                <div data-i18n="Pengiriman Selesai">Pengiriman Selesai</div>
            </a>
        </li>
        <li class="menu-header small mt-5">
            <span class="menu-header-text" data-i18n="User & Akun">User & Akun</span>
        </li>
        <li class="menu-item">
            <a href="icons-ri.html" class="menu-link">
                <i class="menu-icon icon-base ri ri-group-line"></i>
                <div data-i18n="Daftar Pengguna">Daftar Pengguna</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="icons-ri.html" class="menu-link">
                <i class="menu-icon icon-base ri ri-shield-user-line"></i>
                <div data-i18n="Verifikasi Identitas">Verifikasi Identitas</div>
            </a>
        </li>
    </ul>
</aside>
