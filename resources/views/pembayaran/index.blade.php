@extends('layouts.layout')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container-fluid">
    <!-- Header with Breadcrumb -->
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Daftar Pembayaran</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Pembayaran</li>
            </ol>
        </div>
    </div>

    <!-- Table Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card overflow-hidden">
                <!-- Card Header with Filter -->
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0">Data Pembayaran</h5>
                        <form action="{{ route('pembayaran.index') }}" method="GET" 
                              class="ms-auto d-flex align-items-center gap-2">
                            <!-- Filter Kelas -->
                            <select name="kelas_id" class="form-select form-select-sm" style="width: 150px;">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->name }}
                                    </option>
                                @endforeach
                            </select>
                            <!-- Search -->
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Cari nama siswa..." value="{{ request('search') }}" 
                                   style="width: 200px;">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-undo me-1"></i> Reset
                            </a>
                            <a href="{{ route('tagihan.index') }}" class="btn btn-sm btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Siswa</th>
                                    <th width="10%">Kelas</th>
                                    <th width="15%">Jenis Biaya</th>
                                    <th width="10%">Bulan</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="10%">Metode</th>
                                    <th width="10%">Tanggal</th>
                                    <th width="10%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembayarans as $index => $pembayaran)
                                <tr>
                                    <td>{{ $pembayarans->firstItem() + $index }}</td>
                                    <td>{{ $pembayaran->siswa->name }}</td>
                                    <td>{{ $pembayaran->siswa->kelas->name }}</td>
                                    <td>{{ $pembayaran->jenis_biaya }}</td>
                                    <td>{{ $pembayaran->bulan_hijri ?? '-' }}</td>
                                    <td class="text-end">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($pembayaran->metode_pembayaran === 'cash')
                                            <span class="badge bg-success">Cash</span>
                                        @elseif($pembayaran->metode_pembayaran === 'cicilan')
                                            <span class="badge bg-warning">Cicilan</span>
                                        @else
                                            <span class="badge bg-info">Tabungan</span>
                                        @endif
                                    </td>
                                    <td>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        @if($pembayaran->metode_pembayaran !== 'cash')
                                            <span class="badge bg-primary">Via {{ $pembayaran->metode_pembayaran }}</span>
                                        @else
                                            <span class="badge bg-success">Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-3">Tidak ada data pembayaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination Footer -->
                @if($pembayarans->hasPages())
                <div class="card-footer py-2">
                    <div class="row align-items-center">
                        <div class="col-sm">
                            <div class="text-muted">
                                Showing {{ $pembayarans->firstItem() }} to {{ $pembayarans->lastItem() }}
                                of {{ $pembayarans->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            {{ $pembayarans->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection