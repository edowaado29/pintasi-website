<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    public function pemeriksaan()
    {
        return view('pemeriksaan.main-pemeriksaan');
    }
}
