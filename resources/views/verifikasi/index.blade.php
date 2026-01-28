@extends('layouts.kerangkabackend')
@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Persetujuan Verifikasi User</h5>
                <span class="badge bg-label-warning">Menunggu Persetujuan</span>
            </div>
            
            <div class="table-responsive text-nowrap m-3">
                <table class="table" id="myTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No Telp</th>
                            <th>Tgl Lahir</th>
                            <th>Dokumen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        @foreach($user->datadiri as $datadiri)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $user->nama_lengkap }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $datadiri->no_telp ?? '-' }}</td>
                            <td>{{ $datadiri->tanggal_lahir ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewKtp-{{ $user->id }}">
                                    <i class="bx bx-show me-1"></i> Lihat KTP
                                </button>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('backend.verifikasi.approve', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bx bx-check me-1"></i> ACC
                                        </button>
                                    </form>

                                    <form action="{{ route('backend.verifikasi.reject', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bx bx-x me-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="viewKtp-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Dokumen Verifikasi: {{ $user->nama_lengkap }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        @if($user->datadiri && $datadiri->foto_dokumen)
                                            <img src="{{ asset('storage/' . $datadiri->foto_dokumen) }}" class="img-fluid rounded" alt="KTP User">
                                            <div class="mt-3 text-start">
                                                <h6>Alamat:</h6>
                                                <p>{{ $datadiri->alamat }}</p>
                                            </div>
                                        @else
                                            <p class="text-danger">Foto dokumen tidak ditemukan.</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<script>
    let table = new DataTable('#myTable');
</script>
@endsection