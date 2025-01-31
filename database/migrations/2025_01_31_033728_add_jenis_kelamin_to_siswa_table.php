<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Tambahkan kolom setelah 'name' dengan nilai default
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])
                  ->after('name')
                  ->default('Laki-laki');
        });
    }

    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin'); // Rollback: hapus kolom
        });
    }
};