<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\BukuTabunganController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BiayaSekolahController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SavingsExportController;
use App\Http\Controllers\PenarikanController;
use Illuminate\Support\Facades\Route;

// Route utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route dengan middleware auth
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tahun Ajaran
    Route::resource('tahun-ajaran', TahunAjaranController::class)->except(['show']);

    // Kelas
    Route::resource('kelas', KelasController::class);

    // Siswa
    Route::resource('siswa', SiswaController::class);

    // Buku Tabungan
    Route::resource('buku-tabungan', BukuTabunganController::class);

    /* ----- Route Transaksi & Penarikan ----- */
    // Penarikan harus didefinisikan SEBELUM resource
    Route::prefix('transaksi')->group(function () {
        Route::get('penarikan', [TransaksiController::class, 'createPenarikan'])
            ->name('transaksi.penarikan.create');
        Route::post('penarikan', [TransaksiController::class, 'storePenarikan'])
            ->name('transaksi.penarikan.store');
    });

    Route::resource('biaya-sekolah', BiayaSekolahController::class);

    // Resource Transaksi (setelah penarikan)
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('transaksi', TransaksiController::class);

    /* ----- Route Khusus AJAX ----- */
    Route::get('/kelas/{kelas}/siswa', [SiswaController::class, 'getSiswaByKelas'])
        ->name('kelas.siswa.ajax');
        
    Route::get('/get-buku-tabungan-by-kelas/{kelasId}', [TransaksiController::class, 'getBukuTabunganByKelas'])
        ->name('get-buku-tabungan-by-kelas');
    
    
    // Remove the export-pdf route
    
    // Savings Export Routes
    Route::get('/savings/preview/{id}', [SavingsExportController::class, 'preview'])
        ->name('savings.preview');
    Route::get('/savings/export-pdf/{id}', [SavingsExportController::class, 'exportPDF'])
        ->name('savings.export-pdf');

    // Penarikan (Withdrawal) Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/penarikan', [PenarikanController::class, 'index'])->name('penarikan.index');
        Route::get('/penarikan/create', [PenarikanController::class, 'create'])->name('penarikan.create');
        Route::post('/penarikan', [PenarikanController::class, 'store'])->name('penarikan.store');
        Route::get('/penarikan/{penarikan}/edit', [PenarikanController::class, 'edit'])->name('penarikan.edit');
        Route::put('/penarikan/{penarikan}', [PenarikanController::class, 'update'])->name('penarikan.update');
        Route::delete('/penarikan/{penarikan}', [PenarikanController::class, 'destroy'])->name('penarikan.destroy');
    });

});

require __DIR__.'/auth.php';