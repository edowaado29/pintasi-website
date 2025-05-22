<?php

namespace App\Http\Controllers;

use App\Models\DaftarBahan;
use Illuminate\Http\Request;

class DaftarBahanController extends Controller
{
    public function daftar_bahan()
    {
        $bahans = DaftarBahan::all();
        return view('puskesmas.daftarBahan.main-daftarBahan', compact('bahans'));
    }

    public function tambah_bahan()
    {
        return view('puskesmas.daftarBahan.tambah-daftarBahan');
    }

    public function add_bahan(Request $request) {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'kalori' => 'required|numeric',
            'protein' => 'required|numeric',
            'lemak' => 'required|numeric',
            'karbohidrat' => 'required|numeric',
            'serat' => 'required|numeric',
        ], [
            'nama_bahan.required' => 'Nama Bahan tidak boleh kosong.',
            'kalori.required' => 'Kalori tidak boleh kosong.',
            'kalori.numeric' => 'Penulisan harus menggunakan titik (.).',
            'protein.required' => 'Protein tidak boleh kosong.',
            'protein.numeric' => 'Penulisan harus menggunakan titik (.).',
            'lemak.required' => 'Lemak tidak boleh kosong.',
            'lemak.numeric' => 'Penulisan harus menggunakan titik (.).',
            'karbohidrat.required' => 'Karbohidrat tidak boleh kosong.',
            'karbohidrat.numeric' => 'Penulisan harus menggunakan titik (.).',
            'serat.required' => 'Serat tidak boleh kosong.',
            'serat.numeric' => 'Penulisan harus menggunakan titik (.).',
        ]);

        DaftarBahan::create([
            'nama_bahan' => $request->input('nama_bahan'),
            'kalori' => $request->input('kalori'),
            'protein' => $request->input('protein'),
            'lemak' => $request->input('lemak'),
            'karbohidrat' => $request->input('karbohidrat'),
            'serat' => $request->input('serat'),
        ]);

        return redirect()->route('daftar_bahan')->with(['message' => 'Bahan berhasil ditambahkan.']);
    }

    public function edit_bahan($id)
    {
        $bahans = DaftarBahan::findOrFail($id);
        return view('puskesmas.daftarBahan.edit-daftarBahan', compact('bahans'));
    }
    public function update_bahan(Request $request, $id)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'kalori' => 'required|numeric',
            'protein' => 'required|numeric',
            'lemak' => 'required|numeric',
            'karbohidrat' => 'required|numeric',
            'serat' => 'required|numeric',
        ], [
            'nama_bahan.required' => 'Nama Bahan tidak boleh kosong.',
            'kalori.required' => 'Kalori tidak boleh kosong.',
            'kalori.numeric' => 'Penulisan harus menggunakan titik (.).',
            'protein.required' => 'Protein tidak boleh kosong.',
            'protein.numeric' => 'Penulisan harus menggunakan titik (.).',
            'lemak.required' => 'Lemak tidak boleh kosong.',
            'lemak.numeric' => 'Penulisan harus menggunakan titik (.).',
            'karbohidrat.required' => 'Karbohidrat tidak boleh kosong.',
            'karbohidrat.numeric' => 'Penulisan harus menggunakan titik (.).',
            'serat.required' => 'Serat tidak boleh kosong.',
            'serat.numeric' => 'Penulisan harus menggunakan titik (.).',
        ]);

        $bahans = DaftarBahan::findOrFail($id);
        $bahans->update($request->all());

        return redirect()->route('daftar_bahan')->with(['message' => 'Bahan berhasil diperbarui.']);
    }
    
    public function detail_bahan(string $id)
    {
        $bahans = DaftarBahan::findOrFail($id);
        return view('puskesmas.daftarBahan.detail-daftarBahan', compact('bahans'));
    }
    
    public function hapus_bahan(string $id)
    {
        $bahans = DaftarBahan::findOrFail($id);
        $bahans->delete();
        return redirect()->route('daftar_bahan')->with(['message' => 'Bahan berhasil dihapus.']);
    }
}
