<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('biaya_sekolah', function (Blueprint $table) {
            $table->string('jenis_kelamin')->nullable()->after('tingkat');
        });
    }

    public function down()
    {
        Schema::table('biaya_sekolah', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
        });
    }
};