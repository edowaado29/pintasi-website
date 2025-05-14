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
            'min_usia' => 'required|integer',
            'max_usia' => 'required|integer',
            'capaian_motorik' => 'required|string|max:255',
        ], [
            'min_usia.required' => 'Usia minimal tidak boleh kosong.',
            'max_usia.required' => 'Usia maksimal tidak boleh kosong.',
            'capaian_motorik.required' => 'Capaian Motorik tidak boleh kosong.'
        ]);

        
        Motorik::create([
            'min_usia' => $request->min_usia,
            'max_usia' => $request->max_usia,
            'capaian_motorik' => $request->capaian_motorik,
        ]);

        return redirect()->route('motorik')->with(['message' => 'Data motorik berhasil ditambahkan']);
    }

    public function update_motorik(Request $request, string $id) {
        $request->validate([
            'min_usia' => 'required|integer',
            'max_usia' => 'required|integer',
            'capaian_motorik' => 'required|string|max:255',
        ], [
            'min_usia.required' => 'Usia minimal tidak boleh kosong.',
            'max_usia.required' => 'Usia maksimal tidak boleh kosong.',
            'capaian_motorik.required' => 'Capaian Motorik tidak boleh kosong.'
        ]);

        
        $motoriks = Motorik::findOrFail($id);
        $motoriks->update(
            [
                'min_usia' => $request->min_usia,
                'max_usia' => $request->max_usia,
                'capaian_motorik' => $request->capaian_motorik,
            ]); 

        return redirect()->route('motorik')->with(['message' => 'Data motorik berhasil diperbarui']);
    }
    public function hapus_motorik(string $id) {
        
        $motoriks = Motorik::findOrFail($id);
        $motoriks->delete();

        return redirect()->route('motorik')->with(['message' => 'Data motorik berhasil dihapus']);
    }

}
