<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanResep extends Model
{
    protected $fillable = [
        'id_resep',
        'nama_bahan',
        'berat',
        'satuan_berat',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep::class, "id_resep");
    }
}
