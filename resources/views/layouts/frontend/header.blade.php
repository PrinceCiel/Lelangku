<header class="lk-header" id="lk-header">
    <div class="lk-header__container">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="lk-logo">
            <img src="{{ asset('icon/iconL.png') }}" alt="LelangKu" class="lk-logo__img">
        </a>

        {{-- Desktop Nav --}}
        <nav class="lk-nav" aria-label="Navigasi utama">
            <ul class="lk-nav__list">

                {{-- Kategori trigger --}}
                <li class="lk-nav__item">
                    <button class="lk-nav__link" id="lk-kat-btn" aria-expanded="false" aria-controls="lk-sidebar">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                        Kategori
                        <svg class="lk-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                </li>

                {{-- Akun --}}
                <li class="lk-nav__item lk-nav__item--dd">
                    <button class="lk-nav__link">
                        @if(Auth::check())
                            @php $user = Auth::user(); @endphp
                            <div class="lk-av">
                                <img src="{{ Str::startsWith($user->foto,'http') ? $user->foto : asset('storage/'.$user->foto) }}" alt="{{ $user->nama_lengkap }}" loading="lazy">
                            </div>
                            <span class="lk-nav__label">{{ Str::limit($user->nama_lengkap,18) }}</span>
                        @else
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span class="lk-nav__label">Akun</span>
                        @endif
                        <svg class="lk-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="lk-dd lk-dd--right">
                        <div class="lk-dd__in">
                            @if(Auth::check())
                                <div class="lk-dd__head">
                                    <div class="lk-av lk-av--md">
                                        <img src="{{ Str::startsWith($user->foto,'http') ? $user->foto : asset('storage/'.$user->foto) }}" alt="{{ $user->nama_lengkap }}" loading="lazy">
                                    </div>
                                    <div>
                                        <p class="lk-dd__name">{{ $user->nama_lengkap }}</p>
                                        <p class="lk-dd__email">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="lk-dd__sep"></div>
                                <a href="{{ route('dashboard.user') }}" class="lk-dd__item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('struk.index') }}" class="lk-dd__item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Lelang Dimenangkan
                                </a>
                                <a href="{{ route('submissions.index') }}" class="lk-dd__item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    Tawarkan Barang
                                </a>
                                <div class="lk-dd__sep"></div>
                                <a href="#" class="lk-dd__item lk-dd__item--danger"
                                   onclick="event.preventDefault();document.getElementById('lk-logout-form').submit();">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    Logout
                                </a>
                                <form action="{{ route('logout') }}" method="post" id="lk-logout-form" hidden>@csrf</form>
                            @else
                                <a href="{{ route('register') }}" class="lk-dd__item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                                    Daftar
                                </a>
                                <a href="{{ route('login') }}" class="lk-dd__item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                                    Masuk
                                </a>
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

        {{-- Desktop Search --}}
        <form class="lk-dsearch" method="get" action="{{ route('search') }}" role="search">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" placeholder="Cari lelang, barang..." class="lk-dsearch__input" autocomplete="off">
        </form>

        {{-- Mobile Controls --}}
        <div class="lk-mob-ctrl">
            <button class="lk-ibtn" id="lk-m-search-open" aria-label="Cari">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
            <button class="lk-ibtn lk-burger" id="lk-menu-btn" aria-label="Menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>
</header>

<div class="lk-spacer"></div>

{{-- ============================================================
     KATEGORI SIDEBAR (desktop, slide dari kiri)
     ============================================================ --}}
