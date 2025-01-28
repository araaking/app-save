<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->enum('status_siswa', ['Baru', 'Lama'])->after('name');
            $table->boolean('memiliki_raport')->default(false)->after('status_siswa');
            $table->enum('status_iqra', ['Alumni TK', 'Bukan Alumni'])->after('memiliki_raport');
        });
    }

    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['status_siswa', 'memiliki_raport', 'status_iqra']);
        });
    }
};