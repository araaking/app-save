@extends('layouts.layout')

@section('title', 'Daftar Tagihan')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Daftar Tagihan Siswa</h5>
                <div class="d-flex gap-2">
                    <form action="{{ route('tagihan.generate') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-plus-circle me-1"></i> Generate Tagihan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bills List -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>SPP</th>
                                    <th>IKK</th>
                                    <th>THB</th>
                                    <th>UAM</th>
                                    <th>Wisuda</th>
                                    <th>Uang Pangkal</th>
                                    <th>Foto</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tagihans as $siswaId => $siswaTagihan)
                                    @php
                                        $siswa = $siswaTagihan->first()->siswa;
                                        $tagihanByJenis = $siswaTagihan->keyBy('jenis_biaya');
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $siswa->name }}</td>
                                        <td>{{ $siswa->kelas->name }}</td>
                                        <td>{{ isset($tagihanByJenis['SPP']) ? 'Rp ' . number_format($tagihanByJenis['SPP']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td>{{ isset($tagihanByJenis['IKK']) ? 'Rp ' . number_format($tagihanByJenis['IKK']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td>{{ isset($tagihanByJenis['THB']) ? 'Rp ' . number_format($tagihanByJenis['THB']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td>{{ isset($tagihanByJenis['UAM']) ? 'Rp ' . number_format($tagihanByJenis['UAM']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td>{{ isset($tagihanByJenis['Wisuda']) ? 'Rp ' . number_format($tagihanByJenis['Wisuda']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td>{{ isset($tagihanByJenis['Uang Pangkal']) ? 'Rp ' . number_format($tagihanByJenis['Uang Pangkal']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td>{{ isset($tagihanByJenis['Foto']) ? 'Rp ' . number_format($tagihanByJenis['Foto']->sisa, 0, ',', '.') : '-' }}</td>
                                        <td>Rp {{ number_format($siswaTagihan->sum('sisa'), 0, ',', '.') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-primary" 
                                                        disabled>
                                                    <i class="mdi mdi-cash me-1"></i> Bayar
                                                </button>
                                                @if($siswaTagihan->sum('sisa') > 0)
                                                    <button type="button"
                                                            class="btn btn-sm btn-info"
                                                            data-bs-toggle="tooltip"
                                                            title="Pembayaran akan segera tersedia">
                                                        <i class="mdi mdi-information"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">Tidak ada data tagihan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if ($tagihans->count() > 0)
                                Showing {{ $tagihans->firstItem() }} to {{ $tagihans->lastItem() }} 
                                of {{ $tagihans->total() }} entries
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