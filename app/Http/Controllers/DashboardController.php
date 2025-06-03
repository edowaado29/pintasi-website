<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Bayi;
use App\Models\Ibu;
use App\Models\Jadwal;
use App\Models\Pemeriksaan;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function b_dashboard()
    {
        // $total_lk = Bayi::where('jenis_kelamin', 'Laki-laki')->count();
        // $total_pr = Bayi::where('jenis_kelamin', 'Perempuan')->count();
        // $total_bayi = Bayi::count();
        $count_kader = User::where('role', 'kader')->count();
        $count_ibu = Ibu::count();
        $count_bayi = Bayi::count();
        $count_resep = Resep::count();

        $grafBulan = Pemeriksaan::select(
            DB::raw('MONTH(tgl_periksa) as bulan'), 
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('tgl_periksa', date('Y'))
            ->groupBy(DB::raw('MONTH(tgl_periksa)'))
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataBar = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataBar[] = $grafBulan[$i] ?? 0;
        }

        $artikel = Artikel::All();

        return view('puskesmas.dashboard.dashboard', compact('count_kader', 'count_ibu', 'count_bayi', 'count_resep', 'dataBar', 'labels', 'artikel'));
    }

    public function k_dashboard()
    {
        $user = Auth::user();
        $count_ibu = Ibu::count();
        $count_bayi = Bayi::count();

        $grafBulan = Pemeriksaan::select(
            DB::raw('MONTH(tgl_periksa) as bulan'), 
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('tgl_periksa', date('Y'))
            ->groupBy(DB::raw('MONTH(tgl_periksa)'))
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataBar = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataBar[] = $grafBulan[$i] ?? 0;
        }

        $jadwals = Jadwal::all();

        return view('kader.dashboard.dashboard', compact( 'user','count_ibu', 'count_bayi', 'dataBar', 'labels', 'jadwals'));
    }
}
