<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bayi extends Model
{
    use HasFactory;

    protected $fillable = ['no_kk', 'nik_bayi', 'nama', 'tgl_lahir', 'jk', 'nama_ayah', 'nama_ibu', 'foto_bayi'];

    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class);
    }
}
