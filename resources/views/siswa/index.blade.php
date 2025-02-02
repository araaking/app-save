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
        <div class="card-header bg-white d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Daftar Siswa</h5>
            <div class="d-flex gap-2">
                @if($tahunAktif = \App\Models\TahunAjaran::where('is_active', true)->first())
                    <span class="badge bg-success align-self-center">
                        Tahun Ajaran Aktif: {{ $tahunAktif->year_name }}
                    </span>
                @endif
                <a href="{{ route('siswa.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-2"></i> Tambah Siswa
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter -->
gi            <form action="{{ route('siswa.index') }}" method="GET" class="mb-4">
                <div class="row g-2">
                    <div class="col-12 col-md-3">
                        <select name="kelas" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($allKelas as $kelas)
                                <option value="{{ $kelas->name }}" {{ request('kelas') == $kelas->name ? 'selected' : '' }}>
                                    {{ $kelas->name }} (Tingkat {{ $kelas->tingkat }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <input type="text" name="search" 
                               class="form-control" 
                               placeholder="Cari berdasarkan nama atau NIS..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tabel Siswa -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>T.A</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                            <tr>
                                <td class="text-center" style="min-width: 50px;">{{ $loop->iteration + ($siswas->currentPage() - 1) * $siswas->perPage() }}</td>
                                <td style="min-width: 100px;">{{ $siswa->nis ?? '-' }}</td>
                                <td style="min-width: 150px;">{{ $siswa->name }}</td>
                                <td style="min-width: 120px;">
                                    <span class="badge bg-secondary">
                                        {{ $siswa->kelas->name ?? '-' }} ({{ $siswa->kelas->tingkat ?? '-' }})
                                    </span>
                                </td>
                                <td style="min-width: 100px;">{{ $siswa->academicYear->year_name ?? '-' }}</td>
                                <td style="min-width: 80px;">
                                    @if($siswa->status == 'Aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($siswa->status == 'Lulus')
                                        <span class="badge bg-primary">Lulus</span>
                                    @else
                                        <span class="badge bg-danger">Keluar</span>
                                    @endif
                                </td>
                                <td class="text-center" style="min-width: 100px;">
                                    <div class="btn-group">
                                        <a href="{{ route('siswa.edit', $siswa->id) }}" 
                                           class="btn btn-sm btn-outline-warning"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('siswa.destroy', $siswa->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Hapus siswa ini? Data transaksi terkait juga akan terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <img src="{{ asset('empty-state.svg') }}" class="img-fluid mb-3" style="max-width: 200px;">
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