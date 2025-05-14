<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerkembanganMotorik extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id_bayi',
        'id_motorik',
    ];

    public function bayi()
    {
        return $this->belongsTo(Bayi::class, "id_bayi");
    }

    public function motorik()
    {
        return $this->belongsTo(Motorik::class, "id_motorik");
    }
}
