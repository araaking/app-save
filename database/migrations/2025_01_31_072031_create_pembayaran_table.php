<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            
            // Relasi
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('tagihan_id')->constrained('tagihan')->onDelete('cascade'); // Relasi ke tagihan
            
            // Jenis biaya & bulan hijriah
            $table->enum('jenis_biaya', [
                'SPP', 
                'IKK', 
                'THB', 
                'Uang Pangkal', 
                'Raport', 
                'Wisuda', 
                'Foto', 
                'Seragam'
            ]);
            
            $table->enum('bulan_hijri', [
                'Muharram',
                'Safar',
                'Rabiul Awwal',
                'Rabiul Akhir',
                'Jumadil Awwal',
                'Jumadil Akhir',
                'Rajab',
                "Syaban",
                'Ramadan',
                'Syawwal',
                'Dzulqaidah',
                'Dzulhijjah'
            ])->nullable(); // Nullable untuk semua jenis kecuali SPP
            
            // Data pembayaran
            $table->decimal('jumlah', 15, 2); // Nominal pembayaran
            $table->enum('metode_pembayaran', ['cash', 'cicilan', 'tabungan']); // Metode pembayaran
            $table->text('keterangan')->nullable(); // Catatan tambahan
            $table->timestamps(); // Waktu transaksi

            // Unique: Hanya untuk SPP (kombinasi tagihan + bulan)
            $table->unique(
                ['tagihan_id', 'bulan_hijri'], // Gunakan tagihan_id untuk integritas data
                'unique_pembayaran_spp'
            )->where('jenis_biaya', 'SPP'); // Hanya berlaku untuk SPP
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
};