<div class="lk-sidebar" id="lk-sidebar" aria-hidden="true">
    <div class="lk-sidebar__panel">
        <div class="lk-sidebar__head">
            <p class="lk-sidebar__title">Kategori</p>
            <button class="lk-sidebar__close" id="lk-sidebar-close" aria-label="Tutup">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <ul class="lk-sidebar__list">
            @foreach($kategoris as $data)
            <li>
                <a href="{{ route('kategori.show', $data->slug) }}" class="lk-sidebar__item">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    {{ $data->nama }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="lk-sidebar__bd" id="lk-sidebar-bd"></div>
</div>

{{-- ============================================================
     MOBILE SEARCH — FULLSCREEN (slide dari bawah ke atas)
     ============================================================ --}}
<div class="lk-msearch" id="lk-msearch" aria-hidden="true" role="dialog" aria-label="Pencarian">
    <div class="lk-msearch__inner">

        {{-- Top bar sticky: tombol back pojok kiri + input search --}}
        <div class="lk-msearch__topbar">
            <button class="lk-msearch__back" id="lk-msearch-close" aria-label="Kembali">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <form class="lk-msearch__form" method="get" action="{{ route('search') }}" role="search">
                <div class="lk-msearch__inputwrap">
                    <svg class="lk-msearch__ico" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input
                        type="text"
                        name="search"
                        id="lk-msi"
                        class="lk-msearch__input"
                        placeholder="Cari lelang, barang..."
                        autocomplete="off"
                        autocorrect="off"
                        spellcheck="false"
                    >
                    <button type="button" class="lk-msearch__clear" id="lk-msi-clear" aria-label="Hapus" hidden>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            </form>
        </div>

        {{-- Konten: kategori chips + trending --}}
        <div class="lk-msearch__body">
            <p class="lk-msearch__label">Jelajahi Kategori</p>
            <div class="lk-msearch__chips">
                @foreach($kategoris as $data)
                <a href="{{ route('kategori.show', $data->slug) }}" class="lk-chip">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    {{ $data->nama }}
                </a>
                @endforeach
            </div>

            <p class="lk-msearch__label lk-msearch__label--gap">Pencarian Populer</p>
            <div class="lk-msearch__trends">
                <button type="button" class="lk-trend" onclick="lkFill('Elektronik')">Elektronik</button>
                <button type="button" class="lk-trend" onclick="lkFill('Motor')">Motor</button>
                <button type="button" class="lk-trend" onclick="lkFill('Jam Tangan')">Jam Tangan</button>
                <button type="button" class="lk-trend" onclick="lkFill('Laptop')">Laptop</button>
                <button type="button" class="lk-trend" onclick="lkFill('Kamera')">Kamera</button>
                <button type="button" class="lk-trend" onclick="lkFill('Perhiasan')">Perhiasan</button>
            </div>
        </div>

    </div>
</div>

{{-- ============================================================
     MOBILE MENU DRAWER
     ============================================================ --}}
<div class="lk-mob-menu" id="lk-mob-menu" aria-hidden="true">
    <nav>
        @if(Auth::check())
            <div class="lk-mob-user">
                <div class="lk-av lk-av--lg">
                    <img src="{{ Str::startsWith($user->foto,'http') ? $user->foto : asset('storage/'.$user->foto) }}" alt="{{ $user->nama_lengkap }}" loading="lazy">
                </div>
                <div>
                    <p class="lk-mob-user__name">{{ $user->nama_lengkap }}</p>
                    <p class="lk-mob-user__sub">{{ $user->email }}</p>
                </div>
            </div>
            <div class="lk-mob-sep"></div>
            <a href="{{ route('dashboard.user') }}" class="lk-mob-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="{{ route('struk.index') }}" class="lk-mob-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                Lelang Dimenangkan
            </a>
            <a href="{{ route('submissions.index') }}" class="lk-mob-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tawarkan Barang
            </a>
            <div class="lk-mob-sep"></div>
            <a href="#" class="lk-mob-link lk-mob-link--danger"
               onclick="event.preventDefault();document.getElementById('lk-logout-form').submit();">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </a>
        @else
            <div class="lk-mob-guest">
                <p class="lk-mob-guest__title">Selamat datang!</p>
                <p class="lk-mob-guest__sub">Masuk untuk mulai lelang</p>
            </div>
            <div class="lk-mob-sep"></div>
            <a href="{{ route('register') }}" class="lk-mob-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Daftar Akun
            </a>
            <a href="{{ route('login') }}" class="lk-mob-link lk-mob-link--accent">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Masuk
            </a>
        @endif
    </nav>
</div>

<div class="lk-ov" id="lk-ov"></div>

<style>
/* ============================================================
   VARIABLES
   ============================================================ */
:root {
    --bg:     #0c2e2b;
    --bgh:    rgba(255,255,255,0.08);
    --br:     rgba(255,255,255,0.12);
    --c:      #fff;
    --cm:     rgba(255,255,255,0.52);
    --ac:     #3ecf9a;
    --dg:     #f87171;
    --ddbg:   #0d3532;
    --hh:     72px;
    --z:      1000;
    --ease:   cubic-bezier(0.4,0,0.2,1);
    --t:      0.2s cubic-bezier(0.4,0,0.2,1);
    --r:      12px;
    --rs:     8px;
}

*, *::before, *::after { box-sizing: border-box; }

/* ============================================================
   HEADER
   ============================================================ */
.lk-header {
    position: fixed; top: 0; left: 0; right: 0;
    z-index: var(--z);
    background: var(--bg);
    border-bottom: 1px solid var(--br);
    box-shadow: 0 2px 20px rgba(0,0,0,0.25);
    transition: box-shadow 0.3s var(--ease);
}
.lk-header.scrolled { box-shadow: 0 4px 36px rgba(0,0,0,0.42); }

.lk-spacer { height: var(--hh); display: block; }

.lk-header__container {
    display: flex; align-items: center; gap: 8px;
    height: var(--hh); padding: 0 28px;
    max-width: 1400px; margin: 0 auto;
}

/* ---- Logo ---- */
.lk-logo {
    display: flex; align-items: center; flex-shrink: 0;
    text-decoration: none; margin-right: 8px;
    transition: opacity var(--t);
}
.lk-logo:hover { opacity: 0.82; }
.lk-logo__img { height: 40px; width: auto; }

/* ---- Nav ---- */
.lk-nav { display: flex; align-items: center; }
.lk-nav__list { display: flex; gap: 4px; margin: 0; padding: 0; list-style: none; }
.lk-nav__item { position: relative; }

.lk-nav__link {
    display: inline-flex; align-items: center; gap: 7px;
    height: 40px; padding: 0 14px; border-radius: var(--rs);
    background: none; border: none; color: var(--c);
    font-size: 14.5px; font-weight: 500; cursor: pointer;
    text-decoration: none; white-space: nowrap;
    font-family: inherit; letter-spacing: 0.01em;
    transition: background var(--t);
}
.lk-nav__link:hover,
.lk-nav__item--dd:hover .lk-nav__link,
#lk-kat-btn.is-open { background: var(--bgh); }

.lk-nav__label { max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.lk-chev { flex-shrink: 0; opacity: 0.6; transition: transform var(--t); }
.lk-nav__item--dd:hover .lk-chev,
#lk-kat-btn.is-open .lk-chev { transform: rotate(180deg); }

/* ---- Avatar ---- */
.lk-av { width: 30px; height: 30px; border-radius: 50%; overflow: hidden; border: 2px solid rgba(255,255,255,0.25); flex-shrink: 0; }
.lk-av--md { width: 38px; height: 38px; }
.lk-av--lg { width: 46px; height: 46px; }
.lk-av img { width: 100%; height: 100%; object-fit: cover; display: block; }

/* ---- Dropdown ---- */
.lk-dd {
    position: absolute; top: calc(100% + 10px); left: 0;
    min-width: 230px; background: var(--ddbg);
    border: 1px solid var(--br); border-radius: var(--r);
    box-shadow: 0 12px 40px rgba(0,0,0,0.38);
    opacity: 0; visibility: hidden; transform: translateY(-6px);
    transition: opacity var(--t), visibility var(--t), transform var(--t);
    z-index: 20; pointer-events: none;
}
.lk-dd--right { left: auto; right: 0; }
.lk-nav__item--dd:hover .lk-dd { opacity: 1; visibility: visible; transform: translateY(0); pointer-events: auto; }
.lk-dd__in { padding: 7px; }
.lk-dd__head { display: flex; align-items: center; gap: 10px; padding: 10px 10px 8px; }
.lk-dd__name { font-size: 14px; font-weight: 600; color: var(--c); margin: 0; line-height: 1.3; }
.lk-dd__email { font-size: 12px; color: var(--cm); margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 165px; }
.lk-dd__item {
    display: flex; align-items: center; gap: 9px; padding: 9px 10px;
    border-radius: var(--rs); color: rgba(255,255,255,0.85); font-size: 13.5px;
    text-decoration: none; transition: background var(--t), color var(--t);
    white-space: nowrap; cursor: pointer; border: none;
    background: none; width: 100%; font-family: inherit;
}
.lk-dd__item:hover { background: var(--bgh); color: var(--c); }
.lk-dd__item--danger { color: var(--dg); }
.lk-dd__item--danger:hover { background: rgba(248,113,113,0.1); }
.lk-dd__item svg { flex-shrink: 0; opacity: 0.65; }
.lk-dd__sep { height: 1px; background: var(--br); margin: 4px 0; }

/* ---- Desktop Search ---- */
.lk-dsearch {
    display: flex; align-items: center; gap: 9px;
    background: rgba(255,255,255,0.08); border: 1px solid var(--br);
    border-radius: 50px; padding: 0 18px; height: 40px;
    margin-left: auto; min-width: 220px; max-width: 300px; width: 100%;
    transition: background var(--t), border-color var(--t), box-shadow var(--t);
    color: var(--cm);
}
.lk-dsearch:focus-within {
    background: rgba(255,255,255,0.11);
    border-color: rgba(62,207,154,0.45);
    box-shadow: 0 0 0 3px rgba(62,207,154,0.12);
}
.lk-dsearch svg { flex-shrink: 0; }
.lk-dsearch__input { flex: 1; background: none; border: none; outline: none; color: var(--c); font-size: 14px; font-family: inherit; min-width: 0; }
.lk-dsearch__input::placeholder { color: var(--cm); }

/* ---- Mobile Controls ---- */
.lk-mob-ctrl { display: none; align-items: center; gap: 4px; margin-left: auto; }
.lk-ibtn {
    display: flex; align-items: center; justify-content: center;
    width: 42px; height: 42px; background: none; border: none;
    border-radius: var(--rs); color: var(--c); cursor: pointer;
    transition: background var(--t);
    -webkit-tap-highlight-color: transparent;
}
.lk-ibtn:hover { background: var(--bgh); }
.lk-burger { flex-direction: column; gap: 5.5px; }
.lk-burger span { display: block; width: 22px; height: 2px; background: var(--c); border-radius: 2px; transition: all 0.25s var(--ease); }
.lk-burger.is-open span:nth-child(1) { transform: translateY(7.5px) rotate(45deg); }
.lk-burger.is-open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
.lk-burger.is-open span:nth-child(3) { transform: translateY(-7.5px) rotate(-45deg); }

/* ============================================================
   KATEGORI SIDEBAR
   ============================================================ */
.lk-sidebar {
    position: fixed; inset: 0;
    z-index: calc(var(--z) + 5);
    pointer-events: none; visibility: hidden;
    transition: visibility 0s linear 0.3s;
}
.lk-sidebar.is-open { pointer-events: auto; visibility: visible; transition-delay: 0s; }

.lk-sidebar__panel {
    position: absolute; top: 0; left: 0; bottom: 0;
    width: 26vw; min-width: 240px; max-width: 340px;
    background: var(--ddbg); border-right: 1px solid var(--br);
    box-shadow: 6px 0 40px rgba(0,0,0,0.42);
    transform: translateX(-100%);
    transition: transform 0.3s var(--ease);
    will-change: transform;
    overflow-y: auto; display: flex; flex-direction: column;
}
.lk-sidebar.is-open .lk-sidebar__panel { transform: translateX(0); }

.lk-sidebar__head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 18px; border-bottom: 1px solid var(--br);
    flex-shrink: 0; height: var(--hh);
}
.lk-sidebar__title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--cm); margin: 0; }
.lk-sidebar__close {
    display: flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; background: rgba(255,255,255,0.08);
    border: none; border-radius: 50%; color: var(--c);
    cursor: pointer; transition: background var(--t); flex-shrink: 0;
}
.lk-sidebar__close:hover { background: rgba(255,255,255,0.16); }
.lk-sidebar__list { list-style: none; margin: 0; padding: 10px 10px 24px; overflow-y: auto; flex: 1; }
.lk-sidebar__list::-webkit-scrollbar { width: 3px; }
.lk-sidebar__list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.14); border-radius: 3px; }
.lk-sidebar__item {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 12px; border-radius: var(--rs);
    color: rgba(255,255,255,0.82); font-size: 14.5px; text-decoration: none;
    transition: background var(--t), color var(--t), padding-left 0.2s var(--ease);
}
.lk-sidebar__item:hover { background: rgba(255,255,255,0.08); color: var(--c); padding-left: 18px; }
.lk-sidebar__item svg { flex-shrink: 0; opacity: 0.4; transition: opacity var(--t), color var(--t); }
.lk-sidebar__item:hover svg { opacity: 1; color: var(--ac); }
.lk-sidebar__bd {
    position: absolute; inset: 0;
    background: rgba(0,0,0,0.5); backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);
    opacity: 0; transition: opacity 0.3s var(--ease); z-index: -1;
}
.lk-sidebar.is-open .lk-sidebar__bd { opacity: 1; }

