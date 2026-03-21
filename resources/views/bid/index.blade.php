@extends('layouts.kerangkabackend')

@push('style')
<style>
    .bid-winner { border-left: 3px solid var(--bs-success); }
    .bid-cancelled { opacity: .45; }
    .avatar-suspicious { outline: 2px solid var(--bs-warning); outline-offset: 2px; }
    .avatar-banned     { outline: 2px solid var(--bs-danger);  outline-offset: 2px; filter: grayscale(1); opacity:.6; }
    .bid-list-scroll   { max-height: 360px; overflow-y: auto; }
    .bid-list-scroll::-webkit-scrollbar { width: 4px; }
    .bid-list-scroll::-webkit-scrollbar-thumb { background: var(--bs-border-color); border-radius: 4px; }
    .countdown-badge   { font-variant-numeric: tabular-nums; font-size: 11px; letter-spacing: .3px; }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- Page header --}}
        <div class="d-flex align-items-center justify-content-between mb-5">
            <div class="card-toast-wrapper" id="cardToastWrapper"></div>
            <div>
                <h4 class="fw-bold mb-1">Monitor Bid Aktif</h4>
                <p class="text-muted mb-0">{{ $lelang->count() }} lelang sedang dibuka</p>
            </div>
        </div>

        {{-- Kartu per lelang --}}
        @forelse($lelang as $item)
        <div class="card mb-5">

            {{-- Card Header --}}
            <div class="card-header border-bottom d-flex flex-column flex-md-row
                         align-items-md-center justify-content-between gap-3 py-4">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ Storage::url($item->barang->foto) }}"
                         alt="{{ $item->barang->nama }}"
                         class="rounded-2"
                         style="width:48px;height:48px;object-fit:cover;">
                    <div>
                        <h5 class="mb-0 fw-semibold">{{ $item->barang->nama }}</h5>
                        <code class="text-primary small">{{ $item->kode_lelang }}</code>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="badge bg-label-secondary">
                        Harga Awal: <strong>Rp {{ number_format($item->barang->harga, 0, ',', '.') }}</strong>
                    </span>
                    <span class="badge bg-label-success">
                        Bid Tertinggi: <strong>Rp {{ number_format($item->bid_tertinggi, 0, ',', '.') }}</strong>
                    </span>
                    <span class="badge bg-label-success countdown-badge"
                          data-end="{{ \Carbon\Carbon::parse($item->jadwal_berakhir)->toIso8601String() }}">
                        <i class="ri ri-time-line me-1"></i>
                        <span class="countdown-text">Menghitung...</span>
                    </span>
                </div>
            </div>

            {{-- Bid list --}}
            <div class="card-body p-0">
                <div class="bid-list-scroll">
                    @forelse($item->bid as $i => $bid)
                    <div class="d-flex align-items-center px-5 py-3 border-bottom
                                {{ $i === 0 ? 'bid-winner' : '' }}
                                {{ $bid->status === 'cancelled' ? 'bid-cancelled' : '' }}">

                        {{-- Rank --}}
                        <div class="me-3 text-center" style="min-width:28px;">
                            @if($i === 0)
                                <i class="ri ri-trophy-line text-warning fs-5"></i>
                            @else
                                <small class="text-muted fw-bold">#{{ $i + 1 }}</small>
                            @endif
                        </div>

                        {{-- Avatar --}}
                        <div class="avatar me-3 flex-shrink-0">
                            <img src="{{ Storage::url($bid->users->foto) }}"
                                 alt="{{ $bid->users->nama_lengkap }}"
                                 class="rounded
                                    {{ $bid->users->is_suspicious ? 'avatar-suspicious' : '' }}
                                    {{ $bid->users->is_banned     ? 'avatar-banned'     : '' }}"
                                 style="width:38px;height:38px;object-fit:cover;">
                        </div>

                        {{-- Info --}}
                        <div class="flex-grow-1 d-flex flex-wrap align-items-center
                                    justify-content-between gap-2">
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="mb-0">{{ $bid->users->nama_lengkap }}</h6>
                                    @if($bid->users->is_banned)
                                        <span class="badge bg-label-danger" style="font-size:10px;">Banned</span>
                                    @elseif($bid->users->is_suspicious)
                                        <span class="badge bg-label-warning" style="font-size:10px;">Suspicious</span>
                                    @endif
                                    @if($bid->status === 'cancelled')
                                        <span class="badge bg-label-secondary" style="font-size:10px;">Dibatalkan</span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($bid->created_at)->format('d M Y, H:i') }}
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-end">
                                    <div class="fw-semibold {{ $i === 0 ? 'text-success' : '' }}">
                                        Rp {{ number_format($bid->bid, 0, ',', '.') }}
                                    </div>
                                    <small class="text-muted">IDR</small>
                                </div>
                                <div class="d-inline-flex gap-1">
                                    {{-- Detail user --}}
                                    <button type="button"
                                            class="btn btn-icon btn-sm text-primary rounded-pill waves-effect btn-user-detail"
                                            data-user-id="{{ $bid->users->id }}"
                                            data-user-nama="{{ $bid->users->nama_lengkap }}"
                                            data-lelang-id="{{ $item->id }}"
                                            title="Detail User">
                                        <i class="icon-base ri ri-user-search-line icon-22px"></i>
                                    </button>
                                    {{-- Cancel bid --}}
                                    @if($bid->status !== 'cancelled')
                                    <button type="button"
                                            class="btn btn-icon btn-sm text-secondary rounded-pill waves-effect btn-cancel-bid"
                                            data-bid-id="{{ $bid->id }}"
                                            data-bid-amount="{{ number_format($bid->bid, 0, ',', '.') }}"
                                            data-user-name="{{ $bid->users->nama_lengkap }}"
                                            title="Batalkan Bid">
                                        <i class="icon-base ri ri-close-circle-line icon-22px"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    @empty
                    <div class="px-5 py-5 text-center text-muted">
                        <i class="ri ri-inbox-line fs-2 d-block mb-2"></i>
                        Belum ada bid masuk.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-6">
                <i class="ri ri-auction-line fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">Tidak ada lelang yang sedang dibuka.</h5>
            </div>
        </div>
        @endforelse

    </div>

    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl">
            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    © <script>document.write(new Date().getFullYear());</script>, made by
                    <a href="https://themeselection.com" target="_blank" class="footer-link fw-medium">Ardhika Pratama  </a>
                </div>
            </div>
        </div>
    </footer>
    <div class="content-backdrop fade"></div>
