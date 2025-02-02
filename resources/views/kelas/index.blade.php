@extends('layouts.layout')

@section('title', 'Daftar Kelas')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <!-- Header & Breadcrumb -->
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Manajemen Data Kelas</h4>
                </div>
                <div class="text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Kelas</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Main Card -->
            <div class="card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Kelas</h5>
                    <a href="{{ route('kelas.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>Tambah Kelas
                    </a>
                </div>

                <div class="card-body mt-0">
                    <div class="table-responsive table-card mt-0">
                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th scope="col" width="5%">No</th>
                                    <th scope="col">Nama Kelas</th>
                                    <th scope="col">Tingkat</th>
                                    <th scope="col">Kelas Berikutnya</th>
                                    <th scope="col">Total Murid</th>
                                    <th scope="col" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kelas as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @php
                                                $tingkatLabels = [
                                                    1 => 'TK',
                                                    2 => 'Kelas 1',
                                                    3 => 'Kelas 2',
                                                    4 => 'Kelas 3',
                                                    5 => 'Kelas 4',
                                                    6 => 'Kelas 5',
                                                    7 => 'Kelas 6'
                                                ];
                                            @endphp
                                            {{ $tingkatLabels[$item->tingkat] ?? $item->tingkat }}
                                        </td>
                                        <td>
                                            @if($item->nextClass)
                                                {{ $item->nextClass->name }} (Tingkat {{ $item->nextClass->tingkat }})
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->siswa->count() }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('kelas.edit', $item->id) }}" 
                                                   class="btn btn-icon btn-sm bg-warning-subtle"
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit">
                                                    <i class="fas fa-edit fs-14 text-warning"></i>
                                                </a>
                                                <form action="{{ route('kelas.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Hapus kelas ini akan menghapus semua siswa terkait. Lanjutkan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-icon btn-sm bg-danger-subtle"
                                                            data-bs-toggle="tooltip" 
                                                            title="Hapus">
                                                        <i class="fas fa-trash fs-14 text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data kelas.</td>
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