/* ============================================================
   MOBILE SEARCH — FULLSCREEN slide bawah ke atas
   ============================================================ */
.lk-msearch {
    position: fixed; inset: 0;
    z-index: calc(var(--z) + 10);
    visibility: hidden; pointer-events: none;
    transition: visibility 0s linear 0.38s;
}
.lk-msearch.is-open {
    visibility: visible; pointer-events: auto;
    transition-delay: 0s;
}

/* Full screen panel */
.lk-msearch__inner {
    position: absolute; inset: 0;
    background: var(--bg);
    display: flex; flex-direction: column;
    transform: translateY(100%);
    transition: transform 0.38s cubic-bezier(0.32,0.72,0,1);
    will-change: transform;
    overflow-y: auto; overscroll-behavior: contain;
    -webkit-overflow-scrolling: touch;
}
.lk-msearch.is-open .lk-msearch__inner { transform: translateY(0); }

/* ---- Top bar: back + input ---- */
.lk-msearch__topbar {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px;
    border-bottom: 1px solid var(--br);
    flex-shrink: 0;
    position: sticky; top: 0; z-index: 2;
    background: var(--bg);
    /* safe area untuk notch */
    padding-top: max(14px, env(safe-area-inset-top, 14px));
}

/* Tombol back — pojok kiri atas */
.lk-msearch__back {
    display: flex; align-items: center; justify-content: center;
    width: 44px; height: 44px; flex-shrink: 0;
    background: rgba(255,255,255,0.09);
    border: none; border-radius: 50%;
    color: var(--c); cursor: pointer;
    transition: background var(--t), transform 0.15s var(--ease);
    -webkit-tap-highlight-color: transparent;
}
.lk-msearch__back:hover { background: rgba(255,255,255,0.16); }
.lk-msearch__back:active { transform: scale(0.93); }

