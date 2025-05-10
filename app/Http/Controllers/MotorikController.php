<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Motorik;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MotorikController extends Controller
{
    public function motorik(): View
    {
        $motoriks = Motorik::all();
        return view('puskesmas.motorik.main-motorik', compact('motoriks'));
    }

    public function tambah_motorik(): View
    {
        return view('puskesmas.motorik.tambah-motorik');
    }

    public function edit_motorik(string $id): View
    {
        $motoriks = Motorik::findOrFail($id);
        return view('puskesmas.motorik.edit-motorik', compact('motoriks'));
    }
    public function add_motorik(Request $request) {
        $request->validate([
            'usia' => 'required|integer',
            'capaian' => 'required|string|max:255',
        ], [
            'usia.required' => 'Usia tidak boleh kosong.',
            'capaian.required' => 'Capaian tidak boleh kosong.'
        ]);

        
        Motorik::create([
            'usia' => $request->usia,
            'capaian' => $request->capaian,
        ]);

        return redirect()->route('motorik')->with(['message' => 'Data motorik berhasil ditambahkan']);
    }

    public function update_motorik(Request $request, string $id) {
        $request->validate([
            'usia' => 'required|integer',
            'capaian' => 'required|string|max:255',
        ], [
            'usia.required' => 'Usia tidak boleh kosong.',
            'capaian.required' => 'Capaian tidak boleh kosong.'
        ]);

        
        $motoriks = Motorik::findOrFail($id);
        $motoriks->update(
            [
                'usia' => $request->usia,
                'capaian' => $request->capaian,
            ]); 

        return redirect()->route('motorik')->with(['message' => 'Data motorik berhasil diperbarui']);
    }
    public function hapus_motorik(string $id) {
        
        $motoriks = Motorik::findOrFail($id);
        $motoriks->delete();

        return redirect()->route('motorik')->with(['message' => 'Data motorik berhasil dihapus']);
    }

}
