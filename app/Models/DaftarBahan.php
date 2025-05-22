<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarBahan extends Model
{
    protected $fillable = [
        'nama_bahan',
        'kalori',
        'protein',
        'lemak',
        'karbohidrat',
        'serat',
    ];
}