.lk-msearch__form { flex: 1; }

.lk-msearch__inputwrap {
    display: flex; align-items: center; gap: 10px;
    background: rgba(255,255,255,0.09);
    border: 1.5px solid rgba(255,255,255,0.14);
    border-radius: 50px; padding: 0 16px; height: 48px;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}
.lk-msearch__inputwrap:focus-within {
    border-color: var(--ac);
    background: rgba(255,255,255,0.12);
    box-shadow: 0 0 0 3px rgba(62,207,154,0.15);
}
.lk-msearch__ico { flex-shrink: 0; color: var(--cm); transition: color 0.2s; }
.lk-msearch__inputwrap:focus-within .lk-msearch__ico { color: var(--ac); }
.lk-msearch__input {
    flex: 1; background: none; border: none; outline: none;
    color: var(--c); font-size: 16px; font-family: inherit; min-width: 0;
}
.lk-msearch__input::placeholder { color: var(--cm); }
.lk-msearch__clear {
    display: flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; flex-shrink: 0;
    background: rgba(255,255,255,0.12); border: none; border-radius: 50%;
    color: var(--cm); cursor: pointer;
    transition: background var(--t), color var(--t);
    -webkit-tap-highlight-color: transparent;
}
.lk-msearch__clear:hover { background: rgba(255,255,255,0.22); color: var(--c); }

/* ---- Body konten ---- */
.lk-msearch__body {
    padding: 26px 20px;
    flex: 1;
    /* safe area bawah */
    padding-bottom: max(26px, calc(env(safe-area-inset-bottom, 0px) + 26px));
}

.lk-msearch__label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.1em;
    color: var(--cm); margin: 0 0 12px;
}
.lk-msearch__label--gap { margin-top: 30px; }

.lk-msearch__chips { display: flex; flex-wrap: wrap; gap: 9px; }

.lk-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 8px 15px; border-radius: 50px;
    background: rgba(255,255,255,0.07); border: 1px solid var(--br);
    color: rgba(255,255,255,0.78); font-size: 14px; text-decoration: none;
    transition: background var(--t), border-color var(--t), color var(--t);
    white-space: nowrap;
    -webkit-tap-highlight-color: transparent;
}
.lk-chip:hover, .lk-chip:active {
    background: rgba(62,207,154,0.14);
    border-color: rgba(62,207,154,0.4);
    color: var(--ac);
}
.lk-chip svg { opacity: 0.45; }

.lk-msearch__trends { display: flex; flex-wrap: wrap; gap: 8px; }

.lk-trend {
    display: inline-flex; align-items: center;
    padding: 7px 15px; border-radius: 50px;
    background: none; border: 1px solid rgba(255,255,255,0.16);
    color: rgba(255,255,255,0.6); font-size: 13.5px; font-family: inherit;
    cursor: pointer; white-space: nowrap;
    transition: background var(--t), border-color var(--t), color var(--t);
    -webkit-tap-highlight-color: transparent;
}
.lk-trend:hover, .lk-trend:active {
    background: rgba(255,255,255,0.09);
    border-color: rgba(255,255,255,0.28);
    color: var(--c);
}
.lk-trend::before { content: '#'; opacity: 0.4; margin-right: 3px; font-size: 12px; }

