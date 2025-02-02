@extends('layouts.layout')

@section('title', 'Daftar Siswa')

@section('content')
<div class="container-fluid">
    <!-- Header & Breadcrumb -->
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Manajemen Data Siswa</h4>
        </div>
        <div class="text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Siswa</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Card Utama -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="card-title mb-0">Daftar Siswa</h5>
                    @if($tahunAktif = \App\Models\TahunAjaran::where('is_active', true)->first())
                        <span class="badge bg-success">
                            Tahun Ajaran Aktif: {{ $tahunAktif->year_name }}
                        </span>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('siswa.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <select name="kelas" class="form-select form-select-sm" style="width: 200px;">
                            <option value="">Semua Kelas</option>
                            @foreach($allKelas as $kelas)
                                <option value="{{ $kelas->name }}" {{ request('kelas') == $kelas->name ? 'selected' : '' }}>
                                    {{ $kelas->name }} (Tingkat {{ $kelas->tingkat }})
                                </option>
                            @endforeach
                        </select>

                        <input type="text" name="search" 
                               class="form-control form-control-sm" 
                               placeholder="Cari berdasarkan nama atau NIS..."
                               value="{{ request('search') }}"
                               style="width: 200px;">

                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </form>
                    <a href="{{ route('siswa.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-2"></i> Tambah Siswa
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">

            <!-- Tabel Siswa -->
            <div class="table-responsive table-card mt-0">
                <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                    <thead class="text-muted table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                            <tr>
                                <td>{{ $loop->iteration + ($siswas->currentPage() - 1) * $siswas->perPage() }}</td>
                                <td>{{ $siswa->nis ?? '-' }}</td>
                                <td>{{ $siswa->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $siswa->kelas->name ?? '-' }} (Tingkat {{ $siswa->kelas->tingkat ?? '-' }})
                                    </span>
                                </td>
                                <td>{{ $siswa->academicYear->year_name ?? '-' }}</td>
                                <td>
                                    @if($siswa->status == 'Aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($siswa->status == 'Lulus')
                                        <span class="badge bg-primary">Lulus</span>
                                    @else
                                        <span class="badge bg-danger">Keluar</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('siswa.edit', $siswa->id) }}" 
                                       class="btn btn-icon btn-sm bg-primary-subtle me-1" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                    </a>
                                    <form action="{{ route('siswa.destroy', $siswa->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Hapus siswa ini? Data transaksi terkait juga akan terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-icon btn-sm bg-danger-subtle" 
                                                data-bs-toggle="tooltip" 
                                                title="Hapus">
                                            <i class="mdi mdi-delete fs-14 text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <img src="{{ asset('empty-state.svg') }}" width="200" class="mb-3">
                                    <p class="text-muted">Tidak ada data siswa ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($siswas->hasPages())
                <div class="d-flex justify-content-end mt-3">
                    {{ $siswas->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection