{{-- resources/views/admin/submissions/index.blade.php --}}
@extends('layouts.kerangkabackend') {{-- ganti sesuai layout admin lu --}}

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 fw-bold">Pengajuan Barang</h3>
            <small class="text-muted">Review dan proses barang yang diajukan user</small>
        </div>
    </div>

    {{-- Status Counter Cards --}}
    <div class="row g-3 mb-4">
        @php
            $countConfig = [
                'pending'      => ['label' => 'Pending',       'color' => 'warning', 'icon' => 'time-fill'],
                'under_review' => ['label' => 'Ditinjau',      'color' => 'info',    'icon' => 'file-search-fill'],
                'approved'     => ['label' => 'Disetujui',     'color' => 'success', 'icon' => 'checkbox-circle-fill'],
                'rejected'     => ['label' => 'Ditolak',       'color' => 'danger',  'icon' => 'close-circle-fill'],
                'purchased'    => ['label' => 'Sudah Dibeli',  'color' => 'primary', 'icon' => 'shopping-bag-fill'],
            ];
        @endphp
        @foreach($countConfig as $key => $cfg)
        <div class="col-6 col-md-4 col-lg-2-4">
            <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 {{ request('status') === $key ? 'border border-' . $cfg['color'] . ' border-2' : '' }}">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box bg-{{ $cfg['color'] }} bg-opacity-10 text-{{ $cfg['color'] }}">
                            <i class="ri ri-{{ $cfg['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="fs-5 fw-bold">{{ $counts[$key] ?? 0 }}</div>
                            <small class="text-muted">{{ $cfg['label'] }}</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    {{-- Filter + Search --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Cari Barang / User</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama barang atau nama user..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\ItemSubmission::statusList() as $val => $label)
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary button-submit">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('backend.submissions.index') }}" class="btn btn-outline-secondary ms-1 button-submit">Reset</a>
                </div>
            </form>
        </div>
        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Barang</th>
                                <th>User</th>
                                <th>Harga Ditawarkan</th>
                                <th>Harga Deal</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $sub)
                            <tr>
                                <td class="text-muted small">{{ $sub->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if(!empty($sub->foto_barang[0]))
                                            <img src="{{ Storage::url($sub->foto_barang[0]) }}"
                                                 class="rounded" width="45" height="45"
                                                 style="object-fit:cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width:45px;height:45px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ Str::limit($sub->nama_barang, 30) }}</div>
                                            <small class="text-muted">{{ count($sub->foto_barang ?? []) }} foto</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $sub->user->name }}</div>
                                    <small class="text-muted">{{ $sub->user->email }}</small>
                                </td>
                                <td>Rp {{ number_format($sub->harga_ditawarkan, 0, ',', '.') }}</td>
                                <td>
                                    @if($sub->harga_deal)
                                        <span class="text-success fw-semibold">
                                            Rp {{ number_format($sub->harga_deal, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $sub->status_badge }}">
                                        {{ $sub->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $sub->created_at->format('d M Y') }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('backend.submissions.show', $sub) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Review
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada pengajuan yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($submissions->hasPages())
            <div class="card-footer border-0 bg-transparent">
                {{ $submissions->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

<style>
.col-lg-2-4 { flex: 0 0 auto; width: 20%; }
@media (max-width: 992px) { .col-lg-2-4 { width: 33.33%; } }
@media (max-width: 576px) { .col-lg-2-4 { width: 50%; } }

.icon-box {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 16px;
}
.button-submit {
    width: 100px;
    height: 50px;
}
</style>
@endsection
