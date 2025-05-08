<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    protected $fillable = [
        'bayi_id',
        'tanggal_pemeriksaan',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'catatan',
        'status_gizi',
        'kalori',
        'protein',
        'lemak',
        'karbohidrat',
        'serat',
    ];

    public function bayi() {
        return $this->belongsTo(Bayi::class, 'bayi_id');
    }
}
