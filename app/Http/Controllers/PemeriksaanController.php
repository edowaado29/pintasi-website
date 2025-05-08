<?php

namespace App\Http\Controllers;

use App\Models\Bayi;
use App\Models\Pemeriksaan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{    
    public function pemeriksaan()
    {
        $pemeriksaans = Pemeriksaan::with('bayi')->get();

        foreach ($pemeriksaans as $pemeriksaan) {
            if ($pemeriksaan->bayi && $pemeriksaan->bayi->tanggal_lahir && $pemeriksaan->tanggal_pemeriksaan) {
                $pemeriksaan->umur = Carbon::parse($pemeriksaan->bayi->tanggal_lahir)
                    ->diffInMonths(Carbon::parse($pemeriksaan->tanggal_pemeriksaan));
            } else {
                $pemeriksaan->umur = null;
            }
        }
        return view('puskesmas.pemeriksaan.main-pemeriksaan', compact('pemeriksaans'));
    }

    public function tambah_pemeriksaan(Request $request): View
    {
        $bayi = Bayi::findOrFail(id: $request->bayi_id);
        return view('puskesmas.pemeriksaan.tambah-pemeriksaan', compact('bayi'));
    }
    
    public function periksa(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bayi_id' => 'required|exists:bayis,id',
            'tanggal_pemeriksaan' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'lingkar_kepala' => 'required|numeric',
            'catatan' => 'required|string|max:255',
            'status_gizi' => 'required|string|max:255',
            'kalori' => 'required|integer',
            'protein' => 'required|integer',
            'lemak' => 'required|integer',
            'karbohidrat' => 'required|integer',
            'serat' => 'required|integer',
        ]);

        Pemeriksaan::create($validated);

        return redirect()->route('puskesmas.pemeriksaan.main-pemeriksaan')->with(['message' => 'Data berhasil ditambahkan']);
    }

    public function detail_pemeriksaan(string $id): View
    {
        $pemeriksaans = Pemeriksaan::findorfail($id);
        return view('puskesmas.pemeriksaan.detail-pemeriksaan', compact('pemeriksaans'));
    }

    public function edit_pemeriksaan(): View
    {
        return view('puskesmas.pemeriksaan.edit-pemeriksaan');
    }

    public function hapus_pemeriksaan(string $id): RedirectResponse
    {
        $pemeriksaans = Pemeriksaan::findOrFail($id);
        $pemeriksaans->delete();

        return redirect()->route('puskesmas.pemeriksaan.main-pemeriksaan')->with(['message' => 'Data berhasil dihapus']);
    }

    public function searchBayi(Request $request)
    {
        $search = $request->get('term');

    $result = Bayi::where('nama_bayi', 'like', '%' . $search . '%')->get();

    $data = [];
    foreach ($result as $bayi) {
        $data[] = [
            'id' => $bayi->id,
            'label' => $bayi->nama_bayi,
            'value' => $bayi->nama_bayi,
        ];
    }

    return response()->json($data);
}
    //     $term = $request->get('term');

    //     $bayis = Bayi::where('nama_bayi', 'like', '%' . $term . '%')->get();

    //     return response()->json($bayis->map(function ($bayi) {
    //         return [
    //             'id' => $bayi->id,
    //             'label' => $bayi->nama_bayi
    //         ];
    //     }));
    // }













    //Json
    public function apiIndex(): JsonResponse
    {
        $pemeriksaans = Pemeriksaan::with('bayi')->get();

        foreach ($pemeriksaans as $pemeriksaan) {
            if ($pemeriksaan->bayi && $pemeriksaan->bayi->tanggal_lahir) {
                $umurFloat = Carbon::parse($pemeriksaan->bayi->tanggal_lahir)
                    ->floatDiffInMonths(Carbon::parse($pemeriksaan->tanggal_pemeriksaan));
                $pemeriksaan->umur = round($umurFloat); // Bisa pakai floor(), ceil(), atau round()
            } else {
                $pemeriksaan->umur = null;
            }
        }

        return response()->json($pemeriksaans);
    }


    public function apiStore(Request $request): JsonResponse
    {
        $request->validate([
            'bayi_id' => 'required|exists:bayis,id',
            'tanggal_pemeriksaan' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'lingkar_kepala' => 'required|numeric',
            'catatan' => 'required|string|max:255',
            'status_gizi' => 'required|string|max:255',
            'kalori' => 'required|integer',
            'protein' => 'required|integer',
            'lemak' => 'required|integer',
            'karbohidrat' => 'required|integer',
            'serat' => 'required|integer',
        ]);

        $pemeriksaan = Pemeriksaan::create($request->all());

        return response()->json([
            'message' => 'Data pemeriksaan berhasil ditambahkan.',
            'data' => $pemeriksaan
        ], 201);
    }
}
