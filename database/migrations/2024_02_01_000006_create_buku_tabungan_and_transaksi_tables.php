<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('buku_tabungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('kelas')->onDelete('cascade');
            $table->integer('nomor_urut');
            $table->unique(['class_id', 'tahun_ajaran_id', 'nomor_urut']);
            $table->timestamps();
        });

        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['simpanan', 'penarikan', 'cicilan'])
                ->comment('Jenis transaksi: simpanan/penarikan/cicilan');
            $table->foreignId('buku_tabungan_id')->constrained('buku_tabungan')->onDelete('cascade');
            $table->decimal('jumlah', 15, 2);
            $table->timestamp('tanggal')->useCurrent();
            $table->string('sumber_penarikan')->nullable()
                ->comment('Sumber penarikan: simpanan/cicilan (hanya untuk jenis penarikan)');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->index(['buku_tabungan_id', 'jenis']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('buku_tabungan');
    }
};