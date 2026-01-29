
    <header>
            <div class="header-bottom">
                <div class="container">
                    <div class="header-wrapper">
                        <div class="logo">
                            <a href="{{ url('/')}}">
                                <img src="{{ asset('icon/iconL.png') }}" alt="logo" class="logo-img" style="padding: 10px 0;">
                            </a>
                        </div>
                        <ul class="menu ml-auto">
                            <li>
                                <a href="#0">Kategori</a>
                                <ul class="submenu">
                                    @foreach($kategoris as $data)
                                    <li>
                                        <a href="{{ route('kategori.show', $data->slug) }}">{{$data->nama}}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li>
                                <a href="#0"><i class="flaticon-user"></i> @if(Auth::check()) {{Auth::user()->nama_lengkap}} @endif</a>
                                <ul class="submenu">
                                    @if(Auth::check())
                                    <li>
                                        <a href="#0">Profile</a>
                                        <ul class="submenu">
                                            <li>
                                                <a href="{{ route('dashboard.user')}}">Dashboard</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('struk.index')}}">Lelang yang Dimenangkan</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" type="button" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                                            Logout
                                        </a>
                                        <form action="{{ route('logout') }}" method="post" id="logout-form">
                                            @csrf
                                        </form>
                                    </li>
                                    @else
                                    <li>
                                        <a href="{{ route('register') }}">Sign Up</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('login') }}">Sign In</a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                        <form class="search-form" method="get" action="{{ route('search')}}">
                            <input type="text" placeholder="Cari nama lelang, kode, atau kategori..." name="search" id="search">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                        <div class="search-bar d-md-none">
                            <a href="#0"><i class="fas fa-search"></i></a>
                        </div>
                        <div class="header-bar d-lg-none">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
    </header>
