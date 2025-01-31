<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    
    protected $fillable = [
        'siswa_id',
        'tagihan_id',
        'jenis_biaya',
        'bulan_hijri',
        'jumlah',
        'metode_pembayaran',
        'keterangan'
    ];

    // Constants for enum values
    const JENIS_BIAYA = [
        'SPP', 
        'IKK', 
        'THB', 
        'Uang Pangkal', 
        'Raport', 
        'Wisuda', 
        'Foto', 
        'Seragam',
        'UAM'
    ];

    const BULAN_HIJRI = [
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
    ];

    const METODE_PEMBAYARAN = [
        'cash',
        'cicilan',
        'tabungan'
    ];

    // Relationships
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class);
    }

    // Di Pembayaran.php
public function transaksi()
{
    return $this->hasOne(Transaksi::class);
}
}