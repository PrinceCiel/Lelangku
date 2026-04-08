@extends('layouts.kerangkafrontend')
@section('content')

{{-- ============= Hero Section ============= --}}
<div class="hero-section">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><span>Ajukan Barang</span></li>
        </ul>
    </div>
    <div class="bg_img hero-bg bottom_center"
         data-background="{{ asset('sbidu/assets/images/banner/hero-bg.png') }}"></div>
</div>

{{-- ============= Form Section ============= --}}
<section class="contact-section padding-bottom">
    <div class="container">
        <div class="contact-wrapper padding-top padding-bottom mt--100 mt-lg--440">

            <div class="section-header" data-aos="zoom-out-down" data-aos-duration="1200">
                <h5 class="cate">Jual Barangmu</h5>
                <h2 class="title">Ajukan Barang</h2>
                <p>Isi form di bawah ini. Admin kami akan menghubungi kamu untuk proses verifikasi dan penawaran harga.</p>
            </div>

            {{-- Alert Success --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Alert Error --}}
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Oops!</strong> Ada beberapa kesalahan:
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="contact-form" id="submission_form"
                  action="{{ route('submissions.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                {{-- ── SECTION 1: Info Barang ─────────────────────────────────── --}}
                <div class="submission-section-title mb-3">
                    <i class="fas fa-box-open me-2"></i> <strong>Informasi Barang</strong>
                </div>

                {{-- Nama Barang --}}
                <div class="form-group">
                    <label for="nama_barang"><i class="fas fa-tag"></i></label>
                    <input type="text"
                           name="nama_barang"
                           id="nama_barang"
                           placeholder="Nama Barang (contoh: Kamera Canon EOS M50)"
                           value="{{ old('nama_barang') }}"
                           class="{{ $errors->has('nama_barang') ? 'is-invalid' : '' }}">
                </div>

                {{-- Kategori Barang --}}
                <div class="form-group-select mb-3">
                    <label class="field-label" for="kategori">
                        <i class="fas fa-list me-2"></i> Kategori Barang
                    </label>
                    <select name="kategori" id="kategori"
                        class="select2 form-select {{ $errors->has('kategori') ? 'is-invalid' : '' }}">
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}" {{ old('kategori') == $k->nama ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label for="deskripsi" class="message"><i class="fas fa-align-left"></i></label>
                    <textarea name="deskripsi"
                              id="deskripsi"
                              placeholder="Deskripsikan barang kamu: kondisi, kelengkapan, tahun beli, alasan jual, dll. (min. 20 karakter)"
                              class="{{ $errors->has('deskripsi') ? 'is-invalid' : '' }}">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Harga Ditawarkan --}}
                <div class="form-group">
                    <label for="harga_ditawarkan"><i class="fas fa-money-bill-wave"></i></label>
                    <input type="number"
                           name="harga_ditawarkan"
                           id="harga_ditawarkan"
                           placeholder="Harga yang kamu tawarkan (Rp)"
                           value="{{ old('harga_ditawarkan') }}"
                           min="1000"
                           class="{{ $errors->has('harga_ditawarkan') ? 'is-invalid' : '' }}">
                </div>

                {{-- Foto Barang --}}
                <div class="form-group flex-column align-items-start gap-2" style="padding: 20px;">
                    <label class="label-foto" for="foto_barang">
                        <strong>📷 Foto Barang</strong>
                        <small class="text-muted ms-2">(Maks. 5 foto, format JPG/PNG/WEBP, maks. 3MB/foto)</small>
                    </label>

                    {{-- Preview Area --}}
                    <div id="foto-preview" class="d-flex flex-wrap gap-2 mb-2"></div>

                    {{-- Upload Zone --}}
                    <div id="foto-dropzone" class="foto-dropzone">
                        <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-muted"></i>
                        <p class="mb-1 text-muted">Klik atau drag & drop foto di sini</p>
                        <small class="text-muted">Maksimal 5 foto</small>
                        <input type="file"
                               name="foto_barang[]"
                               id="foto_barang"
                               accept="image/jpg,image/jpeg,image/png,image/webp"
                               multiple
                               style="opacity:0; position:absolute; inset:0; cursor:pointer;">
                    </div>
                    @error('foto_barang')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    @error('foto_barang.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- ── SECTION 2: Kontak ──────────────────────────────────────── --}}
                <div class="submission-section-title mb-3 mt-4">
                    <i class="fas fa-address-card me-2"></i> <strong>Informasi Kontak</strong>
                    <small class="text-muted ms-2">Untuk dihubungi admin saat review barang</small>
                </div>

                {{-- WhatsApp --}}
                <div class="form-group">
                    <label for="nomor_whatsapp"><i class="fab fa-whatsapp"></i></label>
                    <input type="text"
                           name="nomor_whatsapp"
                           id="nomor_whatsapp"
                           placeholder="Nomor WhatsApp aktif (contoh: 08123456789)"
                           value="{{ old('nomor_whatsapp') }}"
                           class="{{ $errors->has('nomor_whatsapp') ? 'is-invalid' : '' }}">
                </div>

                {{-- Telepon --}}
                <div class="form-group">
                    <label for="nomor_telepon"><i class="fas fa-phone-alt"></i></label>
                    <input type="text"
                           name="nomor_telepon"
                           id="nomor_telepon"
                           placeholder="Nomor telepon (boleh sama dengan WA)"
                           value="{{ old('nomor_telepon') }}"
                           class="{{ $errors->has('nomor_telepon') ? 'is-invalid' : '' }}">
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label for="alamat_lengkap" class="message"><i class="fas fa-map-marker-alt"></i></label>
                    <textarea name="alamat_lengkap"
                              id="alamat_lengkap"
                              placeholder="Alamat lengkap untuk pengecekan fisik (Jalan, RT/RW, Kelurahan, Kecamatan, Kota)"
                              class="{{ $errors->has('alamat_lengkap') ? 'is-invalid' : '' }}">{{ old('alamat_lengkap') }}</textarea>
                </div>

                {{-- Info Box --}}
                <div class="info-box-submission mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Proses selanjutnya:</strong>
                        <ol class="mb-0 mt-1 ps-3">
                            <li>Admin menghubungi kamu via WhatsApp / telepon</li>
                            <li>Pengecekan fisik (area kota) atau virtual (luar kota dalam pulau)</li>
                            <li>Negosiasi harga & deal</li>
                            <li>Pembayaran dari platform ke kamu</li>
                            <li>Barang masuk ke lelang platform</li>
                        </ol>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="form-group text-center mb-0">
                    <button type="submit" class="custom-button" id="btn-submit">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.form-group {
    position: relative;
}
.form-group input,
.form-group textarea,
.form-group select {
    padding-left: 50px;
}
.contact-wrapper {
    padding-left: 30px;
    padding-right: 30px;
}
.section-header {
    text-align: center;
    margin-bottom: 40px;
}
.section-header .title {
    margin: 10px 0;
}

/* ── Kategori: label teks di atas, select di bawah, tidak ada konflik ── */
.form-group-select {
    margin-bottom: 16px;
}
.form-group-select .field-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: inherit;
    margin-bottom: 8px;
    padding: 0;
    position: static;
    width: auto;
    height: auto;
    background: none;
    transform: none;
}
.form-group-select .select2-container {
    width: 100% !important;
}
.select2-container .select2-selection--single {
    height: 55px !important;
    padding-left: 16px !important;
    border-radius: 6px !important;
    border: 1px solid #ddd !important;
    display: flex !important;
    align-items: center !important;
    box-shadow: none !important;
    background: transparent !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: normal !important;
    padding-left: 0 !important;
    color: inherit;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 55px !important;
    top: 0 !important;
}

