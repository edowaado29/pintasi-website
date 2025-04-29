<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KaderController extends Controller
{
    public function kader()
    {
        return view('kader.main-kader');
    }
}
