<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ibu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IbuController extends Controller
{
    public function ibu()
    {
        $ibus = Ibu::Latest()->paginate(10);
        return view('puskesmas.ibu.main-ibu', compact('ibus'));
    }

    public function detail_ibu(string $id)
    {
        $ibus = Ibu::findOrFail($id);
        return view('puskesmas.ibu.detail-ibu', compact('ibus'));
    }

    public function tambah_ibu()
    {
        return view('puskesmas.ibu.tambah-ibu');
    }

    public function add_ibu(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|unique:ibus,email',
                'password' => 'required|min:8',
                'nik' => 'nullable|string|max:16|unique:ibus',
                'nama_ibu' => 'required|string|max:255',
                'tempat_lahir' => 'nullable|string|max:255',
                'tanggal_lahir' => 'nullable|date',
                'alamat' => 'nullable|string|max:255',
                'telepon' => 'nullable|string|max:20',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'email.required' => 'Email tidak boleh kosong.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password tidak boleh kosong.',
                'nik.unique' => 'NIK sudah terdaftar.',
                'nama_ibu.required' => 'Nama Ibu tidak boleh kosong.',
                'foto.image' => 'File yang diunggah harus berupa gambar.',
                'foto.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
                'foto.max' => 'Ukuran gambar maksimal 2MB.',
            ]
        );

        $fotopath = $request->hasFile('foto') ? $request->file('foto')->store('public/ibus') : null;
        $foto = $fotopath ? $request->file('foto')->hashName() : null;

        Ibu::create([
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
            'nik'=> $request->nik,
            'nama_ibu'=> $request->nama_ibu,
            'tempat_lahir'=> $request->tempat_lahir,
            'tanggal_lahir'=> $request->tanggal_lahir,
            'alamat'=> $request->alamat,
            'telepon'=> $request->telepon,
            'foto'=> $foto
        ]);
        return redirect()->route('ibu')->with(['message' => 'Ibu berhasil ditambahkan']);
    }

    public function edit_ibu(string $id)
    {
        $ibus = Ibu::findOrFail($id);
        return view('puskesmas.ibu.edit-ibu', compact('ibus'));
    }
    public function update_ibu(Request $request, string $id)
    {
        $request->validate(
            [
                'email' => 'required|email|unique:ibus,email,' . $id,
                'nik' => 'nullable|string|max:16|unique:ibus,nik,' . $id,
                'nama_ibu' => 'required|string|max:255',
                'tempat_lahir' => 'nullable|string|max:255',
                'tanggal_lahir' => 'nullable|date',
                'alamat' => 'nullable|string|max:255',
                'telepon' => 'nullable|string|max:20',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'email.required' => 'Email tidak boleh kosong.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'nik.unique' => 'NIK sudah terdaftar.',
                'nama_ibu.required' => 'Nama Ibu tidak boleh kosong.',
                'foto.image' => 'File yang diunggah harus berupa gambar.',
                'foto.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
                'foto.max' => 'Ukuran gambar maksimal 2MB.',
            ]
        );

        $ibus = Ibu::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($ibus->foto) {
                Storage::delete('public/ibus/' . $ibus->foto);
            }

            $foto = $request->file('foto')->hashName();
            $request->file('foto')->storeAs('public/ibus', $foto);
            $ibus->foto = $foto;
        }

        $data = [
            'email' => $request->email,
            'nik' => $request->nik,
            'nama_ibu' => $request->nama_ibu,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $ibus->update($data);

        return redirect()->route('ibu')->with(['message' => 'Data berhasil diperbarui']);
    }

    public function hapus_ibu(string $id)
    {
        $ibus = Ibu::findOrFail($id);
        if ($ibus->foto && Storage::exists('public/ibus/' . $ibus->foto)) {
            Storage::delete('public/ibus/' . $ibus->foto);
        }
        $ibus->delete();
        return redirect()->route('ibu')->with(['message' => 'Data berhasil dihapus']);
    }
}
