<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->default('Laki-laki');
            $table->enum('status_siswa', ['Baru', 'Lama']);
            $table->boolean('memiliki_raport')->default(false);
            $table->enum('status_iqra', ['Alumni TK', 'Bukan Alumni']);
            $table->string('nis', 20)->unique()->nullable();
            $table->foreignId('class_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->enum('status', ['Aktif', 'Lulus', 'Keluar'])->default('Aktif');
            $table->enum('category', ['Anak Guru', 'Anak Yatim', 'Kakak Beradik', 'Anak Normal']);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswa');
    }
};