/* ── Label foto ── */
.label-foto {
    display: block !important;
    position: static !important;
    width: auto !important;
    height: auto !important;
    background: none !important;
    color: inherit !important;
    font-size: 14px !important;
    padding: 0 0 8px 0 !important;
    margin: 0 !important;
    transform: none !important;
    border-radius: 0 !important;
    cursor: default !important;
}
.label-foto strong { font-weight: 600; }
.label-foto small { font-size: 12px; color: #888; margin-left: 6px; }

.submission-section-title {
    padding: 12px 16px;
    background: rgba(var(--primary-rgb, 108, 99, 255), 0.08);
    border-left: 4px solid var(--primary-color, #6c63ff);
    border-radius: 4px;
    font-size: 15px;
}

.foto-dropzone {
    position: relative;
    border: 2px dashed #ccc;
    border-radius: 12px;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.3s, background 0.3s;
    background: #fafafa;
    min-width: 100%;
}
.foto-dropzone:hover,
.foto-dropzone.dragover {
    border-color: var(--primary-color, #6c63ff);
    background: rgba(var(--primary-rgb, 108, 99, 255), 0.05);
}

#foto-preview .preview-item {
    position: relative;
    width: 90px;
    height: 90px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid #e0e0e0;
}
#foto-preview .preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
#foto-preview .preview-item .remove-foto {
    position: absolute;
    top: 3px;
    right: 3px;
    background: rgba(220,53,69,0.85);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    font-size: 11px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-box-submission {
    background: #fff8e1;
    border: 1px solid #ffe082;
    border-radius: 10px;
    padding: 16px 20px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
    color: #5d4037;
}
.info-box-submission i {
    color: #f9a825;
    margin-top: 2px;
    flex-shrink: 0;
}
</style>
@endpush
@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputFile  = document.getElementById('foto_barang');
    const preview    = document.getElementById('foto-preview');
    const dropzone   = document.getElementById('foto-dropzone');
    const form       = document.getElementById('submission_form');
    const MAX_PHOTOS = 5;
    let selectedFiles = [];

    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('dragover'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        handleFiles(Array.from(e.dataTransfer.files));
    });

    inputFile.addEventListener('change', function () {
        handleFiles(Array.from(this.files));
        this.value = '';
    });

    function handleFiles(files) {
        const imageFiles = files.filter(f => f.type.startsWith('image/'));
        const remaining  = MAX_PHOTOS - selectedFiles.length;

        if (remaining <= 0) {
            alert('Maksimal ' + MAX_PHOTOS + ' foto.');
            return;
        }

        imageFiles.slice(0, remaining).forEach(file => {
            if (file.size > 3 * 1024 * 1024) {
                alert(`File "${file.name}" melebihi 3MB.`);
                return;
            }
            selectedFiles.push(file);
        });

        renderPreviews();
    }

    function renderPreviews() {
        preview.innerHTML = '';
        selectedFiles.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = e => {
                const item = document.createElement('div');
                item.className = 'preview-item';
                item.innerHTML = `
                    <img src="${e.target.result}" alt="preview">
                    <button type="button" class="remove-foto" data-idx="${idx}" title="Hapus">
                        <i class="fas fa-times"></i>
                    </button>`;
                preview.appendChild(item);
            };
            reader.readAsDataURL(file);
        });

        const hint = dropzone.querySelector('p');
        if (hint) hint.textContent = selectedFiles.length >= MAX_PHOTOS
            ? `Sudah ${MAX_PHOTOS} foto (maksimal)`
            : `${selectedFiles.length}/${MAX_PHOTOS} foto terpilih — klik atau drag untuk tambah`;
    }

    preview.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-foto');
        if (!btn) return;
        const idx = parseInt(btn.dataset.idx);
        selectedFiles.splice(idx, 1);
        renderPreviews();
    });

    // ✅ Intercept submit — bangun FormData manual biar foto pasti ke-attach
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        // Hapus entry foto_barang[] dari FormData bawaan (kalau ada)
        formData.delete('foto_barang[]');

        // Append selectedFiles satu-satu
        selectedFiles.forEach(file => {
            formData.append('foto_barang[]', file);
        });

        // Submit manual via fetch / XMLHttpRequest, atau bisa pakai trick hidden submit
        // Pakai cara paling kompatibel: temporary form submit via fetch
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        }).then(response => {
            // Kalau server redirect (302), ikuti redirect-nya
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                // Kalau ada response HTML (error validasi), replace halaman
                return response.text().then(html => {
                    document.open();
                    document.write(html);
                    document.close();
                    window.history.replaceState({}, '', form.action);
                });
            }
        }).catch(err => {
            console.error('Submit error:', err);
            alert('Terjadi kesalahan, coba lagi.');
        });
    });

    const hargaInput = document.getElementById('harga_ditawarkan');
    hargaInput.addEventListener('input', function () {
        if (this.value < 0) this.value = 0;
    });
});
$(document).ready(function() {
    $('#kategori').select2({
        placeholder: "-- Pilih Kategori --",
        width: '100%'
    });
});
</script>
@endpush
