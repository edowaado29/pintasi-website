<?php

namespace App\Http\Controllers;

use App\Models\Bayi;
use App\Models\Ibu;
use App\Models\PerkembanganMotorik;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BayiController extends Controller
{
    public function b_bayi(): View
    {
        $bayis = Bayi::all();
        return view('puskesmas.bayi.main-bayi', compact('bayis'));
    }

    public function b_detail_bayi(string $id): View
    {
        $bayis = Bayi::with(['ibu', 'pemeriksaans' => function ($q) {
            $q->orderBy('tgl_periksa');
        }, 'perkembangan_motorik.motorik'])->findOrFail($id);

        $tglLahir = Carbon::parse($bayis->tanggal_lahir);

        $bb = $tb = $imt = array_fill(0, 25, null);

    foreach ($bayis->pemeriksaans as $p) {
        $bulan = $tglLahir->diffInMonths(Carbon::parse($p->tgl_periksa));
        if ($bulan >= 0 && $bulan <= 24) {
            $bb[$bulan] = $p->bb;
            $tb[$bulan] = $p->tb;
            $imt[$bulan] = $p->imt;
        }
    }

    $labels = range(0, 24);

    return view('puskesmas.bayi.detail-bayi', compact('bayis', 'labels', 'bb', 'tb', 'imt'));
}
    public function b_tambah_bayi(): View
    {
        $ibus = Ibu::all();
        return view('puskesmas.bayi.tambah-bayi', compact('ibus'));
    }

    public function b_add_bayi(Request $request)
    {
        $request->validate([
            'id_ibu' => 'required|exists:ibus,id',
            'no_kk' => 'nullable|string|max:16',
            'nik_bayi' => 'nullable|string|max:16|unique:bayis',
            'nama_bayi' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'foto_bayi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'id_ibu.required' => 'ID Ibu tidak boleh kosong.',
            'nik_bayi.required' => 'NIK Bayi tidak boleh kosong.',
            'nik_bayi.unique' => 'NIK Bayi sudah terdaftar.',
            'nama_bayi.required' => 'Nama Bayi tidak boleh kosong.',
            'tanggal_lahir.required' => 'Tanggal Lahir tidak boleh kosong.',
            'jenis_kelamin.required' => 'Jenis Kelamin tidak boleh kosong.'

        ]);

        $fotoPath = $request->hasFile('foto_bayi') ? $request->file('foto_bayi')->store('public/bayis') : null;
        $fotoBayi = $fotoPath ? $request->file('foto_bayi')->hashName() : null;

        $ibu = Ibu::find($request->id_ibu);

        Bayi::create([
            'id_ibu' => $request->id_ibu,
            'no_kk' => $request->no_kk,
            'nik_bayi' => $request->nik_bayi,
            'nama_bayi' => $request->nama_bayi,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $ibu ? $ibu->nama_ibu : null,
            'foto_bayi' => $fotoBayi
        ]);

        return redirect()->route('b/bayi')->with(['message' => 'Bayi berhasil ditambahkan']);
    }

    public function b_edit_bayi(string $id): View
    {
        $bayis = Bayi::findOrFail($id);
        $ibus = Ibu::all();
        return view('puskesmas.bayi.edit-bayi', compact('bayis', 'ibus'));
    }

    public function b_update_bayi(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'id_ibu' => 'required|exists:ibus,id',
            'no_kk' => 'nullable|string|max:16',
            'nik_bayi' => 'nullable|string|max:16',
            'nama_bayi' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki, Perempuan',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'foto_bayi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $bayis = Bayi::findOrFail($id);

        if ($request->hasFile('foto_bayi')) {
            if ($bayis->foto_bayi) {
                Storage::delete('public/bayis/' . $bayis->foto_bayi);
            }

            $fotoPath = $request->file('foto_bayi')->store('public/bayis');
            $bayis->foto_bayi = basename($fotoPath);
        }
        $ibu = Ibu::find($request->id_ibu);

        $data = [
            'id_ibu' => $request->id_ibu,
            'no_kk' => $request->no_kk,
            'nik_bayi' => $request->nik_bayi,
            'nama_bayi' => $request->nama_bayi,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $ibu ? $ibu->nama_ibu : null,
        ];

        $bayis->update($data);
        return redirect()->route('b/bayi')->with(['message' => 'Bayi berhasil diperbarui']);
    }

    public function b_hapus_bayi(string $id): RedirectResponse
    {
        $bayis = Bayi::findOrFail($id);

        if ($bayis->foto_bayi) {
            Storage::delete('public/bayis/' . $bayis->foto_bayi);
        }

        $bayis->delete();
        return redirect()->route('b/bayi')->with(['message' => 'Bayi berhasil dihapus']);
    }

    public function b_trashed(): View
    {
        $bayis = Bayi::onlyTrashed()->get();
        return view('bayis.trashed', compact('bayis'));
    }

    public function b_restore(string $id): RedirectResponse
    {
        $bayis = Bayi::onlyTrashed()->where('id', $id)->firstOrFail();
        $bayis->restore();

        return redirect()->route('b/bayi')->with(['message' => 'Data bayi berhasil dipulihkan']);
    }

    //kader
    public function k_bayi(): View
    {
        $bayis = Bayi::all();
        return view('kader.bayi.main-bayi', compact('bayis'));
    }

    public function k_detail_bayi(string $id): View
    {
        $bayis = Bayi::with(['ibu', 'pemeriksaans' => function ($q) {
            $q->orderBy('tgl_periksa');
        }, 'perkembangan_motorik.motorik'])->findOrFail($id);

        $tglLahir = Carbon::parse($bayis->tanggal_lahir);

        $bb = $tb = $imt = array_fill(0, 25, null);

    foreach ($bayis->pemeriksaans as $p) {
        $bulan = $tglLahir->diffInMonths(Carbon::parse($p->tgl_periksa));
        if ($bulan >= 0 && $bulan <= 24) {
            $bb[$bulan] = $p->bb;
            $tb[$bulan] = $p->tb;
            $imt[$bulan] = $p->imt;
        }
    }

    $labels = range(0, 24);

    return view('kader.bayi.detail-bayi', compact('bayis', 'labels', 'bb', 'tb', 'imt'));
}
    public function k_tambah_bayi(): View
    {
        $ibus = Ibu::all();
        return view('kader.bayi.tambah-bayi', compact('ibus'));
    }

    public function k_add_bayi(Request $request)
    {
        $request->validate([
            'id_ibu' => 'required|exists:ibus,id',
            'no_kk' => 'nullable|string|max:16',
            'nik_bayi' => 'nullable|string|max:16|unique:bayis',
            'nama_bayi' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'foto_bayi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'id_ibu.required' => 'ID Ibu tidak boleh kosong.',
            'nik_bayi.required' => 'NIK Bayi tidak boleh kosong.',
            'nik_bayi.unique' => 'NIK Bayi sudah terdaftar.',
            'nama_bayi.required' => 'Nama Bayi tidak boleh kosong.',
            'tanggal_lahir.required' => 'Tanggal Lahir tidak boleh kosong.',
            'jenis_kelamin.required' => 'Jenis Kelamin tidak boleh kosong.'

        ]);

        $fotoPath = $request->hasFile('foto_bayi') ? $request->file('foto_bayi')->store('public/bayis') : null;
        $fotoBayi = $fotoPath ? $request->file('foto_bayi')->hashName() : null;

        $ibu = Ibu::find($request->id_ibu);

        Bayi::create([
            'id_ibu' => $request->id_ibu,
            'no_kk' => $request->no_kk,
            'nik_bayi' => $request->nik_bayi,
            'nama_bayi' => $request->nama_bayi,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $ibu ? $ibu->nama_ibu : null,
            'foto_bayi' => $fotoBayi
        ]);

        return redirect()->route('k/bayi')->with(['message' => 'Bayi berhasil ditambahkan']);
    }

    public function k_edit_bayi(string $id): View
    {
        $bayis = Bayi::findOrFail($id);
        $ibus = Ibu::all();
        return view('kader.bayi.edit-bayi', compact('bayis', 'ibus'));
    }

    public function k_update_bayi(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'id_ibu' => 'required|exists:ibus,id',
            'no_kk' => 'nullable|string|max:16',
            'nik_bayi' => 'nullable|string|max:16',
            'nama_bayi' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki, Perempuan',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'foto_bayi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $bayis = Bayi::findOrFail($id);

        if ($request->hasFile('foto_bayi')) {
            if ($bayis->foto_bayi) {
                Storage::delete('public/bayis/' . $bayis->foto_bayi);
            }

            $fotoPath = $request->file('foto_bayi')->store('public/bayis');
            $bayis->foto_bayi = basename($fotoPath);
        }
        $ibu = Ibu::find($request->id_ibu);

        $data = [
            'id_ibu' => $request->id_ibu,
            'no_kk' => $request->no_kk,
            'nik_bayi' => $request->nik_bayi,
            'nama_bayi' => $request->nama_bayi,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $ibu ? $ibu->nama_ibu : null,
        ];

        $bayis->update($data);
        return redirect()->route('k/bayi')->with(['message' => 'Bayi _kaderberhasil diperbarui']);
    }

    public function k_hapus_bayi(string $id): RedirectResponse
    {
        $bayis = Bayi::findOrFail($id);

        if ($bayis->foto_bayi) {
            Storage::delete('public/bayis/' . $bayis->foto_bayi);
        }

        $bayis->delete();
        return redirect()->route('k/bayi')->with(['message' => 'Bayi berhasil dihapus']);
    }


    //API Mobile
    public function index()
    {
        try {
            $data = Bayi::all();
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data bayi', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'no_kk' => 'nullable|string|max:16',
                'nik_bayi' => 'required|string|max:16|unique:bayis',
                'nama_bayi' => 'required|string|max:255',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|string|max:10',
                'nama_ayah' => 'nullable|string|max:255',
                'nama_ibu' => 'nullable|string|max:255',
                'foto_bayi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Ambil data dari request
            $data = $request->only([
                'no_kk',
                'nik_bayi',
                'nama_bayi',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'nama_ayah',
                'nama_ibu',
            ]);

            // Menyimpan akun_id berdasarkan user yang sedang login
            $id_ibu = Auth::id();
            $data['id_ibu'] = $id_ibu;

            // Jika ada foto bayi
            if ($request->hasFile('foto_bayi')) {
                $foto = $request->file('foto_bayi');
                $path = $foto->store('public/bayis');
                $data['foto_bayi'] = basename($path);
            }

            // Simpan data bayi ke database
            $bayi = Bayi::create($data);

            return response()->json([
                'message' => 'Data Bayi berhasil ditambahkan',
                'data' => $bayi
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showBayis(Request $request)
    {
        // Mendapatkan informasi pengguna yang terautentikasi
        $user = $request->user();

        // Mengambil bayi berdasarkan user_id
        $bayis = Bayi::where('id_ibu', $user->id)->get();

        // Mengembalikan data bayi sebagai JSON
        return response()->json([
            'data' => $bayis,
        ]);
    }
}
