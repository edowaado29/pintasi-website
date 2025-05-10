<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $fillable = ['id_bayi', 'bb', 'tb', 'lk', 'imt', 'tgl_periksa', 'status_gizi', 'kalori', 'protein', 'lemak', 'karbo', 'serat'];

    public function bayi()
    {
        return $this->belongsTo(Bayi::class, 'id_bayi');
    }
}
