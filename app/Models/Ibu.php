<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Ibu extends Authenticatable
{
    use HasApiTokens, SoftDeletes, Notifiable;

    protected $table = 'ibus';

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

    protected $hidden = [
        'password', // Sembunyikan password saat serialisasi
        'remember_token',
    ];

    public function bayi()
    {
        return $this->hasMany(Bayi::class, 'id_ibu');
    }

}
