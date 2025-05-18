<?php

namespace App\Http\Controllers;

use App\Models\BahanResep;
use App\Models\Resep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResepController extends Controller
{
    public function resep()
    {
        $reseps = Resep::latest()->paginate(10);
        return view('puskesmas.resep.main-resep', compact('reseps'));
    }

    public function detail_resep(string $id)
    {
        $reseps = Resep::findOrFail($id);
        return view('puskesmas.resep.detail-resep', compact('reseps'));
    }
    public function tambah_resep()
    {
        return view('puskesmas.resep.tambah-resep');
    }
    public function add_resep(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_resep' => 'required|string|max:255',
            'langkah' => 'required|string|max:255',
            'jumlah_porsi' => 'required|integer',
            'min_usia' => 'required|integer',
            'max_usia' => 'required|integer',
            'total_kalori' => 'required|integer',
            'total_protein' => 'required|integer',
            'total_lemak' => 'required|integer',
            'gambar_resep' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_bahan' => 'required|array',
            'nama_bahan.*' => 'required|string|max:255',
            'berat' => 'required|array',
            'berat.*' => 'required|integer',
            'satuan_berat' => 'required|array',
            'satuan_berat.*' => 'required|string|max:255',
        ], [
            'nama_resep.required' => 'Nama Resep tidak boleh kosong.',
            'langkah.required' => 'Langkah tidak boleh kosong.',
            'jumlah_porsi.required' => 'Jumlah Porsi tidak boleh kosong.',
            'min_usia.required' => 'Usia minimum tidak boleh kosong.',
            'max_usia.required' => 'Usia maksimum tidak boleh kosong.',
            'total_kalori.required' => 'Total kalori tidak boleh kosong.',
            'total_protein.required' => 'Total protein tidak boleh kosong.',
            'total_lemak.required' => 'Total lemak tidak boleh kosong.',
            'gambar_resep.image' => 'File yang diunggah harus berupa gambar.',
            'gambar_resep.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
            'gambar_resep.max' => 'Ukuran gambar maksimal 2MB.',
            'nama_bahan.required' => 'Nama Bahan tidak boleh kosong.',
            'berat.required' => 'Berat tidak boleh kosong.',
            'satuan_berat.required' => 'Satuan berat tidak boleh kosong.',
        ]);

        $fotoPath = $request->hasFile('gambar_resep') ? $request->file('gambar_resep')->store('public/reseps') : null;
        $gambarResep = $fotoPath ? $request->file('gambar_resep')->hashName() : null;

        $reseps = Resep::create([
            'nama_resep' => $request->nama_resep,
            'langkah' => $request->langkah,
            'jumlah_porsi' => $request->jumlah_porsi,
            'min_usia' => $request->min_usia,
            'max_usia' => $request->max_usia,
            'total_kalori' => $request->total_kalori,
            'total_protein' => $request->total_protein,
            'total_lemak' => $request->total_lemak,
            'gambar_resep' => $gambarResep,
        ]);

        // Simpan bahan-bahan resep
        foreach ($request->nama_bahan as $i => $nama_bahan) {
            BahanResep::create([
                'id_resep' => $reseps->id,
                'nama_bahan' => $nama_bahan,
                'berat' => $request->berat[$i],
                'satuan_berat' => $request->satuan_berat[$i],
            ]);
        }

        return redirect()->route('resep')->with(['message' => 'Resep berhasil ditambahkan']);
    }
    public function edit_resep(string $id)
    {
        $reseps = Resep::findOrFail($id);
        $bahans = BahanResep::where('id_resep', $id)->get();
        return view('puskesmas.resep.edit-resep', compact('reseps', 'bahans'));
    }

    public function update_resep(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'nama_resep' => 'required|string|max:255',
            'langkah' => 'required|string|max:255',
            'jumlah_porsi' => 'required|integer',
            'min_usia' => 'required|integer',
            'max_usia' => 'required|integer',
            'total_kalori' => 'required|integer',
            'total_protein' => 'required|integer',
            'total_lemak' => 'required|integer',
            'gambar_resep' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_bahan' => 'required|array',
            'nama_bahan.*' => 'required|string|max:255',
            'berat' => 'required|array',
            'berat.*' => 'required|integer',
            'satuan_berat' => 'required|array',
            'satuan_berat.*' => 'required|string|max:255',
        ]);

        $reseps = Resep::findOrFail($id);

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
            'total_kalori' => $request->total_kalori,
            'total_protein' => $request->total_protein,
            'total_lemak' => $request->total_lemak,
            'gambar_resep' => $reseps->gambar_resep,
        ]);

        BahanResep::where('id_resep', $id)->delete();
        foreach ($request->nama_bahan as $i => $nama_bahan) {
            BahanResep::create([
                'id_resep' => $id,
                'nama_bahan' => $nama_bahan,
                'berat' => $request->berat[$i],
                'satuan_berat' => $request->satuan_berat[$i],
            ]);
        }

        return redirect()->route('resep')->with(['message' => 'Resep berhasil diperbarui']);
    }


    public function hapus_resep($id): RedirectResponse
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
        return redirect()->route('resep')->with(['message' => 'Resep berhasil dihapus']);
    }
}
