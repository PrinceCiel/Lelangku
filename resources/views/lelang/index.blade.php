@extends('layouts.kerangkabackend')
@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.min.css">
<style>
    /* Filter Section Styling */
    
    .filter-section {
        background: #1b4849;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .filter-section .form-label {
        color: white;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .filter-section .form-control {
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    
    .filter-section .form-control:focus {
        border-color: white;
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        background-color: rgba(255, 255, 255, 0.95);
    }
    
    .filter-section .form-control[type="date"] {
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 10px 15px;
        transition: all 0.3s ease;
        color: white; /* Tambahkan ini */
        background-color: rgba(255, 255, 255, 0.1); /* Tambahkan ini */
    }

    .filter-section .form-control[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1); /* Biar icon kalender keliatan */
        cursor: pointer;
    }

    .filter-section .form-control[type="date"]:focus {
        border-color: white;
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        background-color: rgba(255, 255, 255, 0.95);
        color: #495057; /* Warna text saat focus */
    }

    .filter-section .btn-reset {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid white;
        color: white;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
        height: 46px;
    }
    
    .filter-section .btn-reset:hover {
        background: white;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
    }

    /* Export Buttons Styling */
    .export-buttons-wrapper {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #e3e6f0;
    }
    
    .export-buttons-wrapper h6 {
        margin: 0 0 12px 0;
        color: #5a5c69;
        font-weight: 600;
        font-size: 14px;
    }
    
    #exportButtons .dt-button {
        margin-right: 8px !important;
        margin-bottom: 8px !important;
        border-radius: 8px !important;
        padding: 8px 16px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        border: none !important;
    }
    
    #exportButtons .dt-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    /* Table Styling */
    .table-responsive {
        border-radius: 8px;
    }
    
    #myTable {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    #myTable thead th {
        background: #1b4849;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 15px 12px;
        border: none;
        white-space: nowrap;
    }
    
    #myTable thead th:first-child {
        border-top-left-radius: 8px;
    }
    
    #myTable thead th:last-child {
        border-top-right-radius: 8px;
    }
    
    #myTable tbody tr {
        transition: all 0.3s ease;
    }
    
    #myTable tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    #myTable tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        color: #495057;
    }

    /* Badge Styling */
    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 11px;
        letter-spacing: 0.3px;
    }
    
    .bg-label-success {
        background-color: #d4edda !important;
        color: #155724 !important;
        border: 1px solid #c3e6cb;
    }
    
    .bg-label-danger {
        background-color: #f8d7da !important;
        color: #721c24 !important;
        border: 1px solid #f5c6cb;
    }
    
    .bg-label-warning {
        background-color: #fff3cd !important;
        color: #856404 !important;
        border: 1px solid #ffeeba;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
        justify-content: flex-start;
    }
    /* Add New Button */
    .btn-primary2 {
        background: #1b4849;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary2:hover {
        transform: translateY(-2px);
        box-shadow: #1b4849;
        background: #171d1c;
    }

    .btn-primary2:active {
        transform: scale(0.95);
        background-color: #1b4849;
    }
    
    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
    }
    
    .modal-header {
        background: #1b4849;
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 20px 25px;
        border-bottom: none;
    }
    
    .modal-title {
        color: white;
        font-weight: 700;
        font-size: 20px;
    }
    
    .modal-body {
        padding: 30px 25px;
    }
    
    .modal-body .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .modal-body .form-control:disabled {
        background-color: #f8f9fa;
        border-color: #e3e6f0;
        color: #495057;
    }
    
    .modal-footer {
        padding: 15px 25px;
        border-top: 1px solid #e3e6f0;
    }
    
    .modal-body .img-fluid {
        border: 3px solid #e3e6f0;
        padding: 5px;
        background: white;
    }

    /* DataTable Custom Styling */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px;
        border: 1px solid #d1d3e2;
        padding: 6px 12px;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px !important;
        margin: 0 3px;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #1b4849;
        border-color: #667eea !important;
        color: white !important;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .filter-section {
            padding: 20px 15px;
        }
        
        .card-title {
            font-size: 18px;
        }
        
        #myTable thead th,
        #myTable tbody td {
            font-size: 12px;
            padding: 10px 8px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <!-- Header -->
            <div class="row card-header flex-column flex-md-row">
                <div class="col-md-6 d-flex align-items-center">
                    <h5 class="card-title">Data Lelang</h5>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end justify-content-start mt-3 mt-md-0">
                    <a class="btn btn-primary2" href="{{ route('backend.lelang.create') }}">
                        <i class="bx bx-plus me-2"></i>Tambah Lelang Baru
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Filter Tanggal -->
                <div class="filter-section">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label">📅 Dari Tanggal</label>
                            <input type="date" id="minDate" class="form-control" placeholder="Pilih tanggal awal">
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label">📅 Sampai Tanggal</label>
                            <input type="date" id="maxDate" class="form-control" placeholder="Pilih tanggal akhir">
                        </div>
                        <div class="col-md-4">
                            <button id="resetFilter" class="btn btn-reset w-100">
                                <i class="bx bx-reset me-2"></i>Reset Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Export Buttons -->
                <div class="export-buttons-wrapper">
                    <h6><i class="bx bx-download me-2"></i>Ekspor Data</h6>
                    <div id="exportButtons"></div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="myTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Lelang</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Berakhir</th>
                                <th style="width: 100px;">Status</th>
                                <th>Kode Lelang</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lelangs as $data)
                            <tr>
                                <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                    {{ $data->barang->nama }}
                                    </div>
                                </td>
                                <td>
                                    <i class="bx bx-calendar me-1 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($data->jadwal_mulai)->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <i class="bx bx-calendar-check me-1 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($data->jadwal_berakhir)->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <span class="badge @if($data->status == 'dibuka') bg-label-success @elseif($data->status == 'selesai') bg-label-danger @elseif($data->status == 'ditutup') bg-label-warning @endif">
                                        {{ ucfirst($data->status) }}
                                    </span>
                                </td>
                                <td>
                                    <code class="text-primary">{{ $data->kode_lelang }}</code>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalCenter-{{ $data->slug }}" title="Lihat Detail">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        @if($data->status == 'ditutup')
                                            <a class="btn btn-sm btn-warning" href="{{ route('backend.lelang.edit', $data->id) }}" title="Edit">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <a class="btn btn-sm btn-danger" href="{{ route('backend.lelang.destroy', $data->id) }}" data-confirm-delete="true" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        @elseif($data->status == 'selesai')
                                            <a class="btn btn-sm btn-danger" href="{{ route('backend.lelang.destroy', $data->id) }}" data-confirm-delete="true" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="content-footer footer bg-footer-theme mt-4">
        <div class="container-xxl">
            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    © <script>document.write(new Date().getFullYear());</script>, made with ❤️ by 
                    <a href="https://themeselection.com" target="_blank" class="footer-link fw-bold">ThemeSelection</a>
                </div>
            </div>
        </div>
    </footer>

    <div class="content-backdrop fade"></div>
</div>

<!-- Modal Detail -->
@foreach($lelangs as $data)
<div class="modal fade" id="modalCenter-{{ $data->slug }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-detail me-2"></i>Detail Lelang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <img src="{{ Storage::url($data->barang->foto) }}" 
                             alt="Foto {{ $data->barang->nama }}" 
                             class="img-fluid" 
                             style="max-width: 250px; height: 250px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bx bx-package me-1"></i>Nama Lelang
                        </label>
                        <input type="text" class="form-control" value="{{ $data->barang->nama }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bx bx-barcode me-1"></i>Kode Lelang
                        </label>
                        <input type="text" class="form-control" value="{{ $data->kode_lelang }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bx bx-calendar me-1"></i>Tanggal Mulai
                        </label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($data->jadwal_mulai)->format('d F Y, H:i') }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bx bx-calendar-check me-1"></i>Tanggal Berakhir
                        </label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($data->jadwal_berakhir)->format('d F Y, H:i') }}" disabled>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="bx bx-info-circle me-1"></i>Status
                        </label>
                        <div>
                            <span class="badge @if($data->status == 'dibuka') bg-label-success @elseif($data->status == 'selesai') bg-label-danger @elseif($data->status == 'ditutup') bg-label-warning @endif" style="font-size: 14px; padding: 8px 16px;">
                                {{ ucfirst($data->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('script')
<!-- jQuery harus dimuat PERTAMA -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    // Custom filter untuk tanggal FLEXIBLE
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        let min = $('#minDate').val();
        let max = $('#maxDate').val();
        
        // Ambil tanggal MULAI dari kolom index 2
        let tanggalMulaiStr = data[2] || '';
        // Ambil tanggal BERAKHIR dari kolom index 3
        let tanggalBerakhirStr = data[3] || '';
        
        // Parse tanggal MULAI
        let mulaiMatch = tanggalMulaiStr.match(/(\d{2})\s+(\w+)\s+(\d{4})/);
        // Parse tanggal BERAKHIR
        let berakhirMatch = tanggalBerakhirStr.match(/(\d{2})\s+(\w+)\s+(\d{4})/);
        
        if (!mulaiMatch || !berakhirMatch) return true;
        
        // Konversi bulan dari nama ke angka
        const monthMap = {
            'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'May': 4, 'Jun': 5,
            'Jul': 6, 'Aug': 7, 'Sep': 8, 'Oct': 9, 'Nov': 10, 'Dec': 11,
            'Agu': 7, 'Okt': 9, 'Des': 11, 'Mei': 4
        };
        
        // Format tanggal MULAI ke YYYY-MM-DD
        let mulaiDay = parseInt(mulaiMatch[1]);
        let mulaiMonth = monthMap[mulaiMatch[2]];
        let mulaiYear = parseInt(mulaiMatch[3]);
        let tanggalMulai = mulaiYear + '-' + String(mulaiMonth + 1).padStart(2, '0') + '-' + String(mulaiDay).padStart(2, '0');
        
        // Format tanggal BERAKHIR ke YYYY-MM-DD
        let berakhirDay = parseInt(berakhirMatch[1]);
        let berakhirMonth = monthMap[berakhirMatch[2]];
        let berakhirYear = parseInt(berakhirMatch[3]);
        let tanggalBerakhir = berakhirYear + '-' + String(berakhirMonth + 1).padStart(2, '0') + '-' + String(berakhirDay).padStart(2, '0');
        
        // LOGIC FILTER YANG BENAR:
        // 1. Kalau cuma min yang diisi -> filter EXACT tanggal MULAI = min
        // 2. Kalau cuma max yang diisi -> filter EXACT tanggal BERAKHIR = max
        // 3. Kalau keduanya diisi -> filter RANGE (mulai >= min DAN berakhir <= max)
        
        if (!min && !max) {
            // Tidak ada filter
            return true;
        } else if (min && !max) {
            // Cuma "Dari Tanggal" yang diisi -> filter EXACT tanggal MULAI
            return tanggalMulai === min;
        } else if (!min && max) {
            // Cuma "Sampai Tanggal" yang diisi -> filter EXACT tanggal BERAKHIR
            return tanggalBerakhir === max;
        } else {
            // Keduanya diisi -> filter RANGE
            return tanggalMulai >= min && tanggalBerakhir <= max;
        }
    });

    // Inisialisasi DataTable
    let table = $('#myTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="bx bxs-file-export me-1"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                title: 'Data Lelang - ' + new Date().toLocaleDateString('id-ID')
            },
            {
                extend: 'pdf',
                text: '<i class="bx bxs-file-pdf me-1"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                title: 'Data Lelang',
                orientation: 'landscape',
                pageSize: 'A4',
                customize: function(doc) {
                    doc.styles.title = {
                        fontSize: 18,
                        bold: true,
                        alignment: 'center',
                        margin: [0, 0, 0, 15]
                    };
                    doc.styles.tableHeader = {
                        fillColor: '#667eea',
                        color: 'white',
                        bold: true,
                        alignment: 'center'
                    };
                }
            },
            {
                extend: 'print',
                text: '<i class="bx bx-printer me-1"></i> Print',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                title: 'Data Lelang',
                customize: function(win) {
                    $(win.document.body).css('font-size', '10pt');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', '10pt');
                }
            }
        ],
        order: [[0, 'asc']],
        // language: {
        //     search: "Cari:",
        //     lengthMenu: "Tampilkan _MENU_ data",
        //     zeroRecords: "❌ Data tidak ditemukan",
        //     info: "Halaman _PAGE_ dari _PAGES_",
        //     infoEmpty: "Tidak ada data",
        //     infoFiltered: "(dari _MAX_ total data)",
        //     paginate: {
        //         first: "⏮️",
        //         last: "⏭️",
        //         next: "▶️",
        //         previous: "◀️"
        //     }
        // },
        pageLength: 10,
        responsive: true
    });

    // Pindahkan tombol export
    table.buttons().container().appendTo('#exportButtons');

    // Event listener filter tanggal
    $('#minDate, #maxDate').on('change', function() {
        table.draw();
    });

    // Reset filter
    $('#resetFilter').on('click', function() {
        $('#minDate').val('');
        $('#maxDate').val('');
        table.search('').draw();
        
        // Animasi feedback
        $(this).html('<i class="bx bx-check me-2"></i>Berhasil!');
        setTimeout(() => {
            $(this).html('<i class="bx bx-reset me-2"></i>Reset Filter');
        }, 1500);
    });
});
</script>
@endsection