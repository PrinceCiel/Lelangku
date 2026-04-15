@extends('layouts.kerangkafrontend')
@section('content')

<div class="hero-section style-2 pb-lg-400">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home.user') }}">Home</a></li>
            <li><a href="#0">My Account</a></li>
            <li><span>Riwayat Refund</span></li>
        </ul>
    </div>
    <div class="bg_img hero-bg bottom_center"
        data-background="{{ asset('sbidu/assets/images/banner/hero-bg.png') }}"></div>
</div>

<section class="dashboard-section padding-bottom mt--240 mt-lg--325 pos-rel">
    <div class="container">
        <div class="row justify-content-center">

            {{-- Sidebar --}}
            <div class="col-sm-10 col-md-7 col-lg-4">
                <div class="dashboard-widget mb-30 mb-lg-0">
                    <div class="user">
                        <div class="thumb-area">
                            <div class="thumb">
                                <img src="{{ Storage::url(Auth::user()->foto) }}" alt="user">
                            </div>
                        </div>
                        <div class="content">
                            <h5 class="title"><a href="#0">{{ Auth::user()->nama_lengkap }}</a></h5>
                            <span class="username">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                    <ul class="dashboard-menu">
                        <li>
                            <a href="{{ route('dashboard.user') }}">
                                <i class="flaticon-dashboard"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('personal.user') }}">
                                <i class="flaticon-settings"></i>Personal Profile
                            </a>
                        </li>
                        <li class="active">
                            <a href="{{ route('refund.user') }}">
                                <i class="flaticon-return"></i>Refund Deposit
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Konten --}}
            <div class="col-lg-8">

                @if (session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                <div class="dashboard-widget mb-40">
                    <div class="dashboard-title mb-30">
                        <h5 class="title">Riwayat Refund Deposit</h5>
                    </div>

                    @forelse ($refunds as $refund)
                        @php
                            $lelang = $refund->deposit->lelang ?? null;
                            $barang = $lelang->barang ?? null;
                        @endphp

                        <div class="dash-pro-item mb-20 dashboard-widget"
                            style="border-left: 4px solid
                                {{ $refund->status === 'selesai' ? '#28a745' :
                                   ($refund->status === 'diproses' ? '#17a2b8' :
                                   ($refund->status === 'gagal' ? '#dc3545' : '#ffc107')) }};">

                            {{-- Header card --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1 fw-semibold">
                                        {{ $lelang->kode_lelang ?? '-' }} — {{ $barang->nama ?? '-' }}
                                    </h6>
                                    <small class="text-muted">Dibuat {{ $refund->created_at->format('d M Y') }}</small>
                                </div>
                                @if ($refund->status === 'pending')
                                    <span class="badge" style="background:#fff3cd; color:#856404; padding:6px 12px; border-radius:20px;">
                                        ⏳ Menunggu
                                    </span>
                                @elseif ($refund->status === 'diproses')
                                    <span class="badge" style="background:#d1ecf1; color:#0c5460; padding:6px 12px; border-radius:20px;">
                                        🔄 Diproses
                                    </span>
                                @elseif ($refund->status === 'selesai')
                                    <span class="badge" style="background:#d4edda; color:#155724; padding:6px 12px; border-radius:20px;">
                                        ✅ Selesai
                                    </span>
                                @elseif ($refund->status === 'gagal')
                                    <span class="badge" style="background:#f8d7da; color:#721c24; padding:6px 12px; border-radius:20px;">
                                        ❌ Ditolak
                                    </span>
                                @endif
                            </div>

                            {{-- Info refund --}}
                            <ul class="dash-pro-body">
                                <li>
                                    <div class="info-name">Jumlah Refund</div>
                                    <div class="info-value fw-bold" style="font-size:1.1rem;">
                                        Rp{{ number_format($refund->jumlah, 0, ',', '.') }}
                                    </div>
                                </li>
                                <li>
                                    <div class="info-name">Metode Bayar Asal</div>
                                    <div class="info-value">{{ strtoupper($refund->payment_type ?? '-') }}</div>
                                </li>

                                @if ($refund->rekening_tujuan)
                                    <li>
                                        <div class="info-name">Rekening Tujuan</div>
                                        <div class="info-value">
                                            {{ $refund->bank_tujuan }} — {{ $refund->rekening_tujuan }}
                                            <small class="d-block text-muted">a/n {{ $refund->nama_pemilik }}</small>
                                        </div>
                                    </li>
                                @endif

                                @if ($refund->status === 'selesai' && $refund->processed_at)
                                    <li>
                                        <div class="info-name">Diproses Pada</div>
                                        <div class="info-value">{{ $refund->processed_at->format('H:i, d M Y') }}</div>
                                    </li>
                                @endif

                                @if ($refund->catatan_admin)
                                    <li>
                                        <div class="info-name">Catatan Admin</div>
                                        <div class="info-value">{{ $refund->catatan_admin }}</div>
                                    </li>
                                @endif

                                @if ($refund->bukti_transfer)
                                    <li>
                                        <div class="info-name">Bukti Transfer</div>
                                        <div class="info-value">
                                            <a href="{{ Storage::url($refund->bukti_transfer) }}"
                                                target="_blank" class="custom-button" style="padding: 6px 16px; font-size:.85rem;">
                                                Lihat Bukti
                                            </a>
                                        </div>
                                    </li>
                                @endif
                            </ul>

                            {{-- CTA: isi rekening kalau belum --}}
                            @if ($refund->status === 'pending' && !$refund->rekening_tujuan)
                                <div class="mt-3 p-3 rounded"
                                    style="background:#fff8e1; border:1px solid #ffe082;">
                                    <p class="mb-2" style="font-size:.88rem; color:#5a3e00;">
                                        ⚠️ Harap isi rekening tujuan refund agar admin dapat memproses pengembalian dana Anda.
                                    </p>
                                    <button class="custom-button"
                                        style="padding:8px 20px; font-size:.85rem;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalRekening-{{ $refund->id }}">
                                        Isi Rekening Sekarang
                                    </button>
                                </div>
                            @endif

                        </div>

                    @empty
                        <div class="text-center py-5">
                            <img src="{{ asset('sbidu/assets/images/dashboard/01.png') }}" alt="" style="width:80px; opacity:.4;">
                            <p class="text-muted mt-3">Tidak ada riwayat refund deposit.</p>
                        </div>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
</section>

{{-- MODALS ISI REKENING --}}
@foreach ($refunds as $refund)
    @if ($refund->status === 'pending' && !$refund->rekening_tujuan)
        <div class="modal fade" id="modalRekening-{{ $refund->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-0">

                        <div class="d-flex align-items-center justify-content-between px-4 pt-4 pb-3 border-bottom">
                            <h5 class="mb-0 fw-semibold">Isi Rekening Tujuan Refund</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form method="POST" action="{{ route('refund.isi-rekening', $refund->id) }}"
                            class="px-4 py-4">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-medium">Jumlah yang di-refund</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control"
                                        value="{{ number_format($refund->jumlah, 0, ',', '.') }}" disabled />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Bank Tujuan <span class="text-danger">*</span></label>
                                <select name="bank_tujuan" class="form-select" required>
                                    <option value="">— Pilih Bank —</option>
                                    <option value="BCA">BCA</option>
                                    <option value="BNI">BNI</option>
                                    <option value="BRI">BRI</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="CIMB Niaga">CIMB Niaga</option>
                                    <option value="Permata">Permata</option>
                                    <option value="Danamon">Danamon</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">No. Rekening <span class="text-danger">*</span></label>
                                <input type="text" name="rekening_tujuan" class="form-control"
                                    placeholder="Contoh: 1234567890" required />
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-medium">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pemilik" class="form-control"
                                    placeholder="Sesuai buku tabungan" required />
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="custom-button" style="padding:10px 24px;">
                                    Simpan Rekening
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection
