@extends('layouts.layout')

@section('title', 'Tambah Pembayaran Biaya Sekolah')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Tambah Pembayaran Biaya Sekolah</h5>
                <a href="{{ route('tagihan.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="row">
                    <div class="col-lg-6">
                        <form action="{{ route('pembayaran.store') }}" method="POST" id="paymentForm">
                            @csrf
                            <div class="mb-3">
                                <label for="siswa_id" class="form-label">Siswa</label>
                                <select name="siswa_id" id="siswa_id" class="form-select" required readonly>
                                    @foreach ($siswas as $siswa)
                                        <option value="{{ $siswa->id }}" selected>
                                            {{ $siswa->name }} - {{ $siswa->kelas->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="jenis_biaya" class="form-label">Jenis Biaya</label>
                                <select name="jenis_biaya" id="jenis_biaya" class="form-select" required>
                                    <option value="">Pilih Jenis Biaya</option>
                                </select>
                                <div id="jenis_biaya_error" class="invalid-feedback"></div>
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

                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah Pembayaran (Rp)</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" min="0" placeholder="0" required>
                                <small class="text-muted" id="sisa_tagihan"></small>
                                <div id="jumlah_error" class="invalid-feedback"></div>
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
                                <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-6">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">Informasi Pembayaran:</h6>
                            <p class="mb-0" id="payment_info">Silahkan pilih jenis biaya untuk melihat informasi pembayaran.</p>
                        </div>
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
    const form = document.getElementById('paymentForm');
    const jenisBiaya = document.getElementById('jenis_biaya');
    const bulanContainer = document.getElementById('bulan_hijri_container');
    const bulanSelect = document.getElementById('bulan_hijri');
    const siswaSelect = document.getElementById('siswa_id');
    const jumlahInput = document.getElementById('jumlah');
    const submitBtn = document.getElementById('submitBtn');
    const paymentInfo = document.getElementById('payment_info');
    
    loadTagihan(siswaSelect.value);

    function toggleBulanField() {
        const isSPP = jenisBiaya.value === 'SPP';
        bulanContainer.style.display = isSPP ? 'block' : 'none';
        bulanSelect.required = isSPP;
        if (!isSPP) bulanSelect.value = '';
    }

    toggleBulanField();
    
    jenisBiaya.addEventListener('change', function() {
        toggleBulanField();
        updatePaymentInfo(this.options[this.selectedIndex]);
    });
    
    jumlahInput.addEventListener('input', function() {
        const max = parseFloat(this.getAttribute('max'));
        const value = parseFloat(this.value);
        
        if (max && value > max) {
            this.value = max;
            this.classList.add('is-invalid');
            document.getElementById('jumlah_error').textContent = 'Jumlah melebihi sisa tagihan';
        } else {
            this.classList.remove('is-invalid');
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateForm()) {
            this.submit();
        }
    });
});

function loadTagihan(siswaId) {
    if (!siswaId) return;

    fetch(`/api/siswa/${siswaId}/tagihan`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
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

            if (data.length === 0) {
                document.getElementById('payment_info').textContent = 'Tidak ada tagihan yang perlu dibayar.';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('payment_info').textContent = 'Terjadi kesalahan saat memuat data tagihan.';
        });
}

function updatePaymentInfo(selectedOption) {
    const sisaTagihan = selectedOption.dataset.sisa;
    const jumlahInput = document.getElementById('jumlah');
    const sisaInfo = document.getElementById('sisa_tagihan');
    const paymentInfo = document.getElementById('payment_info');
    
    if (sisaTagihan) {
        jumlahInput.max = sisaTagihan;
        jumlahInput.value = '';
        sisaInfo.textContent = `Sisa tagihan: Rp ${new Intl.NumberFormat('id-ID').format(sisaTagihan)}`;
        paymentInfo.textContent = `Pembayaran untuk ${selectedOption.text}`;
        jumlahInput.classList.remove('is-invalid');
    }
}

function validateForm() {
    const form = document.getElementById('paymentForm');
    const jumlahInput = document.getElementById('jumlah');
    const jenisBiaya = document.getElementById('jenis_biaya');
    let isValid = true;

    if (!jenisBiaya.value) {
        jenisBiaya.classList.add('is-invalid');
        document.getElementById('jenis_biaya_error').textContent = 'Pilih jenis biaya';
        isValid = false;
    }

    if (!jumlahInput.value || parseFloat(jumlahInput.value) <= 0) {
        jumlahInput.classList.add('is-invalid');
        document.getElementById('jumlah_error').textContent = 'Masukkan jumlah pembayaran';
        isValid = false;
    }

    return isValid;
}
</script>
@endpush