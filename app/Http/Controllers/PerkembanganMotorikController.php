<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bayi;
use Illuminate\Http\Request;
use App\Models\Motorik;
use App\Models\PerkembanganMotorik;

class PerkembanganMotorikController extends Controller
{
    // menampilkan milestone motorik berdasarkan usia bayi
    public function milestoneByAge($id_bayi)
    {
        $bayi = Bayi::findOrFail($id_bayi);
        $usiaBulan = Carbon::parse($bayi->tanggal_lahir)->diffInMonths(now());

        $milestones = Motorik::where('min_usia', '<=', $usiaBulan)
                        ->where('max_usia', '>=', $usiaBulan)
                        ->get();
        
        $checked = PerkembanganMotorik::where('id_bayi', $id_bayi)
                    ->pluck('id_motorik')->toArray();

        $data = $milestones->map(function ($item) use ($checked) {
            return [
                'id' => $item->id,
                'capaian_motorik' => $item->capaian_motorik,
                'min_usia' => $item->min_usia,
                'max_usia' => $item->max_usia,
                'diceklis' => in_array($item->id, $checked),
            ];
        });

        return response()->json($data);
    }

    // menampilkan semua milestone bayi, dikelompokkan berdasarkan rentang usia
    public function semuaMilestoneBayi($id_bayi)
    {
        $bayi = Bayi::findOrFail($id_bayi);
        $usiaBulan = Carbon::parse($bayi->tanggal_lahir)->diffInMonths(now());

        $milestones = Motorik::orderBy('min_usia')->get();
        $checked = PerkembanganMotorik::where('id_bayi', $id_bayi)
                    ->pluck('id_motorik')->toArray();

        // Kelompokkan berdasarkan rentang usia
        $grouped = $milestones->groupBy(function ($item) {
            return $item->min_usia . ' - ' . $item->max_usia . ' bulan';
        });

        $result = [];

        foreach ($grouped as $rentang => $items) {
            $usiaMin = $items->first()->min_usia;
            $usiaMax = $items->first()->max_usia;

            $result[] = [
                'rentang_usia' => $rentang,
                'min_usia' => $usiaMin,
                'max_usia' => $usiaMax,
                'is_accessible' => $usiaBulan >= $usiaMin,
                'milestones' => $items->map(function ($item) use ($checked) {
                    return [
                        'id' => $item->id,
                        'capaian_motorik' => $item->capaian_motorik,
                        'diceklis' => in_array($item->id, $checked),
                    ];
                })->values()
            ];
        }

        return response()->json($result);
    }

    // simpan ceklis milestone
    public function ceklis(Request $request)
    {
        $request->validate([
            'id_bayi' => 'required|exists:bayis,id',
            'id_motorik' => 'required|exists:motoriks,id',
        ]);

        $bayi = Bayi::findOrFail($request->id_bayi);
        $usiaBulan = Carbon::parse($bayi->tanggal_lahir)->diffInMonths(now());

        $milestone = Motorik::findOrFail($request->id_motorik);

        // validasi milestone berdasarkan usia bayi
        if ($usiaBulan < $milestone->min_usia) {
            return response()->json([
                'message' => 'Capaian ini belum bisa diceklis karena belum sesuai usia bayi'
            ], 400);
        }

        // cek apakah sudah diceklis
        $exists = PerkembanganMotorik::where('id_bayi', $request->id_bayi)
            ->where('id_motorik', $request->id_motorik)
            ->first();

        if (!$exists) {
            PerkembanganMotorik::create($request->all());
        }

        return response()->json(['message' => 'Ceklis disimpan']);
    }

    // hapus ceklis milestone
    public function uncheck(Request $request)
    {
        $request->validate([
            'id_bayi' => 'required|exists:bayis,id',
            'id_motorik' => 'required|exists:motoriks,id',
        ]);

        $deleted = PerkembanganMotorik::where('id_bayi', $request->id_bayi)
                                    ->where('id_motorik', $request->id_motorik)
                                    ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Ceklis dihapus']);
        } else {
            return response()->json(['message' => 'Tidak ada data yang dihapus'], 404);
        }
    }
}