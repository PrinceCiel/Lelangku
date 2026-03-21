@extends('layouts.kerangkabackend')
@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    @endpush
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Product List Widget -->
            <div class="card mb-6">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Total Barang</p>
                                        <h4 class="mb-1">{{ $barangs->count() }}</h4>
                                        <p class="mb-0">
                                            <span class="me-2">All Products</span>
                                            <span class="badge rounded-pill bg-label-success">Active</span>
                                        </p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-shopping-bag-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-6" />
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Barang Baru</p>
                                        <h4 class="mb-1">{{ $barangs->where('kondisi', 'Baru')->count() }}</h4>
                                        <p class="mb-0">
                                            <span class="me-2">New Items</span>
                                            <span class="badge rounded-pill bg-label-success">+100%</span>
                                        </p>
                                    </div>
                                    <div class="avatar me-lg-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-star-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none" />
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                    <div>
                                        <p class="mb-1">Barang Bekas</p>
                                        <h4 class="mb-1">{{ $barangs->where('kondisi', 'Bekas')->count() }}</h4>
                                        <p class="mb-0">Used Items</p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-recycle-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1">Barang Rusak</p>
                                        <h4 class="mb-1">{{ $barangs->where('kondisi', 'Rusak')->count() }}</h4>
                                        <p class="mb-0">
                                            <span class="me-2">Damaged</span>
                                            <span class="badge rounded-pill bg-label-danger">Need Repair</span>
                                        </p>
                                    </div>
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-3 text-heading">
                                            <i class="icon-base ri ri-tools-line icon-28px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Displaying a specific field's first error message --}}
            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <!-- Product List Table -->
            <div class="card position-relative">
                <div class="card-toast-wrapper" id="cardToastWrapper"></div>
                <div id="table-loader" class="table-loader-wrapper">
                    <div class="loader"><span class="loader-inner"></span></div>
                </div>
                <div class="card-header border-bottom">
                    <div class="d-flex justify-content-between align-items-center row gap-5 gx-6 gap-md-0">
                        <div class="col-md-4 product_status"></div>
                        <div class="col-md-4 product_category"></div>
                        <div class="col-md-4 product_stock"></div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table class="datatables-products table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Tersedia</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Kondisi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- / Content -->

        <!-- Footer -->
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">
                <div
                    class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                    <div class="mb-2 mb-md-0">
                        &#169;
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        , made with ❤️ by
                        <a href="https://pixinvent.com" target="_blank" class="footer-link fw-medium">Pixinvent</a>
                    </div>
                    <div class="d-none d-lg-inline-block">
                        <a href="https://themeforest.net/licenses/standard" class="footer-link me-4"
                            target="_blank">License</a>
                        <a href="https://themeforest.net/user/pixinvent/portfolio" target="_blank"
                            class="footer-link me-4">More Themes</a>
                        <a href="https://demos.pixinvent.com/materialize-html-admin-template/documentation/" target="_blank"
                            class="footer-link me-4">Documentation</a>
                        <a href="https://pixinvent.ticksy.com/" target="_blank"
                            class="footer-link d-none d-sm-inline-block">Support</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- / Footer -->

        <div class="content-backdrop fade"></div>
    </div>

    @foreach ($barangs as $data)
        <div class="modal fade" id="modalCenter-{{ $data->slug }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                <div class="modal-content">
                    <div class="modal-body p-0">

                        {{-- Header --}}
                        <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class="icon-base ri ri-eye-line icon-28px"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-semibold">Detail Barang</h5>
                                    <small class="text-muted">Informasi lengkap barang.</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="px-5 py-4">

                            {{-- Foto --}}
                            <div class="d-flex justify-content-center mb-4">
                                <img src="{{ Storage::url($data->foto) }}" alt="Foto {{ $data->nama }}"
                                    class="rounded-3 shadow-sm" style="width:120px; height:120px; object-fit:cover;">
                            </div>

                            {{-- Badge kondisi --}}
                            <div class="d-flex justify-content-center mb-4">
                                @php
                                    $kondisiClass = match ($data->kondisi) {
                                        'Baru' => 'bg-label-success',
                                        'Bekas' => 'bg-label-warning',
                                        'Rusak' => 'bg-label-danger',
                                        default => 'bg-label-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $kondisiClass }} rounded-pill px-3 py-2">
                                    {{ $data->kondisi }}
                                </span>
                            </div>

                            {{-- Section: Informasi Barang --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3"
                                style="font-size:.7rem; letter-spacing:.08em;">
                                <i class="bx bx-info-circle me-1"></i> Informasi Barang
                            </p>

                            <div class="row g-4 mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-medium text-muted">Nama Barang</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i
                                                class="menu-icon icon-base ri ri-archive-2-line"></i></span>
                                        <input type="text" class="form-control" value="{{ $data->nama }}"
                                            disabled />
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-muted">Jenis Barang</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i
                                                class="menu-icon icon-base ri ri-purchase-tag"></i></span>
                                        <input type="text" class="form-control" value="{{ $data->jenis_barang }}"
                                            disabled />
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-muted">Kategori</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i
                                                class="menu-icon icon-base ri ri-function-line"></i></span>
                                        <input type="text" class="form-control"
                                            value="{{ $data->kategori->nama ?? 'Tidak ada kategori' }}" disabled />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-medium text-muted">Deskripsi</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text align-items-start pt-3">
                                            <i class="menu-icon icon-base ri ri-file-text-line"></i>
                                        </span>
                                        <textarea class="form-control" style="height:80px" disabled>{{ $data->deskripsi }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" />

                            {{-- Section: Harga & Stok --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3"
                                style="font-size:.7rem; letter-spacing:.08em;">
                                <i class="bx bx-dollar me-1"></i> Harga & Stok
                            </p>

                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-muted">Harga</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control"
                                            value="{{ number_format($data->harga, 0, ',', '.') }}" disabled />
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-muted">Jumlah</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-layer"></i></span>
                                        <input type="text" class="form-control" value="{{ $data->jumlah }}"
                                            disabled />
                                        <span class="input-group-text">pcs</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Footer --}}
                        <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Tutup
                            </button>
                            {{-- <a href="/admin/barang/{{ $data->slug }}/edit" class="btn btn-warning">
                                <i class="bx bx-edit me-1"></i> Edit Barang
                            </a> --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="modal fade" id="addBarang" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body p-0">

                    {{-- Header --}}
                    <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar flex-shrink-0">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="icon-base ri ri-shopping-bag-line icon-28px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold">Tambah Data Barang</h5>
                                <small class="text-muted">Isi formulir berikut untuk menambahkan barang baru.</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    {{-- Form Body --}}
                    <form id="formAddBarang" action="{{ route('backend.barang.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="px-5 py-4">

                            {{-- Section: Informasi Barang --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3"
                                style="font-size: .7rem; letter-spacing: .08em;">
                                <i class="bx bx-info-circle me-1"></i> Informasi Barang
                            </p>

                            <div class="row g-4 mb-4">
                                {{-- Nama --}}
                                <div class="col-12">
                                    <label class="form-label fw-medium" for="namaBarang">
                                        Nama Barang <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-box"></i></span>
                                        <input type="text" id="namaBarang" name="nama" class="form-control"
                                            placeholder="Contoh: Meja Belajar Kayu" required />
                                    </div>
                                </div>

                                {{-- Jenis & Kategori --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium" for="jenisBarang">Jenis Barang</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-purchase-tag"></i></span>
                                        <select class="form-select" id="jenisBarang" name="jenis_barang">
                                            <option value="Bekas Sekolah">Bekas Sekolah</option>
                                            <option value="Sumbangan Sekolah">Sumbangan Sekolah</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium" for="kategoriBarang">Kategori</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-category"></i></span>
                                        <select class="form-select" id="kategoriBarang" name="kategori">
                                            @foreach ($kategori as $data)
                                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                <div class="col-12">
                                    <label class="form-label fw-medium" for="deskripsiBarang">Deskripsi</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text align-items-start pt-3"><i
                                                class="bx bx-comment"></i></span>
                                        <textarea id="deskripsiBarang" name="deskripsi" class="form-control" style="height: 90px"
                                            placeholder="Jelaskan detail produk dan kondisinya..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" />

                            {{-- Section: Harga, Stok & Kondisi --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3"
                                style="font-size: .7rem; letter-spacing: .08em;">
                                <i class="bx bx-dollar me-1"></i> Harga, Stok & Kondisi
                            </p>

                            <div class="row g-4 mb-4">
                                {{-- Harga --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium" for="hargaBarang">
                                        Harga <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">Rp</span>
                                        {{-- Display input (yang keliatan user) --}}
                                        <input type="text" id="hargaDisplayAdd" class="form-control" placeholder="0"
                                            inputmode="numeric" autocomplete="off" />
                                    </div>
                                    {{-- Hidden input (yang dikirim ke server) --}}
                                    <input type="hidden" id="hargaBarang" name="harga" required />
                                </div>

                                {{-- Jumlah --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium" for="jumlahBarang">
                                        Jumlah <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-layer"></i></span>
                                        <input type="number" id="jumlahBarang" name="jumlah" class="form-control"
                                            placeholder="0" min="0" required />
                                        <span class="input-group-text">pcs</span>
                                    </div>
                                </div>

                                {{-- Kondisi --}}
                                <div class="col-12">
                                    <label class="form-label fw-medium d-block mb-2">Kondisi Barang</label>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <div class="form-check border rounded px-4 py-2 kondisi-opt">
                                            <input class="form-check-input" type="radio" name="kondisi"
                                                id="kondisiBaru" value="Baru" checked>
                                            <label class="form-check-label cursor-pointer" for="kondisiBaru">
                                                <span class="badge bg-label-success">Baru</span>
                                            </label>
                                        </div>
                                        <div class="form-check border rounded px-4 py-2 kondisi-opt">
                                            <input class="form-check-input" type="radio" name="kondisi"
                                                id="kondisiBekas" value="Bekas">
                                            <label class="form-check-label cursor-pointer" for="kondisiBekas">
                                                <span class="badge bg-label-warning">Bekas</span>
                                            </label>
                                        </div>
                                        <div class="form-check border rounded px-4 py-2 kondisi-opt">
                                            <input class="form-check-input" type="radio" name="kondisi"
                                                id="kondisiRusak" value="Rusak">
                                            <label class="form-check-label cursor-pointer" for="kondisiRusak">
                                                <span class="badge bg-label-danger">Rusak</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" />

                            {{-- Section: Foto --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3"
                                style="font-size: .7rem; letter-spacing: .08em;">
                                <i class="bx bx-image me-1"></i> Foto Barang
                            </p>

                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="dropzone needsclick" id="dropzone-add">
                                        <div class="dz-message needsclick">
                                            <div class="d-flex justify-content-center mb-2">
                                                <div class="avatar">
                                                    <span class="avatar-initial rounded-3 bg-label-primary">
                                                        <i class="icon-base ri ri-upload-2-line icon-24px"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="h6 needsclick mb-1">Drag and drop foto di sini</p>
                                            <small class="text-muted d-block mb-2">atau</small>
                                            <span class="btn btn-sm btn-outline-primary needsclick">Browse Foto</span>
                                            <small class="text-muted d-block mt-2">
                                                <i class="bx bx-info-circle me-1"></i> JPG, PNG, WEBP. Maks. 2MB.
                                            </small>
                                        </div>
                                        <div class="fallback">
                                            <input name="foto" type="file" accept="image/*" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Footer --}}
                        <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Simpan Barang
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editBarang" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body p-0">

                    {{-- Header --}}
                    <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar flex-shrink-0">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="icon-base ri ri-edit-box-line icon-28px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold">Edit Data Barang</h5>
                                <small class="text-muted">Ubah informasi barang yang sudah ada.</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    {{-- Form Body --}}
                    <form id="formEditBarang" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="px-5 py-4">

                            {{-- Section: Informasi Barang --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3"
                                style="font-size: .7rem; letter-spacing: .08em;">
                                <i class="bx bx-info-circle me-1"></i> Informasi Barang
                            </p>

                            <div class="row g-4 mb-4">
                                {{-- Nama --}}
                                <div class="col-12">
                                    <label class="form-label fw-medium" for="editNamaBarang">
                                        Nama Barang <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-box"></i></span>
                                        <input type="text" id="editNamaBarang" name="nama" class="form-control"
                                            placeholder="Contoh: Meja Belajar Kayu" required />
                                    </div>
                                </div>

                                {{-- Jenis & Kategori --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium" for="editJenisBarang">Jenis Barang</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-purchase-tag"></i></span>
                                        <select class="form-select" id="editJenisBarang" name="jenis_barang">
                                            <option value="Bekas Sekolah">Bekas Sekolah</option>
                                            <option value="Sumbangan Sekolah">Sumbangan Sekolah</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium" for="editKategoriBarang">Kategori</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-category"></i></span>
                                        <select class="form-select" id="editKategoriBarang" name="kategori">
                                            @foreach ($kategori as $data)
                                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                <div class="col-12">
                                    <label class="form-label fw-medium" for="editDeskripsiBarang">Deskripsi</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text align-items-start pt-3">
                                            <i class="bx bx-comment"></i>
                                        </span>
                                        <textarea id="editDeskripsiBarang" name="deskripsi" class="form-control" style="height: 90px"
                                            placeholder="Jelaskan detail produk dan kondisinya..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" />

                            {{-- Section: Harga, Stok & Kondisi --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3"
                                style="font-size: .7rem; letter-spacing: .08em;">
                                <i class="bx bx-dollar me-1"></i> Harga, Stok & Kondisi
                            </p>

                            <div class="row g-4 mb-4">
                                {{-- Harga --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium" for="hargaDisplayEdit">
                                        Harga <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="hargaDisplayEdit" class="form-control" placeholder="0"
                                            inputmode="numeric" autocomplete="off" />
                                    </div>
                                    <input type="hidden" id="editHargaBarang" name="harga" required />
                                </div>

                                {{-- Jumlah --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium" for="editJumlahBarang">
                                        Jumlah <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-layer"></i></span>
                                        <input type="number" id="editJumlahBarang" name="jumlah" class="form-control"
                                            placeholder="0" min="0" required />
                                        <span class="input-group-text">pcs</span>
                                    </div>
                                </div>

                                {{-- Kondisi --}}
                                <div class="col-12">
                                    <label class="form-label fw-medium d-block mb-2">Kondisi Barang</label>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <div class="form-check border rounded px-4 py-2 kondisi-opt">
                                            <input class="form-check-input" type="radio" name="kondisi"
                                                id="editKondisiBaru" value="Baru">
                                            <label class="form-check-label cursor-pointer" for="editKondisiBaru">
                                                <span class="badge bg-label-success">Baru</span>
                                            </label>
                                        </div>
                                        <div class="form-check border rounded px-4 py-2 kondisi-opt">
                                            <input class="form-check-input" type="radio" name="kondisi"
                                                id="editKondisiBekas" value="Bekas">
                                            <label class="form-check-label cursor-pointer" for="editKondisiBekas">
                                                <span class="badge bg-label-warning">Bekas</span>
                                            </label>
                                        </div>
                                        <div class="form-check border rounded px-4 py-2 kondisi-opt">
                                            <input class="form-check-input" type="radio" name="kondisi"
                                                id="editKondisiRusak" value="Rusak">
                                            <label class="form-check-label cursor-pointer" for="editKondisiRusak">
                                                <span class="badge bg-label-danger">Rusak</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" />

                            {{-- Section: Foto --}}
                            <p class="text-uppercase text-muted fw-semibold mb-3"
                                style="font-size: .7rem; letter-spacing: .08em;">
                                <i class="bx bx-image me-1"></i> Foto Barang
                            </p>

                            <div class="row g-4">
                                <div class="col-12" id="editFotoPreviewWrapper" style="display:none;">
                                    <label class="form-label fw-medium">Foto Saat Ini</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img id="editFotoPreview" src="" alt="Foto Barang" class="rounded"
                                            style="width:64px;height:64px;object-fit:cover;">
                                        <small class="text-muted">Upload foto baru untuk mengganti foto ini.</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="dropzone needsclick" id="dropzone-edit">
                                        <div class="dz-message needsclick">
                                            <div class="d-flex justify-content-center mb-2">
                                                <div class="avatar">
                                                    <span class="avatar-initial rounded-3 bg-label-primary">
                                                        <i class="icon-base ri ri-upload-2-line icon-24px"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="h6 needsclick mb-1">Drag and drop foto di sini</p>
                                            <small class="text-muted d-block mb-2">atau</small>
                                            <span class="btn btn-sm btn-outline-primary needsclick">Browse Foto</span>
                                            <small class="text-muted d-block mt-2">
                                                <i class="bx bx-info-circle me-1"></i> JPG, PNG, WEBP. Maks. 2MB.
                                            </small>
                                        </div>
                                        <div class="fallback">
                                            <input name="foto" type="file" accept="image/*" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Footer --}}
                        <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bx bx-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .kondisi-opt:has(input:checked) {
            border-color: var(--bs-primary) !important;
            background-color: rgba(var(--bs-primary-rgb), .05);
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
    <!--/ Edit User Modal -->
@endsection
@push('script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <!-- Pass Laravel data to JavaScript -->
    <script>
        // Convert Laravel collection to JavaScript array
        const barangsData = @json($barangs);
        // console.log('Barang Data Loaded:', barangsData);
    </script>
    <script src="{{ asset('assets/js/custom/toast.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <!-- Load custom DataTable script -->
    <script src="{{ asset('assets/js/custom/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/custom/barang.js') }}"></script>
    <script src="{{ asset('assets/js/custom/format-rupiah.js') }}"></script>
@endpush