/* ============================================================
   MOBILE MENU DRAWER
   ============================================================ */
.lk-mob-menu {
    position: fixed; top: var(--hh); left: 0; right: 0; bottom: 0;
    background: var(--bg); overflow-y: auto;
    transform: translateX(-100%);
    transition: transform 0.3s var(--ease);
    will-change: transform;
    z-index: calc(var(--z) - 1);
    padding: 8px 0 48px;
    -webkit-overflow-scrolling: touch;
}
.lk-mob-menu.is-open { transform: translateX(0); }

.lk-mob-user { display: flex; align-items: center; gap: 14px; padding: 18px 22px; }
.lk-mob-user__name { font-size: 16px; font-weight: 600; color: var(--c); margin: 0; line-height: 1.2; }
.lk-mob-user__sub { font-size: 12.5px; color: var(--cm); margin: 2px 0 0; }

.lk-mob-guest { padding: 20px 22px 12px; }
.lk-mob-guest__title { font-size: 17px; font-weight: 600; color: var(--c); margin: 0 0 4px; }
.lk-mob-guest__sub { font-size: 13px; color: var(--cm); margin: 0; }

.lk-mob-sep { height: 1px; background: var(--br); margin: 6px 0; }

.lk-mob-link {
    display: flex; align-items: center; gap: 12px;
    padding: 15px 22px; color: rgba(255,255,255,0.85); font-size: 16px;
    text-decoration: none; transition: background var(--t), color var(--t);
    border: none; background: none; width: 100%;
    cursor: pointer; font-family: inherit;
    -webkit-tap-highlight-color: transparent;
}
.lk-mob-link:hover { background: var(--bgh); color: var(--c); }
.lk-mob-link svg { flex-shrink: 0; opacity: 0.6; }
.lk-mob-link--accent { color: var(--ac); font-weight: 600; }
.lk-mob-link--danger { color: var(--dg); }
.lk-mob-link--danger:hover { background: rgba(248,113,113,0.08); }

/* Overlay */
.lk-ov {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.5); z-index: calc(var(--z) - 2);
    backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);
}
.lk-ov.is-open { display: block; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1023px) {
    .lk-nav, .lk-dsearch { display: none; }
    .lk-mob-ctrl { display: flex; }
    .lk-sidebar__panel { width: 78vw; max-width: none; }
}
@media (min-width: 1024px) {
    .lk-mob-menu, .lk-ov { display: none !important; }
    .lk-msearch { display: none !important; }
}

/* Tablet 600–1023 */
@media (max-width: 1023px) and (min-width: 600px) {
    :root { --hh: 68px; }
    .lk-header__container { padding: 0 24px; }
    .lk-logo__img { height: 38px; }
    .lk-msearch__body { padding: 30px 28px; }
}

/* Mobile < 600px */
@media (max-width: 599px) {
    :root { --hh: 60px; }
    .lk-header__container { padding: 0 16px; }
    .lk-logo__img { height: 34px; }
    .lk-ibtn { width: 40px; height: 40px; }
    .lk-msearch__topbar { padding: 12px 14px; gap: 10px; }
    .lk-msearch__back { width: 42px; height: 42px; }
    .lk-msearch__inputwrap { height: 46px; }
    .lk-msearch__input { font-size: 15px; }
    .lk-msearch__body { padding: 22px 16px; }
    .lk-mob-link { font-size: 15px; padding: 13px 18px; }
    .lk-mob-user { padding: 16px 18px; }
}

