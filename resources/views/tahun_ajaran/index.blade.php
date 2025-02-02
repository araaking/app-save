@extends('layouts.layout')

@section('title', 'Kelola Tahun Ajaran')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <!-- Header & Breadcrumb -->
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Manajemen Tahun Ajaran</h4>
                </div>
                <div class="text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Tahun Ajaran</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                    @if(session('promotion_info'))
                        <hr>
                        <div class="mt-2">
                            <strong>Detail Kenaikan Kelas:</strong>
                            <ul class="mb-0">
                                @foreach(session('promotion_info') as $info)
                                    <li>{{ $info }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif

            <!-- System Warning -->
            <div class="alert alert-warning mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Sistem otomatis:
                <ul class="mt-2 mb-0">
                    <li>Tahun ajaran baru akan menggantikan status aktif tahun sebelumnya</li>
                    <li>Tahun aktif tidak bisa dinonaktifkan secara manual</li>
                </ul>
            </div>

            <!-- Main Card -->
            <div class="card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Tahun Ajaran</h5>
                    <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>Tambah Tahun Ajaran
                    </a>
                </div>

                <div class="card-body mt-0">
                    <div class="table-responsive table-card mt-0">
                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th scope="col" width="5%" class="cursor-pointer">No</th>
                                    <th scope="col" class="cursor-pointer">Tahun Ajaran</th>
                                    <th scope="col" class="cursor-pointer">Status</th>
                                    <th scope="col" width="25%" class="cursor-pointer">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tahunAjaran as $key => $tahun)
                                    <tr>
                                        <td class="align-middle">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ $tahun->year_name }}</td>
                                        <td class="align-middle">
                                            @if($tahun->is_active)
                                                <span class="badge bg-success-subtle text-success fw-semibold">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary fw-semibold">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex gap-2">
                                                @if($tahun->is_active)
                                                    <button class="btn btn-icon btn-sm bg-warning-subtle" disabled
                                                        data-bs-toggle="tooltip" title="Tahun aktif tidak dapat diedit">
                                                        <i class="fas fa-edit fs-14 text-warning"></i>
                                                    </button>
                                                @else
                                                    <a href="{{ route('tahun-ajaran.edit', $tahun->id) }}" 
                                                       class="btn btn-icon btn-sm bg-warning-subtle"
                                                       data-bs-toggle="tooltip" 
                                                       title="Edit">
                                                        <i class="fas fa-edit fs-14 text-warning"></i>
                                                    </a>
                                                @endif

                                                <form action="{{ route('tahun-ajaran.destroy', $tahun->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-icon btn-sm bg-danger-subtle"
                                                            data-bs-toggle="tooltip" 
                                                            title="Hapus"
                                                            {{ $tahun->is_active ? 'disabled' : '' }}
                                                            onclick="return confirm('Hapus tahun ajaran ini akan MENGHAPUS SELURUH SISWA di tahun ini. Yakin?')">
                                                        <i class="fas fa-trash fs-14 text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data tahun ajaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection