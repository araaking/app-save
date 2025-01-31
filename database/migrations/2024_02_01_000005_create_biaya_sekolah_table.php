<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('biaya_sekolah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->enum('jenis_biaya', [
                'SPP', 'IKK', 'THB', 'UAM', 'Wisuda', 
                'Uang Pangkal', 'Raport', 'Seragam', 'Foto'
            ]);
            $table->enum('kategori_siswa', [
                'Anak Guru', 'Anak Yatim', 'Anak Yatim Kakak Beradik',
                'Kakak Beradik', 'Anak Normal'
            ])->nullable();
            $table->integer('tingkat')->nullable()->comment('1=TK, 2-7=Grade 1-6');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->unique([
                'tahun_ajaran_id', 'jenis_biaya', 
                'kategori_siswa', 'tingkat', 'jenis_kelamin'
            ], 'biaya_unik');
        });
    }

    public function down()
    {
        Schema::dropIfExists('biaya_sekolah');
    }
};