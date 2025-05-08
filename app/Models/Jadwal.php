<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal_pemeriksaan',
        'jam_pemeriksaan',
        'jenis_pemeriksaan',
        'tempat',
    ];
}
