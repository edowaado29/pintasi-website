<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanResep extends Model
{
    protected $fillable = [
        'id_resep',
        'id_daftarBahan',
        'berat',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep::class, "id_resep");
    }
    public function daftarBahan()
    {
        return $this->belongsTo(DaftarBahan::class, "id_daftarBahan");
    }
}
