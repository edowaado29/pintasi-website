<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    public function pemeriksaan()
    {
        return view('puskesmas.pemeriksaan.main-pemeriksaan');
    }
    
    public function tambah_pemeriksaan(): View
    {
        return view('puskesmas.pemeriksaan.tambah-pemeriksaan');
    }

    public function detail_pemeriksaan(): View
    {
        return view('puskesmas.pemeriksaan.detail-pemeriksaan');
    }

    public function edit_pemeriksaan(): View
    {
        return view('puskesmas.pemeriksaan.edit-pemeriksaan');
    }
}