/* Kecil banget < 400px */
@media (max-width: 399px) {
    :root { --hh: 56px; }
    .lk-logo__img { height: 30px; }
    .lk-header__container { padding: 0 12px; gap: 4px; }
    .lk-ibtn { width: 38px; height: 38px; }
}
</style>

<script>
(function () {
    var header    = document.getElementById('lk-header');
    var katBtn    = document.getElementById('lk-kat-btn');
    var sidebar   = document.getElementById('lk-sidebar');
    var sidebarBd = document.getElementById('lk-sidebar-bd');
    var sideClose = document.getElementById('lk-sidebar-close');
    var msearch   = document.getElementById('lk-msearch');
    var msClose   = document.getElementById('lk-msearch-close');
    var msBtnO    = document.getElementById('lk-m-search-open');
    var msi       = document.getElementById('lk-msi');
    var msiClear  = document.getElementById('lk-msi-clear');
    var menuBtn   = document.getElementById('lk-menu-btn');
    var mobMenu   = document.getElementById('lk-mob-menu');
    var ov        = document.getElementById('lk-ov');

    function lock()     { document.body.style.overflow = 'hidden'; }
    function unlock()   { document.body.style.overflow = ''; }
    function isMobile() { return window.innerWidth < 1024; }

    /* ---- Sidebar Kategori ---- */
    function sidebarOpen() {
        sidebar.classList.add('is-open');
        sidebar.setAttribute('aria-hidden', 'false');
        katBtn.classList.add('is-open');
        katBtn.setAttribute('aria-expanded', 'true');
        if (isMobile()) lock();
    }
    function sidebarClose() {
        sidebar.classList.remove('is-open');
        sidebar.setAttribute('aria-hidden', 'true');
        katBtn.classList.remove('is-open');
        katBtn.setAttribute('aria-expanded', 'false');
        if (isMobile()) unlock();
    }
    if (katBtn)    katBtn.addEventListener('click', function () { sidebar.classList.contains('is-open') ? sidebarClose() : sidebarOpen(); });
    if (sideClose) sideClose.addEventListener('click', sidebarClose);
    if (sidebarBd) sidebarBd.addEventListener('click', sidebarClose);

    /* ---- Mobile Search Fullscreen ---- */
    function msOpen() {
        msearch.classList.add('is-open');
        msearch.setAttribute('aria-hidden', 'false');
        lock();
        setTimeout(function () { if (msi) msi.focus(); }, 380);
    }
    function msCloseF() {
        msearch.classList.remove('is-open');
        msearch.setAttribute('aria-hidden', 'true');
        unlock();
        if (msi) msi.blur();
    }
    if (msBtnO) msBtnO.addEventListener('click', msOpen);
    if (msClose) msClose.addEventListener('click', msCloseF);

    /* Clear button */
    if (msi && msiClear) {
        msi.addEventListener('input', function () { msiClear.hidden = !msi.value; });
        msiClear.addEventListener('click', function () { msi.value = ''; msiClear.hidden = true; msi.focus(); });
    }

    /* ---- Mobile Menu ---- */
    function mobClose() {
        mobMenu.classList.remove('is-open');
        mobMenu.setAttribute('aria-hidden', 'true');
        menuBtn.classList.remove('is-open');
        menuBtn.setAttribute('aria-expanded', 'false');
        ov.classList.remove('is-open');
        unlock();
    }
    if (menuBtn) {
        menuBtn.addEventListener('click', function () {
            var open = mobMenu.classList.toggle('is-open');
            mobMenu.setAttribute('aria-hidden', String(!open));
            menuBtn.classList.toggle('is-open', open);
            menuBtn.setAttribute('aria-expanded', String(open));
            ov.classList.toggle('is-open', open);
            open ? lock() : unlock();
        });
    }
    if (ov) ov.addEventListener('click', mobClose);

    /* ---- ESC ---- */
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        sidebarClose(); msCloseF(); mobClose();
    });

    /* ---- Scroll shadow ---- */
    window.addEventListener('scroll', function () {
        header.classList.toggle('scrolled', window.scrollY > 12);
    }, { passive: true });

    /* ---- Trending fill ---- */
    window.lkFill = function (kw) {
        if (!msi) return;
        msi.value = kw;
        if (msiClear) msiClear.hidden = false;
        msi.focus();
    };
})();
</script>
