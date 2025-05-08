<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function jadwal(): View
    {
        $jadwals = Jadwal::latest('created_at')->paginate(10);
        return view('puskesmas.jadwal.main-jadwal', compact('jadwals'));
    }

    public function tambah_jadwal(): View
    {
        return view('puskesmas.jadwal.tambah-jadwal');
    }

    public function add_jadwal(Request $request): RedirectResponse
    {
        $request->validate([
            'tanggal_pemeriksaan' => 'required|date',
            'jam_pemeriksaan' => 'required|date_format:H:i',
            'jenis_pemeriksaan' => 'required|in:Imunisasi,Posyandu',
            'tempat' => 'required|string|max:255',
        ], [
            'tanggal_pemeriksaan.required' => 'Tanggal pemeriksaan tidak boleh kosong.',
            'jam_pemeriksaan.required' => 'Jam pemeriksaan tidak boleh kosong.',
            'jenis_pemeriksaan.required' => 'Jenis pemeriksaan tidak boleh kosong.',
            'jenis_pemeriksaan.in' => 'Jenis pemeriksaan harus berupa Imunisasi atau Posyandu.',
            'tempat.required' => 'Tempat pemeriksaan tidak boleh kosong.',
        ]);

        Jadwal::create([
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'jam_pemeriksaan' => $request->jam_pemeriksaan,
            'jenis_pemeriksaan' => $request->jenis_pemeriksaan,
            'tempat' => $request->tempat,
        ]);

        return redirect()->route('tambah_jadwal')->with(['message' => 'Jadwal berhasil ditambahkan']);
    }

    public function edit_jadwal(string $id): View
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('puskesmas.jadwal.edit-jadwal', compact('jadwal'));
    }

    public function update_jadwal(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'tanggal_pemeriksaan' => 'required|date',
            'jam_pemeriksaan' => 'required|date_format:H:i',
            'jenis_pemeriksaan' => 'required|in:Imunisasi,Posyandu',
            'tempat' => 'required|string|max:255',
        ], [
            'tanggal_pemeriksaan.required' => 'Tanggal pemeriksaan tidak boleh kosong.',
            'jam_pemeriksaan.required' => 'Jam pemeriksaan tidak boleh kosong.',
            'jenis_pemeriksaan.required' => 'Jenis pemeriksaan tidak boleh kosong.',
            'jenis_pemeriksaan.in' => 'Jenis pemeriksaan harus berupa Imunisasi atau Posyandu.',
            'tempat.required' => 'Tempat pemeriksaan tidak boleh kosong.',
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update([
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'jam_pemeriksaan' => $request->jam_pemeriksaan,
            'jenis_pemeriksaan' => $request->jenis_pemeriksaan,
            'tempat' => $request->tempat,
        ]);

        return redirect()->route('tambah_jadwal')->with(['message' => 'Jadwal berhasil diperbarui']);
    }

    public function hapus_jadwal(string $id): RedirectResponse
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        return redirect()->route('')->with(['message' => 'Jadwal berhasil dihapus']);
        
    }
}