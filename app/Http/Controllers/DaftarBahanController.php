<?php

namespace App\Http\Controllers;

use App\Imports\DaftarBahanImport;
use App\Models\BahanResep;
use App\Models\DaftarBahan;
use App\Models\Resep;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DaftarBahanController extends Controller
{
    public function b_daftar_bahan()
    {
        $bahans = DaftarBahan::all();
        return view('puskesmas.daftarBahan.main-daftarBahan', compact('bahans'));
    }

    public function b_tambah_bahan()
    {
        return view('puskesmas.daftarBahan.tambah-daftarBahan');
    }

    public function b_add_bahan(Request $request) {
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

        return redirect()->route('b/daftar_bahan')->with(['message' => 'Bahan berhasil ditambahkan.']);
    }

    public function b_edit_bahan($id)
    {
        $bahans = DaftarBahan::findOrFail($id);
        return view('puskesmas.daftarBahan.edit-daftarBahan', compact('bahans'));
    }
    public function b_update_bahan(Request $request, $id)
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

        $bahanReseps = BahanResep::where('id_daftarBahan', $id)->get();
        foreach ($bahanReseps as $bahanResep) {
            $resep = Resep::find($bahanResep->id_resep);
            if($resep){
                $allBahanResep = BahanResep::where('id_resep', $resep->id)->get();
                $total_kalori = $total_protein = $total_lemak = $total_karbohidrat = $total_serat = 0;
                foreach ($allBahanResep as $br){
                    $bahan = DaftarBahan::find($br->id_daftarBahan);
                    if($bahan){
                        $total_kalori += ($bahan->kalori * $br->berat) / 100;
                        $total_protein += ($bahan->protein * $br->berat) / 100;
                        $total_lemak += ($bahan->lemak * $br->berat) / 100;
                        $total_karbohidrat += ($bahan->karbohidrat * $br->berat) / 100;
                        $total_serat += ($bahan->serat * $br->berat) / 100;
                    }
                }
                $resep->update([
                    'total_kalori' => $total_kalori,
                    'total_protein' => $total_protein,
                    'total_lemak' => $total_lemak,
                    'total_karbohidrat' => $total_karbohidrat,  
                    'total_serat' => $total_serat
                ]);
            }
            
        }

        return redirect()->route('b/daftar_bahan')->with(['message' => 'Bahan berhasil diperbarui.']);
    }
    
    public function b_detail_bahan(string $id)
    {
        $bahans = DaftarBahan::findOrFail($id);
        return view('puskesmas.daftarBahan.detail-daftarBahan', compact('bahans'));
    }
    
    public function b_hapus_bahan(string $id)
    {
        $bahans = DaftarBahan::findOrFail($id);
        $bahans->delete();
        return redirect()->route('b/daftar_bahan')->with(['message' => 'Bahan berhasil dihapus.']);
    }

    public function b_import_bahan(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ], [
            'file.mimes' => 'File harus berupa file Excel (xlsx, xls) atau CSV.',
        ]);

        Excel::import(new DaftarBahanImport, $request->file('file'));

        return redirect()->route('b/daftar_bahan')->with(['message' => 'Bahan berhasil diimpor']);
    }
}
