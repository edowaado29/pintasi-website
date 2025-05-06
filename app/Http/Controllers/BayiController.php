<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bayi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BayiController extends Controller
{
    public function bayi(): View
    {
        $bayis = Bayi::all();
        return view('puskesmas.bayi.main-bayi', compact('bayis'));
    }
}
