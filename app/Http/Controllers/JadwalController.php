<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function jadwal()
    {
        return view('puskesmas.jadwal.main-jadwal');
    }
}
