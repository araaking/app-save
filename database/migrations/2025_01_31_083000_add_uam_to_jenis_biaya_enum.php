<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddUamToJenisBiayaEnum extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN jenis_biaya ENUM('SPP', 'IKK', 'THB', 'Uang Pangkal', 'Raport', 'Wisuda', 'Foto', 'Seragam', 'UAM')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN jenis_biaya ENUM('SPP', 'IKK', 'THB', 'Uang Pangkal', 'Raport', 'Wisuda', 'Foto', 'Seragam')");
    }
}