<header class="floating-header-modern">
    <div class="floating-container">
        <div class="container">
            <div class="floating-nav-wrapper">
                <div class="brand-logo-modern">
                    <a href="{{ url('/')}}">
                        <img src="{{ asset('icon/iconL.png') }}" alt="logo" class="brand-img-modern">
                    </a>
                </div>

                <ul class="nav-menu-modern">
                    <li class="nav-item-modern">
                        <a href="#0" class="nav-link-modern">
                            <span>Kategori</span>
                        </a>
                        <ul class="dropdown-modern">
                            @foreach($kategoris as $data)
                            <li class="dropdown-item-modern">
                                <a href="{{ route('kategori.show', $data->slug) }}" class="dropdown-link-modern">{{$data->nama}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item-modern">
                        <a href="#0" class="nav-link-modern user-nav-link">
                            @if(Auth::check())
                                <div class="user-avatar-modern">
                                    <img src="{{ Storage::url(Auth::user()->foto ?? 'images/default-avatar.png') }}" alt="Avatar">
                                </div>
                                <span>{{Auth::user()->nama_lengkap}}</span>
                            @else
                                <i class="flaticon-user"></i>
                                <span>Akun</span>
                            @endif
                        </a>
                        <ul class="dropdown-modern">
                            @if(Auth::check())
                            <li class="dropdown-item-modern">
                                <a href="#0" class="dropdown-link-modern">Profile</a>
                                <ul class="dropdown-modern dropdown-nested">
                                    <li class="dropdown-item-modern">
                                        <a href="{{ route('dashboard.user')}}" class="dropdown-link-modern">Dashboard</a>
                                    </li>
                                    <li class="dropdown-item-modern">
                                        <a href="{{ route('struk.index')}}" class="dropdown-link-modern">Lelang yang Dimenangkan</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown-item-modern">
                                <a class="dropdown-link-modern" href="{{ route('logout')}}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form action="{{ route('logout') }}" method="post" id="logout-form">
                                    @csrf
                                </form>
                            </li>
                            @else
                            <li class="dropdown-item-modern">
                                <a href="{{ route('register') }}" class="dropdown-link-modern">Sign Up</a>
                            </li>
                            <li class="dropdown-item-modern">
                                <a href="{{ route('login') }}" class="dropdown-link-modern">Sign In</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                </ul>

                <form class="searchbox-modern" method="get" action="{{ route('search')}}">
                    <input type="text" placeholder="Cari lelang..." name="search" id="search" class="searchbox-input-modern">
                    <button type="submit" class="searchbox-btn-modern"><i class="fas fa-search"></i></button>
                </form>

                <div class="mobile-search-toggle d-md-none">
                    <a href="#0"><i class="fas fa-search"></i></a>
                </div>
                <div class="mobile-menu-toggle d-lg-none">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>
</header>
<style>
/* Floating Header Container */
.floating-header-modern {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    padding: 12px 15px;
    display: block;
}

.floating-container {
    background: rgba(15, 48, 46, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    max-width: 1400px;
    margin: 0 auto;
}

.floating-container:hover {
    box-shadow: 0 12px 48px rgba(0, 0, 0, 0.3);
    transform: translateY(-2px);
}

.floating-nav-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 25px;
    gap: 25px;
}

/* Brand Logo */
.brand-logo-modern a {
    display: inline-block;
}

.brand-img-modern {
    height: 40px;
    width: auto;
    transition: transform 0.3s ease;
}

.brand-logo-modern a:hover .brand-img-modern {
    transform: scale(1.05);
}

/* Navigation Menu */
.nav-menu-modern {
    display: flex;
    gap: 10px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.nav-item-modern {
    position: relative;
}

.nav-link-modern {
    color: #fff;
    padding: 8px 18px;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
    display: flex;
    align-items: center; /* Ini buat mastiin teks & icon sejajar vertikal */
    justify-content: center;
    gap: 10px; /* Samain gapnya sama user-nav-link */
    text-decoration: none;
    font-size: 15px;
    height: 40px; /* Set tinggi fix biar sejajar sama yang ada avatarnya */
}

.nav-link-modern i {
    font-size: 16px;
    display: flex;
    align-items: center;
}

.nav-link-modern:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
    color: #fff;
}

/* User Avatar */
.user-nav-link {
    gap: 10px !important;
}

.user-avatar-modern {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.user-avatar-modern img {
    width: 100%;
    height: 100%;
    transform: translateY(0);
}

.nav-link-modern:hover .user-avatar-modern {
    border-color: rgba(255, 255, 255, 0.6);
    transform: scale(1.05);
}

/* Dropdown Menu */
.dropdown-modern {
    position: absolute;
    top: 100%;
    left: 0;
    background: rgba(15, 48, 46, 0.98);
    border-radius: 12px;
    padding: 8px;
    margin-top: 8px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    list-style: none;
    z-index: 100;
}

.nav-item-modern:hover > .dropdown-modern {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item-modern {
    position: relative;
}

.dropdown-link-modern {
    color: #fff;
    padding: 10px 15px;
    border-radius: 8px;
    display: block;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 14px;
}

.dropdown-link-modern:hover {
    background: rgba(255, 255, 255, 0.15);
    padding-left: 20px;
    color: #fff;
}

/* Nested Dropdown */
.dropdown-nested {
    left: 100%;
    top: 0;
    margin-top: 0;
    margin-left: 10px;
}

.dropdown-item-modern:hover > .dropdown-nested {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

/* Modern Search Box */
.searchbox-modern {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 4px 4px 4px 18px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    max-width: 280px;
}

.searchbox-modern:focus-within {
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}

.searchbox-input-modern {
    background: transparent;
    border: none;
    color: #fff;
    padding: 6px 10px;
    flex: 1;
    outline: none;
    font-size: 14px;
}

.searchbox-input-modern::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.searchbox-btn-modern {
    background: linear-gradient(135deg, #1a5f5a, #0f302e);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.searchbox-btn-modern:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    flex-direction: column;
    gap: 4px;
    cursor: pointer;
    padding: 5px;
}

.mobile-menu-toggle span {
    width: 25px;
    height: 3px;
    background: #fff;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.mobile-menu-toggle:hover span {
    background: rgba(255, 255, 255, 0.8);
}

.mobile-search-toggle {
    display: none;
}

.mobile-search-toggle a {
    color: #fff;
    font-size: 18px;
    padding: 10px;
}

/* FIX BODY PADDING - INI YANG PENTING BRO! */
/* body:has(.floating-header-modern) {
    padding-top: 90px !important;
} */

/* Responsive Design */
@media (max-width: 991px) {
    .floating-header-modern {
        padding: 8px 10px;
    }

    .floating-nav-wrapper {
        padding: 10px 18px;
    }

    .nav-menu-modern {
        display: none;
    }

    .searchbox-modern {
        display: none;
    }

    .mobile-menu-toggle {
        display: flex;
    }

    .mobile-search-toggle {
        display: block;
    }

    body:has(.floating-header-modern) {
        padding-top: 75px !important;
    }
}

@media (max-width: 768px) {
    .floating-container {
        border-radius: 12px;
    }

    .floating-nav-wrapper {
        padding: 8px 15px;
    }

    .brand-img-modern {
        height: 32px;
    }
}

@media (max-width: 480px) {
    .floating-header-modern {
        padding: 5px 8px;
    }

    .floating-container {
        border-radius: 10px;
    }

    body:has(.floating-header-modern) {
        padding-top: 65px !important;
    }
}
</style>
