<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MotorikController extends Controller
{
    public function motorik(): View
    {
        return view('puskesmas.motorik.main-motorik');
    }
}
