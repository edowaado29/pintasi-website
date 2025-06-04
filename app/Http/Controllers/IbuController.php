<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ibu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IbuController extends Controller
{
    public function b_ibu()
    {
        $ibus = Ibu::all();
        return view('puskesmas.ibu.main-ibu', compact('ibus'));
    }

    public function b_detail_ibu(string $id)
    {
        $ibus = Ibu::findOrFail($id);
        return view('puskesmas.ibu.detail-ibu', compact('ibus'));
    }

    public function b_tambah_ibu()
    {
        return view('puskesmas.ibu.tambah-ibu');
    }

    public function b_add_ibu(Request $request)
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
        return redirect()->route('b/ibu')->with(['message' => 'Ibu berhasil ditambahkan']);
    }

    public function b_edit_ibu(string $id)
    {
        $ibus = Ibu::findOrFail($id);
        return view('puskesmas.ibu.edit-ibu', compact('ibus'));
    }
    public function b_update_ibu(Request $request, string $id)
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

        return redirect()->route('b/ibu')->with(['message' => 'Data berhasil diperbarui']);
    }

    public function b_hapus_ibu(string $id)
    {
        $ibus = Ibu::findOrFail($id);
        if ($ibus->foto && Storage::exists('public/ibus/' . $ibus->foto)) {
            Storage::delete('public/ibus/' . $ibus->foto);
        }
        $ibus->delete();
        return redirect()->route('b/ibu')->with(['message' => 'Data berhasil dihapus']);
    }


    //kader
    public function k_ibu()
    {
        $ibus = Ibu::all();
        return view('kader.ibu.main-ibu', compact('ibus'));
    }

    public function k_detail_ibu(string $id)
    {
        $ibus = Ibu::findOrFail($id);
        return view('kader.ibu.detail-ibu', compact('ibus'));
    }

    public function k_tambah_ibu()
    {
        return view('kader.ibu.tambah-ibu');
    }

    public function k_add_ibu(Request $request)
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
        return redirect()->route('k/ibu')->with(['message' => 'Ibu berhasil ditambahkan']);
    }

    public function k_edit_ibu(string $id)
    {
        $ibus = Ibu::findOrFail($id);
        return view('kader.ibu.edit-ibu', compact('ibus'));
    }
    public function k_update_ibu(Request $request, string $id)
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

        return redirect()->route('k/ibu')->with(['message' => 'Data berhasil diperbarui']);
    }

    public function k_hapus_ibu(string $id)
    {
        $ibus = Ibu::findOrFail($id);
        if ($ibus->foto && Storage::exists('public/ibus/' . $ibus->foto)) {
            Storage::delete('public/ibus/' . $ibus->foto);
        }
        $ibus->delete();
        return redirect()->route('k/ibu')->with(['message' => 'Data berhasil dihapus']);
    }
}