</div>


{{-- ═══════════════════════════════════════
     MODAL DETAIL USER
═══════════════════════════════════════ --}}
<div class="modal fade" id="modalUserDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-body p-0">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="icon-base ri ri-user-search-line icon-28px"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold" id="uModalTitle">Detail User</h5>
                            <small class="text-muted">Aktivitas & deteksi kecurangan.</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Loading --}}
                <div id="modalUserLoading" class="px-5 py-6 text-center">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-3 mb-0">Memuat data...</p>
                </div>

                {{-- Content --}}
                <div id="modalUserContent" style="display:none;">

                    {{-- Profile + aksi --}}
                    <div class="px-5 py-4 border-bottom">
                        <div class="d-flex align-items-center gap-4">
                            <img id="uFoto" src="" alt="foto"
                                 class="rounded-3 flex-shrink-0"
                                 style="width:64px;height:64px;object-fit:cover;">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h5 class="mb-0 fw-semibold" id="uNama">—</h5>
                                    <span id="uBadgeBanned"  class="badge bg-label-danger  d-none">Banned</span>
                                    <span id="uBadgeSuspect" class="badge bg-label-warning d-none">Suspicious</span>
                                </div>
                                {{-- Aksi --}}
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <button class="btn btn-sm btn-warning" id="btnMarkSuspicious">
                                        <i class="ri ri-alert-line me-1"></i>
                                        <span id="btnSuspectLabel">Tandai Suspicious</span>
                                    </button>
                                    <button class="btn btn-sm btn-danger" id="btnBanUser">
                                        <i class="ri ri-forbid-line me-1"></i>
                                        <span id="btnBanLabel">Blokir User</span>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" id="btnRemoveFromLelang">
                                        <i class="ri ri-logout-box-line me-1"></i> Keluarkan + Refund
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-4">

                        {{-- IP Duplikat warning --}}
                        <div id="uIpWarning" class="alert alert-danger d-none mb-4" role="alert">
                            <div class="d-flex align-items-start gap-2">
                                <i class="ri ri-spy-line fs-5 flex-shrink-0 mt-1"></i>
                                <div>
                                    <strong>Terdeteksi IP sama!</strong>
                                    <div id="uIpDuplikatList" class="mt-2 d-flex flex-column gap-1"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Riwayat bid user di lelang ini --}}
                        <p class="text-uppercase text-muted fw-semibold mb-3"
                           style="font-size:.7rem;letter-spacing:.08em;">
                            <i class="ri ri-history-line me-1"></i> Riwayat Bid di Lelang Ini
                        </p>
                        <div id="uBidList" class="d-flex flex-column gap-2"
                             style="max-height:260px;overflow-y:auto;"></div>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ri ri-close-line me-1"></i> Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('assets/js/custom/toast.js') }}"></script>
