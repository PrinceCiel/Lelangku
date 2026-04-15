@extends('layouts.kerangkabackend')
@section('content')
<div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <form action="{{ route('backend.barang.update', $barang->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
              <!-- Basic Layout -->
              <div class="row mb-12 gy-6">
                <div class="col-xl">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Edit Data Barang</h5>
                      <small class="text-body float-end">Merged input group</small>
                    </div>
                    <div class="card-body">
                      <form>
                        <div class="mb-6">
                          <label class="form-label" for="basic-icon-default-fullname">Nama Barang</label>
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text"
                              ><i class="icon-base bx bx-box"></i
                            ></span>
                            <input
                              type="text"
                              class="form-control"
                              id="basic-icon-default-fullname"
                              placeholder="Nama Barang"
                              aria-label="John Doe"
                              aria-describedby="basic-icon-default-fullname2"
                              name="nama"
                              value="{{ $barang->nama }}"/>
                          </div>
                        </div>
                        <div class="mb-6">
                          <label for="exampleFormControlSelect1" class="form-label">Kategori Barang</label>
                          <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example" name="kategori">
                            @foreach($kategori as $data)
                            <option value="{{ $data->id }}" {{ $data->id == $barang->id_kategori ? 'selected' :''}}>{{ $data->nama }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="mb-6">
                          <label class="form-label" for="basic-icon-default-company">Harga</label>
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-company2" class="input-group-text"
                            ><i class="icon-base bx bx-money"></i
                            ></span>
                            <input
                              type="integer"
                              id="basic-icon-default-company"
                              class="form-control"
                              placeholder="Harga Awal"
                              aria-label="ACME Inc."
                              aria-describedby="basic-icon-default-company2"
                              name="harga"
                              value="{{ $barang->harga}}"/>
                          </div>
                        </div>
                        <div class="mb-6">
                          <label class="form-label" for="basic-icon-default-company">Jumlah</label>
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-company2" class="input-group-text"
                            ><i class="icon-base bx bx-box"></i
                            ></span>
                            <input
                              type="integer"
                              id="basic-icon-default-company"
                              class="form-control"
                              placeholder="Jumlah Barang"
                              aria-label="ACME Inc."
                              aria-describedby="basic-icon-default-company2"
                              name="jumlah"
                              value="{{ $barang->jumlah}}"/>
                          </div>
                        </div>
                        <div class="mb-6">
                          <label class="form-label" for="basic-icon-default-message">Deskripsi</label>
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-message2" class="input-group-text"
                              ><i class="icon-base bx bx-comment"></i
                            ></span>
                            <textarea
                            id="basic-icon-default-message"
                            class="form-control"
                            placeholder="Deskripsi Produk (Detail Produk dan Detail Kondisi)"
                            aria-label="Hi, Do you have a moment to talk Joe?"
                            aria-describedby="basic-icon-default-message2" name="deskripsi">{{ $barang->deskripsi}}</textarea>
                          </div>
                        </div>
                        <div class="mb-6">
                          <label for="exampleFormControlSelect1" class="form-label">Kondisi Barang</label>
                          <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example" name="kondisi">
                            <option value="Baru" {{ $barang->kondisi == 'Baru' ? 'selected' :''}}>Baru</option>
                            <option value="Rusak" {{ $barang->kondisi == 'Rusak' ? 'selected' :''}}>Rusak</option>
                            <option value="Bekas" {{ $barang->kondisi == 'Bekas' ? 'selected' :''}}>Bekas</option>
                          </select>
                        </div>
                        <div class="mb-6">
                          <label for="formFile" class="form-label">Foto Barang</label>
                          <input class="form-control" type="file" id="formFile" name="foto"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              </form>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                  <div class="mb-2 mb-md-0">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    , made with ❤️ by
                    <a href="https://themeselection.com" target="_blank" class="footer-link">ThemeSelection</a>
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <a
                      href="https://themeselection.com/item/category/admin-templates/"
                      target="_blank"
                      class="footer-link me-4"
                      >Admin Templates</a
                    >

                    <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                    <a
                      href="https://themeselection.com/item/category/bootstrap-admin-templates/"
                      target="_blank"
                      class="footer-link me-4"
                      >Bootstrap Dashboard</a
                    >

                    <a
                      href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/"
                      target="_blank"
                      class="footer-link me-4"
                      >Documentation</a
                    >

                    <a
                      href="https://github.com/themeselection/sneat-bootstrap-html-admin-template-free/issues"
                      target="_blank"
                      class="footer-link"
                      >Support</a
                    >
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
@endsection
