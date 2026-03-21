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
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Kategori Table -->
            <div class="card position-relative">
                <div class="card-toast-wrapper" id="cardToastWrapper"></div>
                <div id="table-loader" class="table-loader-wrapper">
                    <div class="loader"><span class="loader-inner"></span></div>
                </div>
                <div class="card-datatable table-responsive">
                    <table class="datatables-kategori table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Nama Kategori</th>
                                <th>Slug</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>

    {{-- ===== MODAL SHOW ===== --}}
    @foreach ($kategori as $data)
        <div class="modal fade" id="modalShow-{{ $data->slug }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-simple">
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
                                    <h5 class="mb-0 fw-semibold">Detail Kategori</h5>
                                    <small class="text-muted">Informasi lengkap kategori barang.</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="px-5 py-4">

                            {{-- Foto --}}
                            <div class="d-flex justify-content-center mb-4">
                                <img src="{{ Storage::url($data->foto) }}" alt="Foto {{ $data->nama }}"
                                    class="rounded-3 shadow-sm" style="width:120px; height:120px; object-fit:cover;">
                            </div>

                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label fw-medium text-muted">Nama Kategori</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">
                                            <i class="icon-base ri ri-price-tag-3-line"></i>
                                        </span>
                                        <input type="text" class="form-control" value="{{ $data->nama }}" disabled />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium text-muted">Slug</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">
                                            <i class="icon-base ri ri-link-m"></i>
                                        </span>
                                        <input type="text" class="form-control" value="{{ $data->slug }}" disabled />
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Footer --}}
                        <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Tutup
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- ===== MODAL ADD ===== --}}
    <div class="modal fade" id="addKategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple">
            <div class="modal-content">
                <div class="modal-body p-0">

                    {{-- Header --}}
                    <div class="d-flex align-items-center justify-content-between px-5 pt-5 pb-4 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar flex-shrink-0">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="icon-base ri ri-folder-add-line icon-28px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold">Tambah Kategori</h5>
                                <small class="text-muted">Isi formulir untuk menambahkan kategori baru.</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    {{-- Form --}}
                    <form id="formAddKategori" action="{{ route('backend.kategori.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="px-5 py-4">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label fw-medium" for="addNamaKategori">
                                        Nama Kategori <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">
                                            <i class="bx bx-purchase-tag"></i>
                                        </span>
                                        <input type="text" id="addNamaKategori" name="nama" class="form-control"
                                            placeholder="Contoh: Elektronik" required />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-medium">Foto Kategori</label>
                                    <div class="dropzone needsclick" id="dropzone-add-kategori">
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

                        <div class="d-flex justify-content-end gap-2 px-5 py-4 border-top">
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL EDIT ===== --}}
    <div class="modal fade" id="editKategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple">
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
                                <h5 class="mb-0 fw-semibold">Edit Kategori</h5>
                                <small class="text-muted">Ubah informasi kategori yang sudah ada.</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    {{-- Form --}}
                    <form id="formEditKategori" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="px-5 py-4">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label fw-medium" for="editNamaKategori">
                                        Nama Kategori <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">
                                            <i class="bx bx-purchase-tag"></i>
                                        </span>
                                        <input type="text" id="editNamaKategori" name="nama" class="form-control"
                                            placeholder="Contoh: Elektronik" required />
                                    </div>
                                </div>

                                <div class="col-12" id="editFotoPreviewWrapper" style="display:none;">
                                    <label class="form-label fw-medium">Foto Saat Ini</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img id="editFotoPreview" src="" alt="Foto Kategori" class="rounded"
                                            style="width:64px;height:64px;object-fit:cover;">
                                        <small class="text-muted">Upload foto baru untuk mengganti foto ini.</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-medium">Ganti Foto (opsional)</label>
                                    <div class="dropzone needsclick" id="dropzone-edit-kategori">
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

@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <script>
        // Pass data Laravel ke JS
        const kategoriData = @json($kategori);
    </script>
    <script src="{{ asset('assets/js/custom/toast.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/custom/toast.js') }}"></script>
    <script src="{{ asset('assets/js/custom/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/custom/kategori.js') }}"></script>
@endpush
