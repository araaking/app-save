@extends('layouts.layout')

@section('title', 'Tambah Penarikan')

@section('content')
<div class="container-fluid">
    <!-- Header with Breadcrumb -->
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Form Penarikan</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penarikan.index') }}">Penarikan</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Form Penarikan</h5>
                    <a href="{{ route('penarikan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Form Filter Kelas -->
                        <form action="{{ route('penarikan.create') }}" method="GET">
                            <div class="mb-3">
                                <label for="kelas_id" class="form-label">Filter Kelas</label>
                                <select name="kelas_id" id="kelas_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>

                        <!-- Form Penarikan -->
                        <form action="{{ route('penarikan.store') }}" method="POST" id="formPenarikan">
                            @csrf
                            <div class="mb-3">
                                <label for="buku_tabungan_id" class="form-label">Buku Tabungan</label>
                                <select name="buku_tabungan_id" id="buku_tabungan_id" class="form-select" required>
                                    <option value="">Pilih Buku Tabungan</option>
                                    @foreach ($bukuTabungans as $buku)
                                        <option value="{{ $buku->id }}" 
                                            data-simpanan="{{ $buku->totalSimpanan - $buku->totalPenarikanSimpanan }}"
                                            data-cicilan="{{ $buku->totalCicilan - $buku->totalPenarikanCicilan }}">
                                            {{ $buku->nomor_urut }} - {{ $buku->siswa->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah Penarikan (Rp)</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" step="0.01" required>
                                <div class="invalid-feedback" id="saldoError"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sumber Penarikan</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sumber_penarikan" 
                                        id="simpanan" value="simpanan" required>
                                    <label class="form-check-label" for="simpanan">Simpanan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sumber_penarikan" 
                                        id="cicilan" value="cicilan">
                                    <label class="form-check-label" for="cicilan">Cicilan</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <a href="{{ route('penarikan.index') }}" class="btn btn-secondary me-2">
                                    <i class="mdi mdi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-coins me-2"></i> Proses Penarikan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formPenarikan');
    const bukuTabunganSelect = document.getElementById('buku_tabungan_id');
    const jumlahInput = document.getElementById('jumlah');
    const sumberInputs = document.getElementsByName('sumber_penarikan');
    const saldoError = document.getElementById('saldoError');

    function checkBalance() {
        const selectedOption = bukuTabunganSelect.options[bukuTabunganSelect.selectedIndex];
        const saldoSimpanan = parseFloat(selectedOption.dataset.simpanan) || 0;
        const saldoCicilan = parseFloat(selectedOption.dataset.cicilan) || 0;
        const jumlah = parseFloat(jumlahInput.value) || 0;
        let sumberPenarikan = '';

        sumberInputs.forEach(input => {
            if (input.checked) {
                sumberPenarikan = input.value;
            }
        });

        // Only check balance for cicilan
        if (sumberPenarikan === 'cicilan') {
            if (jumlah > saldoCicilan) {
                jumlahInput.classList.add('is-invalid');
                saldoError.textContent = `Saldo cicilan tidak mencukupi. Saldo tersedia: Rp ${new Intl.NumberFormat('id-ID').format(saldoCicilan)}`;
                return false;
            }
        }

        jumlahInput.classList.remove('is-invalid');
        saldoError.textContent = '';
        return true;
    }

    // Check balance when amount or withdrawal source changes
    jumlahInput.addEventListener('input', checkBalance);
    sumberInputs.forEach(input => {
        input.addEventListener('change', checkBalance);
    });
    bukuTabunganSelect.addEventListener('change', checkBalance);

    // Prevent form submission if balance is insufficient for cicilan
    form.addEventListener('submit', function(e) {
        const sumberPenarikan = Array.from(sumberInputs).find(input => input.checked)?.value;
        if (sumberPenarikan === 'cicilan' && !checkBalance()) {
            e.preventDefault();
        }
    });
});
</script>
@endpush