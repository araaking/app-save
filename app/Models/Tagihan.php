<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'jenis_biaya',
        'jumlah',
        'sisa',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'sisa' => 'decimal:2',
    ];

    // Relationship with Student
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relationship with Academic Year
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Helper method to check if bill is paid
    public function isLunas()
    {
        return $this->status === 'Lunas';
    }
}