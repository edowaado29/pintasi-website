<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BayiController extends Controller
{
    public function bayi()
    {
        return view('bayi.main-bayi');
    }
}
