<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ibu;
use Illuminate\Http\Request;

class IbuController extends Controller
{
    public function ibu()
    {
        $ibus = Ibu::Latest()->paginate(10);
        return view('puskesmas.ibu.main-ibu', compact('ibus'));
    }

    public function show(string $id)
    {
        $ibus = Ibu::findOrFail($id);
        return view('puskesmas.ibu.show-ibu', compact('ibus'));
    }
}
