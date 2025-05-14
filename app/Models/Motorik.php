<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motorik extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'min_usia',
        'max_usia',
        'capaian_motorik',
    ];
}