<script>
// ── Countdown ────────────────────────────────────────────────
document.querySelectorAll('[data-end]').forEach(function (el) {
    const end    = new Date(el.dataset.end).getTime();
    const textEl = el.querySelector('.countdown-text');
    function tick() {
        const diff = end - Date.now();
        if (diff <= 0) {
            textEl.textContent = 'Berakhir';
            el.classList.replace('bg-label-success', 'bg-label-danger');
            return;
        }
        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff % 86400000) / 3600000);
        const m = Math.floor((diff % 3600000)  / 60000);
        const s = Math.floor((diff % 60000)    / 1000);
        textEl.textContent = d > 0
            ? `${d}h ${String(h).padStart(2,'0')}j ${String(m).padStart(2,'0')}m`
            : `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        setTimeout(tick, 1000);
    }
    tick();
});

// ── State modal user ─────────────────────────────────────────
let _currentUserId   = null;
let _currentLelangId = null;
const userDetailModal = new bootstrap.Modal(document.getElementById('modalUserDetail'));

// ── Buka modal detail user ───────────────────────────────────
$(document).on('click', '.btn-user-detail', function () {
    _currentUserId   = $(this).data('user-id');
    _currentLelangId = $(this).data('lelang-id');
    const nama       = $(this).data('user-nama');

    $('#uModalTitle').text(nama);
    $('#modalUserLoading').show();
    $('#modalUserContent').hide();
    userDetailModal.show();

    $.get(`/admin/bid/user-detail/${_currentUserId}/${_currentLelangId}`)
        .done(function (res) {
            populateUserModal(res);
            $('#modalUserLoading').hide();
            $('#modalUserContent').show();
        })
        .fail(function () {
            $('#modalUserLoading').html('<p class="text-danger px-3">Gagal memuat data.</p>');
        });
});

function populateUserModal(res) {
    const u = res.user;

    // Profile
    $('#uFoto').attr('src', '/storage/' + (u.foto ?? 'default.jpg'));
    $('#uNama').text(u.nama_lengkap);
    $('#uBadgeBanned').toggleClass('d-none',  !u.is_banned);
    $('#uBadgeSuspect').toggleClass('d-none', !u.is_suspicious);

    // Label tombol dinamis
    $('#btnSuspectLabel').text(u.is_suspicious ? 'Hapus Suspicious' : 'Tandai Suspicious');
    $('#btnBanLabel').text(u.is_banned ? 'Unban User' : 'Blokir User');
    $('#btnBanUser').removeClass('btn-danger btn-success')
                   .addClass(u.is_banned ? 'btn-success' : 'btn-danger');

    // IP duplikat
    if (res.ip_duplikat && res.ip_duplikat.length > 0) {
        const html = res.ip_duplikat.map(b => `
            <div class="d-flex align-items-center gap-2">
                <img src="/storage/${b.users?.foto ?? ''}" class="rounded"
                     style="width:24px;height:24px;object-fit:cover;">
                <span class="fw-semibold small">${b.users?.nama_lengkap ?? '—'}</span>
                <code class="ms-auto small">${b.ip_address ?? ''}</code>
            </div>`).join('');
        $('#uIpDuplikatList').html(html);
        $('#uIpWarning').removeClass('d-none');
    } else {
        $('#uIpWarning').addClass('d-none');
    }

    // Riwayat bid di lelang ini
    if (res.bid_di_lelang && res.bid_di_lelang.length > 0) {
        const html = res.bid_di_lelang.map((b, i) => `
            <div class="d-flex justify-content-between align-items-center p-2 rounded border
                        ${b.status === 'cancelled' ? 'opacity-50' : ''}">
                <div class="d-flex align-items-center gap-2">
                    ${i === 0 ? '<i class="ri ri-trophy-line text-warning"></i>' : `<small class="text-muted">#${i+1}</small>`}
                    <div>
                        <div class="fw-semibold ${i === 0 ? 'text-success' : ''}">
                            Rp ${parseInt(b.bid).toLocaleString('id-ID')}
                        </div>
                        <small class="text-muted">
                            ${new Date(b.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}
                            ${new Date(b.created_at).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}
                        </small>
                    </div>
                </div>
                ${b.status === 'cancelled'
                    ? '<span class="badge bg-label-secondary">Dibatalkan</span>'
                    : ''}
            </div>`).join('');
        $('#uBidList').html(html);
    } else {
        $('#uBidList').html('<small class="text-muted">Belum ada bid.</small>');
    }
}

// ── Suspicious ───────────────────────────────────────────────
$(document).on('click', '#btnMarkSuspicious', function () {
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    Swal.fire({
        title: 'Konfirmasi',
        text: $('#btnSuspectLabel').text() + '?',
        icon: 'question',
        theme: isDark ? 'dark' : 'light',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        customClass: { confirmButton: 'btn btn-warning', cancelButton: 'btn btn-outline-secondary ms-1' },
        buttonsStyling: false,
    }).then(res => {
        if (!res.isConfirmed) return;
        $.post(`/admin/bid/suspicious/${_currentUserId}`, { _token: $('meta[name="csrf-token"]').attr('content') })
            .done(r => {
                showCardToast('success', r.message);
                // Reload konten modal
                $.get(`/admin/bid/user-detail/${_currentUserId}/${_currentLelangId}`)
                    .done(res2 => populateUserModal(res2));
            });
    });
});

// ── Blokir / Unban ───────────────────────────────────────────
$(document).on('click', '#btnBanUser', function () {
    const isBanned = $('#btnBanLabel').text().includes('Unban');
    const isDark   = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const token    = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
        title: isBanned ? 'Unban User?' : 'Blokir User?',
        html: isBanned
            ? 'User akan bisa kembali mengikuti lelang.'
            : '<input id="swalReason" class="swal2-input" placeholder="Alasan blokir (opsional)">',
        icon: 'warning',
        theme: isDark ? 'dark' : 'light',
        showCancelButton: true,
        confirmButtonText: isBanned ? 'Ya, Unban' : 'Ya, Blokir',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: isBanned ? 'btn btn-success' : 'btn btn-danger',
            cancelButton: 'btn btn-outline-secondary ms-1',
        },
        buttonsStyling: false,
    }).then(res => {
        if (!res.isConfirmed) return;
        const reason = document.getElementById('swalReason')?.value ?? '';
        const url    = isBanned ? `/admin/bid/unban/${_currentUserId}` : `/admin/bid/ban/${_currentUserId}`;
        $.post(url, { _token: token, reason })
            .done(r => {
                showCardToast('success', r.message);
                userDetailModal.hide();
                setTimeout(() => location.reload(), 800);
            });
    });
});

// ── Keluarkan + Refund ───────────────────────────────────────
$(document).on('click', '#btnRemoveFromLelang', function () {
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const token  = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
        title: 'Keluarkan dari Lelang?',
        text: 'Semua bid user di lelang ini akan dihapus dan deposit di-refund.',
        icon: 'warning',
        theme: isDark ? 'dark' : 'light',
        showCancelButton: true,
        confirmButtonText: 'Ya, Keluarkan',
        cancelButtonText: 'Batal',
        customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-outline-secondary ms-1' },
        buttonsStyling: false,
    }).then(res => {
        if (!res.isConfirmed) return;
        $.post(`/admin/bid/remove/${_currentUserId}/${_currentLelangId}`, { _token: token })
            .done(r => {
                showCardToast('success', r.message);
                userDetailModal.hide();
                setTimeout(() => location.reload(), 800);
            });
    });
});

// ── Cancel bid ───────────────────────────────────────────────
$(document).on('click', '.btn-cancel-bid', function () {
    const bidId    = $(this).data('bid-id');
    const amount   = $(this).data('bid-amount');
    const userName = $(this).data('user-name');
    const isDark   = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const token    = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
        title: 'Batalkan Bid?',
        html: `Bid <strong>Rp ${amount}</strong> oleh <strong>${userName}</strong> akan dibatalkan.`,
        icon: 'warning',
        theme: isDark ? 'dark' : 'light',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Batal',
        customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-outline-secondary ms-1' },
        buttonsStyling: false,
    }).then(res => {
        if (!res.isConfirmed) return;
        $.post(`/admin/bid/cancel-bid/${bidId}`, { _token: token })
            .done(r => {
                showCardToast('success', r.message);
                setTimeout(() => location.reload(), 800);
            });
    });
});
</script>
@endpush
