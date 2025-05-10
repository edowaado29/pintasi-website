<?php

namespace App\Http\Controllers;

use App\Models\Bayi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BayiController extends Controller
{
    public function bayi(): View
    {
        $bayis = Bayi::latest()->paginate(10);
        return view('puskesmas.bayi.main-bayi', compact('bayis'));
    }

    public function detail_bayi(string $id): View
    {
        $bayis = Bayi::findOrFail($id);
        return view('puskesmas.bayi.detail-bayi', compact('bayis'));
    }

    public function hapus_bayi(string $id): RedirectResponse
    {
        $bayis = Bayi::findOrFail($id);

        if ($bayis->foto_bayi) {
            Storage::delete('public/bayis/' . $bayis->foto_bayi);
        }

        $bayis->delete();
        return redirect()->route('bayi')->with(['message' => 'Bayi berhasil dihapus']);
    }

    public function trashed(): View
    {
        $bayis = Bayi::onlyTrashed()->get();
        return view('bayis.trashed', compact('bayis'));
    }

    public function restore(string $id): RedirectResponse
    {
        $bayis = Bayi::onlyTrashed()->where('id', $id)->firstOrFail();
        $bayis->restore();

        return redirect()->route('bayis.index')->with(['message' => 'Data bayi berhasil dipulihkan']);
    }

    public function list()
    {
        return Bayi::select('id', 'nama_bayi', 'jenis_kelamin', 'tanggal_lahir')->get();
    }

    //Json
    public function apiIndex(): JsonResponse
{
    $bayis = Bayi::select('id', 'nama_bayi', 'jenis_kelamin', 'tanggal_lahir')->get();

    // foreach ($bayis as $bayi) {
    //     $bayi->umur = round(Carbon::parse($bayi->tanggal_lahir)->floatDiffInMonths(now()));
    // }

    return response()->json($bayis);
}


public function apiStore(Request $request): JsonResponse
{
    $request->validate([
        'id_ibu' => 'required|exists:ibus,id',
        'no_kk' => 'nullable|string|max:16',
        'nik_bayi' => 'required|string|max:16|unique:bayis,nik_bayi',
        'nama_bayi' => 'required|string|max:255',
        'tempat_lahir' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|string|max:10',
        'nama_ayah' => 'nullable|string|max:255',
        'nama_ibu' => 'nullable|string|max:255',
        'foto_bayi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $data = $request->only([
        'id_ibu', 'no_kk', 'nik_bayi', 'nama_bayi', 'tempat_lahir',
        'tanggal_lahir', 'jenis_kelamin', 'nama_ayah', 'nama_ibu'
    ]);

    if ($request->hasFile('foto_bayi')) {
        $file = $request->file('foto_bayi');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/bayis', $filename);
        $data['foto_bayi'] = $filename;
    }

    $bayi = Bayi::create($data);

    return response()->json([
        'message' => 'Data bayi berhasil ditambahkan.',
        'data' => $bayi
    ], 201);
}


//API Mobile
    public function index()
    {
        try {
            $data = Bayi::all(); // atau query lain
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
                'nik_bayi' => 'required|string|max:16|unique:bayis', // ganti 'nik' dengan 'nik_anak'
                'nama_bayi' => 'required|string|max:255',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|string|max:10',
                'nama_ayah' => 'nullable|string|max:255',
                'nama_ibu' => 'nullable|string|max:255',
                'foto_bayi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Ambil data dari request
            $data = $request->only([
                'no_kk',
                'nik_bayi', // ganti dengan 'nik_anak'
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
}
