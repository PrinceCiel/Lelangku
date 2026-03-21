@extends('layouts.kerangkabackend')
@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
@endsection
@section('content')

    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Basic Bootstrap Table -->
            <div class="card">
                <div class="card-datatable text-nonwrap">
                    <div id="DataTables_Table_0_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">
                        <div class="row card-header flex-column flex-md-row pb-0">
                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
                                <h5 class="card-title mb-0 text-md-start text-center">DataTable with Buttons</h5>
                            </div>
                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mt-0">
                                <div class="dt-buttons btn-group flex-wrap mb-0">
                                    <a class="btn create-new btn-primary" tabindex="0" aria-controls="DataTables_Table_0"
                                        type="button" fdprocessedid="kb7gug" href="{{ route('backend.barang.create') }}">
                                        <span>
                                            <span class="d-flex align-items-center gap-2">
                                                <i class="icon-base bx bx-plus icon-sm"></i>
                                                <span class="d-none d-sm-inline-block">Add New Record</span>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap m-3">
                        <table class="table" id="myTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jenis Barang</th>
                                    <th>Harga</th>
                                    <th>Kondisi</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($barangs as $data)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->jenis_barang }}</td>
                                        <td>{{ $data->harga }}</td>
                                        <td><span
                                                class="badge @if ($data->kondisi == 'Baru') bg-label-success @elseif($data->kondisi == 'Rusak') bg-label-danger @elseif($data->kondisi == 'Bekas') bg-label-warning @endif me-1">{{ $data->kondisi }}</span>
                                        </td>
                                        <td>{{ $data->kategori->nama ?? 'Tidak ada kategori' }}</td>
                                        <td>{{ $data->jumlah }}</td>
                                        <td>
                                            <form action="">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modalCenter-{{ $data->slug }}">
                                                    Show
                                                </button>
                                                <a class="btn btn-warning"
                                                    href="{{ route('backend.barang.edit', $data->slug) }}"><i
                                                        class="icon-base bx bx-edit-alt me-1"></i></a>
                                                <a class="btn btn-danger"
                                                    href="{{ route('backend.barang.destroy', $data->id) }}"
                                                    data-confirm-delete="true"><i
                                                        class="icon-base bx bx-trash me-1"></i></a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/ Nested table -->
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
                        <a href="https://themeselection.com/item/category/admin-templates/" target="_blank"
                            class="footer-link me-4">Admin Templates</a>

                        <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                        <a href="https://themeselection.com/item/category/bootstrap-admin-templates/" target="_blank"
                            class="footer-link me-4">Bootstrap Dashboard</a>

                        <a href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/"
                            target="_blank" class="footer-link me-4">Documentation</a>

                        <a href="https://github.com/themeselection/sneat-bootstrap-html-admin-template-free/issues"
                            target="_blank" class="footer-link">Support</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- / Footer -->

        <div class="content-backdrop fade"></div>
    </div>

    @foreach ($barangs as $data)
        <div class="modal fade" id="modalCenter-{{ $data->slug }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col text-center">
                                <img src="{{ Storage::url($data->foto) }}" alt="Foto Barang"
                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label for="nameWithTitle" class="form-label">Nama Barang</label>
                                <input type="text" id="nameWithTitle" class="form-control" placeholder=""
                                    value="{{ $data->nama }}" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label for="nameWithTitle" class="form-label">Jenis Barang</label>
                                <input type="text" id="nameWithTitle" class="form-control" placeholder=""
                                    value="{{ $data->jenis_barang }}" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label for="nameWithTitle" class="form-label">Kategori Barang</label>
                                <input type="text" id="nameWithTitle" class="form-control" placeholder=""
                                    value="{{ $data->kategori->nama }}" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label for="nameWithTitle" class="form-label">Harga</label>
                                <input type="text" id="nameWithTitle" class="form-control" placeholder=""
                                    value="{{ $data->harga }}" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label for="nameWithTitle" class="form-label">Jumlah</label>
                                <input type="text" id="nameWithTitle" class="form-control" placeholder=""
                                    value="{{ $data->jumlah }}" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label class="form-label" for="basic-icon-default-message">Deskripsi</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-message2" class="input-group-text"><i
                                            class="icon-base bx bx-comment"></i></span>
                                    <textarea id="basic-icon-default-message" class="form-control"
                                        placeholder="Deskripsi Produk (Detail Produk dan Detail Kondisi)"
                                        aria-label="Hi, Do you have a moment to talk Joe?" aria-describedby="basic-icon-default-message2"
                                        name="deskripsi" disabled>{{ $data->deskripsi }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label for="nameWithTitle" class="form-label">Kondisi Barang</label>
                                <input type="text" id="nameWithTitle" class="form-control" placeholder=""
                                    value="{{ $data->kondisi }}" disabled />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('script')
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable');
    </script>
@endsection
