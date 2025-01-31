@extends('layouts.layout')

@section('title', 'Tambah Pembayaran Biaya Sekolah')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tambah Pembayaran Biaya Sekolah</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Form Filter Kelas -->
                        <form action="{{ route('pembayaran.create') }}" method="GET" id="filterForm">
                            <div class="mb-3">
                                <label for="kelas_id" class="form-label">Filter Kelas</label>
                                <select name="kelas_id" id="kelas_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->name }} (Tingkat {{ $kelas->tingkat }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>

                        <!-- Form Pembayaran -->
                        <form action="{{ route('pembayaran.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="siswa_id" class="form-label">Siswa</label>
                                <select name="siswa_id" id="siswa_id" class="form-select" required onchange="loadTagihan(this.value)">
                                    <option value="">Pilih Siswa</option>
                                    @foreach ($siswas as $siswa)
                                        <option value="{{ $siswa->id }}" {{ request('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                            {{ $siswa->name }} - {{ $siswa->category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="jenis_biaya" class="form-label">Jenis Biaya</label>
                                <select name="jenis_biaya" id="jenis_biaya" class="form-select" required>
                                    <option value="">Pilih Jenis Biaya</option>
                                </select>
                            </div>

                            <div class="mb-3" id="bulan_hijri_container" style="display: none;">
                                <label for="bulan_hijri" class="form-label">Bulan Hijriah</label>
                                <select name="bulan_hijri" id="bulan_hijri" class="form-select">
                                    <option value="">Pilih Bulan</option>
                                    @foreach (\App\Models\Pembayaran::BULAN_HIJRI as $bulan)
                                        <option value="{{ $bulan }}">{{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>

                    <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" step="0.01" placeholder="0" required>
                                <small class="text-muted" id="sisa_tagihan"></small>
                            </div>

                            <div class="mb-3">
                                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                                    <option value="cash">Cash</option>
                                    <option value="cicilan">Cicilan</option>
                                    <option value="tabungan">Tabungan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Simpan Pembayaran
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
    const jenisBiaya = document.getElementById('jenis_biaya');
    const bulanContainer = document.getElementById('bulan_hijri_container');
    const bulanSelect = document.getElementById('bulan_hijri');
    const siswaSelect = document.getElementById('siswa_id');
    
    // If siswa_id is in URL, trigger change event
    if (siswaSelect.value) {
        loadTagihan(siswaSelect.value);
    }

    function toggleBulanField() {
        const isSPP = jenisBiaya.value === 'SPP';
        bulanContainer.style.display = isSPP ? 'block' : 'none';
        bulanSelect.required = isSPP;
        if (!isSPP) bulanSelect.value = '';
    }

    // Initial check
    toggleBulanField();
    
    // Event listener
    jenisBiaya.addEventListener('change', toggleBulanField);
});

function loadTagihan(siswaId) {
    if (!siswaId) return;

    fetch(`/api/siswa/${siswaId}/tagihan`)
        .then(response => response.json())
        .then(data => {
            const jenisBiayaSelect = document.getElementById('jenis_biaya');
            jenisBiayaSelect.innerHTML = '<option value="">Pilih Jenis Biaya</option>';
            
            data.forEach(tagihan => {
                if (tagihan.sisa > 0) {
                    const option = new Option(
                        `${tagihan.jenis_biaya} - Sisa: Rp ${new Intl.NumberFormat('id-ID').format(tagihan.sisa)}`,
                        tagihan.jenis_biaya
                    );
                    option.dataset.sisa = tagihan.sisa;
                    jenisBiayaSelect.add(option);
                }
            });
        });
}

// Update max payment amount when jenis_biaya changes
document.getElementById('jenis_biaya').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const sisaTagihan = selectedOption.dataset.sisa;
    const jumlahInput = document.getElementById('jumlah');
    const sisaInfo = document.getElementById('sisa_tagihan');
    
    if (sisaTagihan) {
        jumlahInput.max = sisaTagihan;
        sisaInfo.textContent = `Sisa tagihan: Rp ${new Intl.NumberFormat('id-ID').format(sisaTagihan)}`;
    }
});
</script>
@endpush