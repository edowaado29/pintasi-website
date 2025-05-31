<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ibu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function profil(){
        $user = Auth::user();
        return view('profil.profil', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $id_user = Auth::user();
        $user = User::findOrFail($id_user);
        
        $user->update([
            // 'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);
        return redirect()->route('profil')->with(['message' => 'Data berhasil diperbarui']);
    }

    public function uploadImg(Request $request)
    {
        $request->validate([
            'foto' => 'image|mimes:jpeg,jpg,png|max:2048'
        ],
        [
            'foto.image' => 'Format foto profil salah',
            'foto.max' => 'Ukuran foto profil terlalu besar'
        ]);

        $id_user = Auth::user();
        $user = User::findOrFail($id_user);
        
        $foto = $request->file('foto');
        $foto->storeAs('public/users', $foto->hashName());
        Storage::delete('public/users/' . $user->foto);
        
        $user->update([
            'foto' => $foto->hashName()
        ]);

        return redirect()->route('profil')->with(['message' => 'Foto profile berhasil diedit']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|max:16'
        ],
        [
            'password.required' => 'Password harus diisi!',
            'password.min' => 'Password harus terdiri dari 8-16 karakter',
            'password.max' => 'Password harus terdiri dari 8-16 karakter'
        ]);

        $id_user = Auth::user();
        $user = User::findOrFail($id_user);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profil')->with(['message' => 'Password berhasil diedit']);
    }


    //API Mobile
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
            'telepon' => 'required|regex:/^[0-9]{10,15}$/', // Validasi telepon (10-15 digit)
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto
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
            $namaFile = "img_" . Str::random(10) . time() . '.' . $foto->getClientOriginalExtension();

            // Hapus file lama jika ada
            if ($ibus->foto && file_exists(public_path($ibus->foto))) {
                unlink(public_path($ibus->foto));
            }

            // Simpan file baru
            $foto->move(public_path('images/'), $namaFile);
            $ibus->foto = 'images/' . $namaFile;
        }

        $ibus->save();

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
}
