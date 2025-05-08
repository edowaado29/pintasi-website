<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    public function artikel(): View
    {
        $artikels = Artikel::all();
        return view('puskesmas.artikel.main-artikel', compact('artikels'));
    }

    public function detail_artikel(string $id): View
    {
        $artikels = Artikel::findOrFail($id);
        return view('puskesmas.artikel.detail-artikel', compact('artikels'));
    }

    public function tambah_artikel(): View
    {
        return view('puskesmas.artikel.tambah-artikel');
    }

    public function add_artikel(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required' => 'Judul artikel tidak boleh kosong.',
            'konten.required' => 'Konten artikel tidak boleh kosong.',
            'gambar.required' => 'Gambar artikel tidak boleh kosong.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $gambar = $request->file('gambar');
        $gambarPath = $gambar->storeAs('public/artikels', $gambar->hashName());
        
        Artikel::create([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'gambar' => basename($gambarPath),
        ]);

        return redirect()->route('artikel')->with(['message' => 'Artikel Berhasil Ditambahkan']);
    }

    public function edit_artikel(string $id): View
    {
        $artikels = Artikel::findOrFail($id);
        return view('puskesmas.artikel.edit-artikel', compact('artikels'));
    }

    public function update_artikel(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required' => 'Judul artikel tidak boleh kosong.',
            'konten.required' => 'Konten artikel tidak boleh kosong.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $artikels = Artikel::findOrFail($id);

        if ($request->hasFile('gambar')) {
            if ($artikels->gambar) {
                Storage::delete('public/artikels/' . $artikels->gambar);
            }

            $gambar = $request->file('gambar');
            $gambarPath = $gambar->storeAs('public/artikels', $gambar->hashName());
            $gambarName = basename($gambarPath); 
            
            $artikels->update([
                'judul' => $request->judul,
                'konten' => $request->konten,
                'gambar' => $gambarName,
            ]);
        } else {
            $artikels->update([
                'judul' => $request->judul,
                'konten' => $request->konten,
            ]);
        }

        return redirect()->route('artikel')->with(['message' => 'Artikel berhasil diperbarui']);
    }

    public function hapus_artikel(string $id): RedirectResponse
    {
        $artikels = Artikel::findOrFail($id);

        if ($artikels->gambar) {
            Storage::delete('public/artikels/' . $artikels->gambar);
        }

        $artikels->delete();
        return redirect()->route('artikel')->with(['message' => 'Artikel berhasil dihapus']);
    }

    // API Mobile
    public function artikelIndex(): JsonResponse
    {
        $artikels = Artikel::all();
        return response()->json($artikels);
    }
}
