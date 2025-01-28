<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->enum('jenis_biaya', [
                'SPP',
                'IKK',
                'THB',
                'UAM',
                'Wisuda',
                'Uang Pangkal',
                'Raport',
                'Seragam',
                'Foto'
            ]);
            $table->decimal('jumlah', 15, 2);
            $table->decimal('sisa', 15, 2);
            $table->enum('status', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Add unique constraint
            $table->unique(['siswa_id', 'tahun_ajaran_id', 'jenis_biaya']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihan');
    }
};