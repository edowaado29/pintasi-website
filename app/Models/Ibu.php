<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Ibu extends Model
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $table = 'Ibus';

    protected $fillable = [
        'email',
        'password',
        'nik',
        'nama_ibu',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'telepon',
        'foto',
    ];

    public function bayi()
    {
        return $this->hasMany(Bayi::class, 'id_ibu');
    }

}
