<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ibu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show()
    {
        $ibus = Auth::user();
        return response()->json([
            'data' => $ibus,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'nama_ibu' => 'required|string',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'nullable|date',
                'alamat' => 'required|string',
                'telepon' => 'required|digits:12',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // validasi foto
            ]);

            $ibus = Auth::user();
            $ibus->nama_ibu = $request->nama_ibu;
            $ibus->tempat_lahir = $request->tempat_lahir;
            $ibus->tanggal_lahir = $request->tanggal_lahir;
            $ibus->alamat = $request->alamat;
            $ibus->telepon = $request->telepon;

            // Memproses file gambar jika ada
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                // Membuat nama file unik
                $namaFile = "img_" . Str::random(10) . time() . '.' . $foto->getClientOriginalExtension();
                // Menyimpan gambar ke folder public/images/
                $foto->move(public_path('images/'), $namaFile);

                // Menyimpan path gambar di database
                $ibus->foto = 'images/' . $namaFile; // menyimpan path relatif
            }

            // $ibus->save();
            Log::info('Data Ibu updated:', ['ibu' => $ibus]);

            return response()->json([
                'message' => 'Profile berhasil diperbarui',
                'data' => $ibus,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showbayi()
    {
        $akun = Auth::user();

        // Mengambil bayi yang dimiliki oleh akun ini
        $bayis = $akun->bayis;  // Relasi dari model Akun

        return response()->json([
            'data' => $akun,
            'bayis' => $bayis,  // Menambahkan data bayi
        ]);
    }
}
