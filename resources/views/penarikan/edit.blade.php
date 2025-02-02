@extends('layouts.layout')

@section('title', 'Edit Penarikan')

@section('content')
<div class="container-fluid">
    <!-- Header with Breadcrumb -->
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit Penarikan</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penarikan.index') }}">Penarikan</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Penarikan</h5>
                    <a href="{{ route('penarikan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form action="{{ route('penarikan.update', $penarikan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Info Siswa (Read-only) -->
                                <div class="mb-3">
                                    <label class="form-label">Siswa</label>
                                    <input type="text" class="form-control" value="{{ $penarikan->bukuTabungan->siswa->name }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kelas</label>
                                    <input type="text" class="form-control" value="{{ $penarikan->bukuTabungan->siswa->kelas->name }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Sumber Penarikan</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($penarikan->sumber_penarikan) }}" readonly>
                                </div>

                                <!-- Editable Fields -->
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah Penarikan (Rp)</label>
                                    <input type="number" 
                                           name="jumlah" 
                                           id="jumlah" 
                                           class="form-control @error('jumlah') is-invalid @enderror" 
                                           value="{{ old('jumlah', $penarikan->jumlah) }}" 
                                           required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="datetime-local" 
                                           name="tanggal" 
                                           id="tanggal" 
                                           class="form-control @error('tanggal') is-invalid @enderror" 
                                           value="{{ old('tanggal', $penarikan->tanggal->format('Y-m-d\TH:i')) }}" 
                                           required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea name="keterangan" 
                                              id="keterangan" 
                                              class="form-control @error('keterangan') is-invalid @enderror" 
                                              rows="3">{{ old('keterangan', $penarikan->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <a href="{{ route('penarikan.index') }}" class="btn btn-secondary me-2">
                                        <i class="mdi mdi-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection