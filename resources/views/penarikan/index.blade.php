@extends('layouts.layout')

@section('title', 'Daftar Penarikan')

@section('content')
<div class="container-fluid">
    <!-- Header with Breadcrumb -->
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Daftar Penarikan</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Penarikan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Summary Cards -->
            <div class="row mb-4">
                <!-- Total Penarikan Simpanan Card -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="widget-first">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="p-2 border border-success border-opacity-10 bg-success-subtle rounded-2 me-2">
                                        <div class="bg-success rounded-circle widget-size text-center">
                                            <i class="mdi mdi-cash-multiple text-white"></i>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-dark fs-15">Total Penarikan Simpanan</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    @php
                                        $totalPenarikanSimpanan = $penarikan->where('sumber_penarikan', 'simpanan')->sum('jumlah');
                                    @endphp
                                    <h3 class="mb-0 fs-22 text-dark me-3">Rp {{ number_format($totalPenarikanSimpanan, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Penarikan Cicilan Card -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="widget-first">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="p-2 border border-info border-opacity-10 bg-info-subtle rounded-2 me-2">
                                        <div class="bg-info rounded-circle widget-size text-center">
                                            <i class="mdi mdi-bank text-white"></i>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-dark fs-15">Total Penarikan Cicilan</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    @php
                                        $totalPenarikanCicilan = $penarikan->where('sumber_penarikan', 'cicilan')->sum('jumlah');
                                    @endphp
                                    <h3 class="mb-0 fs-22 text-dark me-3">Rp {{ number_format($totalPenarikanCicilan, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Withdrawal List Card -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Penarikan</h5>
                        <div class="d-flex gap-2">
                            <!-- Academic Year Filter -->
                            <form action="{{ route('penarikan.index') }}" method="GET" class="d-flex align-items-center">
                                <select name="tahun_ajaran_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                    @foreach ($allTahunAjaran as $tahun)
                                        <option value="{{ $tahun->id }}" {{ $selectedTahun->id == $tahun->id ? 'selected' : '' }}>
                                            {{ $tahun->year_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            <a href="{{ route('penarikan.create') }}" class="btn btn-sm btn-primary">
                                <i class="mdi mdi-plus me-1"></i> Tambah Penarikan
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive table-card mt-0">
                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Buku Tabungan</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Sumber</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penarikan as $index => $p)
                                <tr>
                                    <td>{{ $penarikan->firstItem() + $index }}</td>
                                    <td>{{ $p->bukuTabungan->nomor_urut }}</td>
                                    <td>{{ $p->bukuTabungan->siswa->name }}</td>
                                    <td>{{ $p->bukuTabungan->siswa->kelas->name }}</td>
                                    <td>{{ ucfirst($p->sumber_penarikan) }}</td>
                                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ $p->tanggal->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $p->keterangan }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('penarikan.edit', $p->id) }}" 
                                               class="btn btn-icon btn-sm bg-primary-subtle me-1" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit">
                                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                            </a>
                                            <form action="{{ route('penarikan.destroy', $p->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-icon btn-sm bg-danger-subtle" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Hapus">
                                                    <i class="mdi mdi-delete fs-14 text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data penarikan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer py-2">
                    <div class="row align-items-center">
                        <div class="col-sm">
                            @if ($penarikan->count() > 0)
                            <div class="text-muted">
                                Showing {{ $penarikan->count() }} of {{ $penarikan->total() }} entries
                            </div>
                            @endif
                        </div>
                        <div class="col-sm-auto">
                            {{ $penarikan->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection