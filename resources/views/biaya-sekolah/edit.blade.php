@extends('layouts.layout')

@section('title', 'Edit Biaya Sekolah')

@section('content')
<div class="container-fluid">
    <!-- Header & Breadcrumb -->
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit Biaya Sekolah</h4>
        </div>
        <div class="text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('biaya-sekolah.index') }}">Biaya Sekolah</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Biaya Sekolah</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Biaya Sekolah</h5>
                </div>
            <div class="card-body">
                <form action="{{ route('biaya-sekolah.update', $biaya->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Biaya</label>
                                <input type="text" class="form-control" value="{{ $biaya->jenis_biaya }}" readonly>
                            </div>

                            @if(in_array($biaya->jenis_biaya, ['SPP', 'IKK']))
                            <div class="mb-3">
                                <label class="form-label">Kategori Siswa</label>
                                <input type="text" class="form-control" value="{{ $biaya->kategori_siswa }}" readonly>
                            </div>
                            @endif

                            @if($biaya->jenis_biaya === 'THB')
                            <div class="mb-3">
                                <label class="form-label">Tingkat Kelas</label>
                                <input type="text" class="form-control" value="{{ $biaya->tingkat }}" readonly>
                            </div>
                            @endif
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                                <input type="number" name="jumlah" class="form-control" 
                                       value="{{ $biaya->jumlah }}" step="0.01" required>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3">{{ $biaya->keterangan }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection