<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IbuController extends Controller
{
    public function ibu()
    {
        return view('puskesmas.ibu.main-ibu');
    }
}
