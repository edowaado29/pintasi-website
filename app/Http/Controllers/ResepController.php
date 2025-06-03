<?php

namespace App\Http\Controllers;

use App\Models\BahanResep;
use App\Models\DaftarBahan;
use App\Models\Resep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResepController extends Controller
{
    public function b_resep()
    {
        $reseps = Resep::all();
        return view('puskesmas.resep.main-resep', compact('reseps'));
    }

    public function b_detail_resep(string $id)
    {
        $reseps = Resep::findOrFail($id);
        $bahans = BahanResep::where('id_resep', $id)->get();
        $daftarBahans = DaftarBahan::all();
        return view('puskesmas.resep.detail-resep', compact('reseps', 'bahans'));
    }
    public function b_tambah_resep()
    {
        $daftarBahans = DaftarBahan::all();
        return view('puskesmas.resep.tambah-resep', compact('daftarBahans'));
    }
    public function b_add_resep(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_resep' => 'required|string|max:255',
            'langkah' => 'required|string',
            'jumlah_porsi' => 'required|integer',
            'min_usia' => 'required|integer',
            'max_usia' => 'required|integer',
            'gambar_resep' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_daftarBahan' => 'required|array|min:1',
            'id_daftarBahan.*' => 'required|exists:daftar_bahans,id',
            'berat' => 'required|array',
            'berat.*' => 'required|integer',
        ], [
            'nama_resep.required' => 'Nama Resep tidak boleh kosong.',
            'langkah.required' => 'Langkah tidak boleh kosong.',
            'jumlah_porsi.required' => 'Jumlah Porsi tidak boleh kosong.',
            'min_usia.required' => 'Usia minimum tidak boleh kosong.',
            'max_usia.required' => 'Usia maksimum tidak boleh kosong.',
            'gambar_resep.image' => 'File yang diunggah harus berupa gambar.',
            'gambar_resep.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
            'gambar_resep.max' => 'Ukuran gambar maksimal 2MB.',
            'id_bahan.required' => 'Nama Bahan tidak boleh kosong.',
            'berat.required' => 'Berat tidak boleh kosong.',
        ]);

        $total_kalori = 0;
        $total_protein = 0;
        $total_lemak = 0;
        $total_karbohidrat = 0;
        $total_serat = 0;

        foreach ($request->id_daftarBahan as $i => $id_daftarBahan) {
            $bahan = DaftarBahan::find($id_daftarBahan);
            $berat = $request->berat[$i];

            if ($bahan) {
                $total_kalori += ($bahan->kalori * $berat) / 100;
                $total_protein += ($bahan->protein * $berat) / 100;
                $total_lemak += ($bahan->lemak * $berat) / 100;
                $total_karbohidrat += ($bahan->karbohidrat * $berat) / 100;
                $total_serat += ($bahan->serat * $berat) / 100;
            }
        }

        $fotoPath = $request->hasFile('gambar_resep') ? $request->file('gambar_resep')->store('public/reseps') : null;
        $gambarResep = $fotoPath ? $request->file('gambar_resep')->hashName() : null;

        $reseps = Resep::create([
            'nama_resep' => $request->nama_resep,
            'langkah' => $request->langkah,
            'jumlah_porsi' => $request->jumlah_porsi,
            'min_usia' => $request->min_usia,
            'max_usia' => $request->max_usia,
            'total_kalori' => $total_kalori,
            'total_protein' => $total_protein,
            'total_lemak' => $total_lemak,
            'total_karbohidrat' => $total_karbohidrat,
            'total_serat' => $total_serat,
            'gambar_resep' => $gambarResep,
        ]);

        // Simpan bahan-bahan resep
        foreach ($request->id_daftarBahan as $i => $id_daftarBahan) {
            BahanResep::create([
                'id_resep' => $reseps->id,
                'id_daftarBahan' => $id_daftarBahan,
                'berat' => $request->berat[$i],
            ]);
        }

        return redirect()->route('b/resep')->with(['message' => 'Resep berhasil ditambahkan']);
    }
    public function b_edit_resep(string $id)
    {
        $reseps = Resep::findOrFail($id);
        $bahans = BahanResep::where('id_resep', $id)->get();
        $id_daftarBahan = $bahans->pluck('id_daftarBahan')->toArray();
        $berat = $bahans->pluck('berat')->toArray();
        $daftarBahans = DaftarBahan::all();
        return view('puskesmas.resep.edit-resep', compact('reseps', 'bahans', 'id_daftarBahan', 'berat', 'daftarBahans'));
    }

    public function b_update_resep(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'nama_resep' => 'required|string|max:255',
            'langkah' => 'required|string',
            'jumlah_porsi' => 'required|integer',
            'min_usia' => 'required|integer',
            'max_usia' => 'required|integer',
            'gambar_resep' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_daftarBahan' => 'required|array|min:1',
            'id_daftarBahan.*' => 'required|exists:daftar_bahans,id',
            'berat' => 'required|array',
            'berat.*' => 'required|integer',
        ]);

        $reseps = Resep::findOrFail($id);

        $total_kalori = 0;
        $total_protein = 0;
        $total_lemak = 0;
        $total_karbohidrat = 0;
        $total_serat = 0;

        foreach ($request->id_daftarBahan as $i => $id_daftarBahan) {
            $bahan = DaftarBahan::find($id_daftarBahan);
            $berat = $request->berat[$i];

            if ($bahan) {
                $total_kalori += ($bahan->kalori * $berat) / 100;
                $total_protein += ($bahan->protein * $berat) / 100;
                $total_lemak += ($bahan->lemak * $berat) / 100;
                $total_karbohidrat += ($bahan->karbohidrat * $berat) / 100;
                $total_serat += ($bahan->serat * $berat) / 100;
            }
        }

        if ($request->hasFile('gambar_resep')) {
            if ($reseps->gambar_resep) {
                Storage::delete('public/reseps/' . $reseps->gambar_resep);
            }
            $request->file('gambar_resep')->store('public/reseps');
            $reseps->gambar_resep = $request->file('gambar_resep')->hashName();
        }

        $reseps->update([
            'nama_resep' => $request->nama_resep,
            'langkah' => $request->langkah,
            'jumlah_porsi' => $request->jumlah_porsi,
            'min_usia' => $request->min_usia,
            'max_usia' => $request->max_usia,
            'total_kalori' => $total_kalori,
            'total_protein' => $total_protein,
            'total_lemak' => $total_lemak,
            'total_karbohidrat' => $total_karbohidrat,
            'total_serat' => $total_serat,
            'gambar_resep' => $reseps->gambar_resep,
        ]);

        BahanResep::where('id_resep', $id)->delete();
        foreach ($request->id_daftarBahan as $i => $id_daftarBahan) {
            BahanResep::create([
                'id_resep' => $id,
                'id_daftarBahan' => $id_daftarBahan,
                'berat' => $request->berat[$i],
            ]);
        }

        return redirect()->route('b/resep')->with(['message' => 'Resep berhasil diperbarui']);
    }


    public function b_hapus_resep($id): RedirectResponse
    {
        $reseps = Resep::findOrFail($id);
        $bahans = BahanResep::where('id_resep', $id)->get();

        if ($reseps->gambar_resep && Storage::exists('public/reseps/' . $reseps->gambar_resep)) {
            Storage::delete('public/reseps/' . $reseps->gambar_resep);
        }
        foreach ($bahans as $bahan) {
            $bahan->delete();
        }
        $reseps->delete();
        return redirect()->route('b/resep')->with(['message' => 'Resep berhasil dihapus']);
    }
}
