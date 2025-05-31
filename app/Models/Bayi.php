<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bayi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_ibu',
        'no_kk',
        'nik_bayi',
        'nama_bayi',
        'tanggal_lahir',
        'jenis_kelamin',
        'nama_ayah',
        'nama_ibu',
        'foto_bayi'
    ];

    // Relasi: Bayi dimiliki oleh satu Akun (Ibu)
    public function ibu()
    {
        return $this->belongsTo(Ibu::class, 'id_ibu');
    }

    public function pemeriksaan() {
        return $this->hasOne(Pemeriksaan::class, 'id_bayi')->latestOfMany('tgl_periksa');
    }
    public function pemeriksaans() {
        return $this->hasMany(Pemeriksaan::class, 'id_bayi');
    }
}
