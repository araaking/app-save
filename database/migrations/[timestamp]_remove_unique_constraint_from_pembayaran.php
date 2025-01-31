<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Drop the foreign key first
            $table->dropForeign(['tagihan_id']);
            
            // Drop the unique index
            $table->dropIndex('unique_pembayaran_spp');
            
            // Recreate the foreign key without the unique constraint
            $table->foreign('tagihan_id')
                  ->references('id')
                  ->on('tagihan')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Drop the foreign key
            $table->dropForeign(['tagihan_id']);
            
            // Recreate the unique constraint
            $table->unique(['tagihan_id', 'bulan_hijri'], 'unique_pembayaran_spp')
                  ->where('jenis_biaya', 'SPP');
            
            // Recreate the foreign key
            $table->foreign('tagihan_id')
                  ->references('id')
                  ->on('tagihan')
                  ->onDelete('cascade');
        });
    }
};