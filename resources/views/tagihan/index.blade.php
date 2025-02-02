@extends('layouts.layout')

@section('title', 'Daftar Tagihan')

@section('content')
<div class="container-fluid">
    <!-- Header with Breadcrumb -->
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Daftar Tagihan Siswa</h4>
        </div>
        <div class="text-sm-end text-center mt-sm-0 mt-2">
            <ol class="breadcrumb m-0 py-0 justify-content-sm-end justify-content-center">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tagihan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <h5 class="card-title mb-0">Daftar Tagihan Siswa</h5>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-info">
                        <i class="mdi mdi-history me-1"></i> Riwayat Pembayaran
                    </a>
                    <form action="{{ route('tagihan.generate') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Generate tagihan baru">
                            <i class="mdi mdi-plus-circle me-1"></i> Generate Tagihan
                        </button>
                    </form>
                    <form action="{{ route('tagihan.generate') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="refresh" value="true">
                        <button type="submit" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Perbarui tagihan yang sudah ada">
                            <i class="mdi mdi-refresh me-1"></i> Refresh Tagihan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Bills List -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="min-width: 50px;">No</th>
                                    <th style="min-width: 150px;">Nama Siswa</th>
                                    <th style="min-width: 100px;">Kelas</th>
                                    <th style="min-width: 100px;">SPP</th>
                                    <th style="min-width: 100px;">IKK</th>
                                    <th style="min-width: 100px;">THB</th>
                                    <th style="min-width: 100px;">UAM</th>
                                    <th style="min-width: 100px;">Wisuda</th>
                                    <th style="min-width: 120px;">Uang Pangkal</th>
                                    <th style="min-width: 100px;">Foto</th>
                                    <th style="min-width: 100px;">Raport</th>
                                    <th style="min-width: 100px;">Seragam</th>
                                    <th style="min-width: 120px;">Total</th>
                                    <th style="min-width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tagihans as $siswaId => $siswaTagihan)
                                    @php
                                        $siswa = $siswaTagihan->first()->siswa;
                                        $tagihanByJenis = $siswaTagihan->keyBy('jenis_biaya');
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $siswa->name }}</td>
                                        <td>{{ $siswa->kelas->name }}</td>
                                        <td class="text-end">{{ isset($tagihanByJenis['SPP']) ? 'Rp ' . number_format($tagihanByJenis['SPP']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end">{{ isset($tagihanByJenis['IKK']) ? 'Rp ' . number_format($tagihanByJenis['IKK']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end">{{ isset($tagihanByJenis['THB']) ? 'Rp ' . number_format($tagihanByJenis['THB']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end">{{ isset($tagihanByJenis['UAM']) ? 'Rp ' . number_format($tagihanByJenis['UAM']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end">{{ isset($tagihanByJenis['Wisuda']) ? 'Rp ' . number_format($tagihanByJenis['Wisuda']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end">{{ isset($tagihanByJenis['Uang Pangkal']) ? 'Rp ' . number_format($tagihanByJenis['Uang Pangkal']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end">{{ isset($tagihanByJenis['Foto']) ? 'Rp ' . number_format($tagihanByJenis['Foto']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end">{{ isset($tagihanByJenis['Raport']) ? 'Rp ' . number_format($tagihanByJenis['Raport']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end">{{ isset($tagihanByJenis['Seragam']) ? 'Rp ' . number_format($tagihanByJenis['Seragam']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($siswaTagihan->sum('sisa'), 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('pembayaran.create', ['siswa_id' => $siswa->id, 'kelas_id' => $siswa->kelas_id]) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-cash me-1"></i> Bayar
                                                </a>
                                                @if($siswaTagihan->sum('sisa') > 0)
                                                    <button type="button"
                                                            class="btn btn-sm btn-info"
                                                            data-bs-toggle="tooltip"
                                                            title="Klik tombol bayar untuk melakukan pembayaran">
                                                        <i class="mdi mdi-information"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="text-center py-4">
                                            <img src="{{ asset('empty-state.svg') }}" class="img-fluid mb-3" style="max-width: 200px;">
                                            <p class="text-muted">Tidak ada data tagihan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                        <div>
                            @if ($tagihans->count() > 0)
                                <small class="text-muted">
                                    Showing {{ $tagihans->firstItem() }} to {{ $tagihans->lastItem() }} 
                                    of {{ $tagihans->total() }} entries
                                </small>
                            @endif
                        </div>
                        {{ $tagihans->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection