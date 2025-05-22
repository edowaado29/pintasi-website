<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $fillable = [
        'nama_resep',
        'langkah',
        'jumlah_porsi',
        'min_usia',
        'max_usia',
        'total_kalori',
        'total_protein',
        'total_lemak',
        'total_karbohidrat',
        'total_serat',
        'gambar_resep',
    ];
}
