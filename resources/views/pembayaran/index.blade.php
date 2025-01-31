@extends('layouts.layout')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jenis Biaya</th>
                                    <th>Bulan</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembayarans as $index => $pembayaran)
                                <tr>
                                    <td>{{ $pembayarans->firstItem() + $index }}</td>
                                    <td>{{ $pembayaran->siswa->name }}</td>
                                    <td>{{ $pembayaran->siswa->kelas->name }}</td>
                                    <td>{{ $pembayaran->jenis_biaya }}</td>
                                    <td>{{ $pembayaran->bulan_hijri ?? '-' }}</td>
                                    <td>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        @if($pembayaran->metode_pembayaran === 'cash')
                                            <span class="badge bg-success">Cash</span>
                                        @elseif($pembayaran->metode_pembayaran === 'cicilan')
                                            <span class="badge bg-warning">Via Cicilan</span>
                                        @else
                                            <span class="badge bg-info">Via Tabungan</span>
                                        @endif
                                    </td>
                                    <td>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($pembayaran->metode_pembayaran !== 'cash')
                                            <span class="badge bg-primary">Terpotong dari {{ $pembayaran->metode_pembayaran }}</span>
                                        @else
                                            <span class="badge bg-success">Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data pembayaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($pembayarans->hasPages())
                <div class="card-footer">
                    {{ $pembayarans->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection