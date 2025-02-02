@extends('layouts.layout')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container-fluid">
    <!-- Header with Breadcrumb -->
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Daftar Pembayaran</h4>
        </div>
        <div class="text-sm-end text-center mt-sm-0 mt-2">
            <ol class="breadcrumb m-0 py-0 justify-content-sm-end justify-content-center">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Pembayaran</li>
            </ol>
        </div>
    </div>

    <!-- Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Card Header with Filter -->
                <div class="card-header bg-white">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <h5 class="card-title mb-0">Data Pembayaran</h5>
                        <div class="d-flex flex-column flex-sm-row align-items-stretch gap-2">
                            <form action="{{ route('pembayaran.index') }}" method="GET" 
                                  class="d-flex flex-column flex-sm-row align-items-stretch gap-2">
                                <select name="kelas_id" class="form-select form-select-sm" style="width: 200px;">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="search" class="form-control form-control-sm" 
                                       placeholder="Cari nama siswa..." 
                                       value="{{ request('search') }}"
                                       style="width: 200px;">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-search me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </a>
                                    <a href="{{ route('tagihan.index') }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 50px;">No</th>
                                    <th style="min-width: 150px;">Siswa</th>
                                    <th style="min-width: 100px;">Kelas</th>
                                    <th style="min-width: 120px;">Jenis Biaya</th>
                                    <th style="min-width: 100px;">Bulan</th>
                                    <th style="min-width: 120px;">Jumlah</th>
                                    <th style="min-width: 100px;">Metode</th>
                                    <th style="min-width: 120px;">Tanggal</th>
                                    <th style="min-width: 100px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembayarans as $index => $pembayaran)
                                    <tr>
                                        <td class="text-center">{{ $pembayarans->firstItem() + $index }}</td>
                                        <td>{{ $pembayaran->siswa->name }}</td>
                                        <td>{{ $pembayaran->siswa->kelas->name }}</td>
                                        <td>{{ $pembayaran->jenis_biaya }}</td>
                                        <td>{{ $pembayaran->bulan_hijri ?? '-' }}</td>
                                        <td class="text-end">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $pembayaran->metode_pembayaran === 'cash' ? 'success' : ($pembayaran->metode_pembayaran === 'cicilan' ? 'warning' : 'info') }}">
                                                {{ ucfirst($pembayaran->metode_pembayaran) }}
                                            </span>
                                        </td>
                                        <td>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $pembayaran->metode_pembayaran === 'cash' ? 'success' : 'primary' }}">
                                                {{ $pembayaran->metode_pembayaran === 'cash' ? 'Lunas' : 'Via ' . ucfirst($pembayaran->metode_pembayaran) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <img src="{{ asset('empty-state.svg') }}" class="img-fluid mb-3" style="max-width: 200px;">
                                            <p class="text-muted">Tidak ada data pembayaran.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination Footer -->
                @if($pembayarans->hasPages())
                    <div class="card-footer">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                            <div>
                                <small class="text-muted">
                                    Showing {{ $pembayarans->firstItem() }} to {{ $pembayarans->lastItem() }}
                                    of {{ $pembayarans->total() }} entries
                                </small>
                            </div>
                            {{ $pembayarans->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection