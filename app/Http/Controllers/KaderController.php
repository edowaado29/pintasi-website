<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KaderController extends Controller
{
    public function b_kader()
    {
        $kaders = User::where('role', 'kader')->latest()->paginate(40)->withQueryString();
        return view('puskesmas.kader.main-kader', compact('kaders'));
    }

    public function b_detail_kader(string $id)
    {
        $kaders = User::findOrFail($id);
        return view('puskesmas.kader.detail-kader', compact('kaders'));
    }

    public function b_tambah_kader()
    {
        return view('puskesmas.kader.tambah-kader');
    }

    public function b_add_kader(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|in:bidan,kader',
                'no_hp' => 'nullable|string|max:20',
                'alamat' => 'nullable|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong.',
                'email.required' => 'Email tidak boleh kosong.',
                'password.required' => 'Password tidak boleh kosong.',
                'role.required' => 'Role tidak boleh kosong.',
                'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
                'alamat.max' => 'Alamat maksimal 255 karakter.',
                'foto.image' => 'File yang diunggah harus berupa gambar.',
                'foto.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
                'foto.max' => 'Ukuran gambar maksimal 2MB.',
            ]
        );

        $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('public/users') : null;
        $foto = $fotoPath ? $request->file('foto')->hashName() : null;

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'foto' => $foto,
        ]);

        return redirect()->route('b/kader')->with(['message' => 'Kader berhasil ditambahkan']);
    }

    public function b_edit_kader(string $id)
    {
        $kaders = User::findOrFail($id);
        return view('puskesmas.kader.edit-kader', compact('kaders'));
    }

    public function b_update_kader(Request $request, $id): RedirectResponse
    {

        $request->validate(
            [
                'nama' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8',
                'role' => 'required|in:bidan,kader',
                'no_hp' => 'nullable|string|max:20',
                'alamat' => 'nullable|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong.',
                'email.required' => 'Email tidak boleh kosong.',
                'password.min' => 'Password minimal 8 karakter.',
                'role.required' => 'Role tidak boleh kosong.',
                'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
                'alamat.max' => 'Alamat maksimal 255 karakter.',
                'foto.image' => 'File yang diunggah harus berupa gambar.',
                'foto.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
                'foto.max' => 'Ukuran gambar maksimal 2MB.',
            ]
        );

        $kaders = User::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($kaders->foto) {
                Storage::delete('public/users/' . $kaders->foto);
            }

            $foto = $request->file('foto')->hashName();
            $request->file('foto')->storeAs('public/users', $foto);
            $kaders->foto = $foto;
        }

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $kaders->update($data);

        return redirect()->route('b/kader')->with(['message' => 'Data berhasil diperbarui']);
    }

    public function b_hapus_kader(string $id): RedirectResponse
    {
        $kaders = User::findOrFail($id);
        if ($kaders->foto && Storage::exists('public/users/' . $kaders->foto)) {
            Storage::delete('public/users/' . $kaders->foto);
        }
        $kaders->delete();
        return redirect()->route('b/kader')->with(['message' => 'Data berhasil dihapus']);
    }
}
