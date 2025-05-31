<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bayi;
use App\Models\Pemeriksaan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    public function pemeriksaan(): View
    {
        $pemeriksaans = Pemeriksaan::all();
        $bayis = Bayi::all();
        return view('puskesmas.pemeriksaan.main-pemeriksaan', compact('pemeriksaans','bayis'));
    }
    
    public function tambah_pemeriksaan($id_bayi): View
    {
        $bayi = Bayi::findOrFail($id_bayi);
        $usia_bulan = Carbon::parse($bayi->tanggal_lahir)->diffInMonths(now());

        return view('puskesmas.pemeriksaan.tambah-pemeriksaan', compact('bayi', 'usia_bulan'));
    }

    public function store_pemeriksaan(Request $request): RedirectResponse
    {
        $request->validate([
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'lk' => 'nullable|numeric',
        ], [
            'bb.required' => 'Berat Badan tidak boleh kosong.',
            'bb.numeric' => 'Penulisan harus menggunakan titik (.)',
            'tb.required' => 'Tinggi Badan tidak boleh kosong.',
            'tb.numeric' => 'Penulisan harus menggunakan titik (.)',
            'lk.numeric' => 'Penulisan harus menggunakan titik (.)',
        ]);

        $id_bayi = $request->id_bayi;
        $bb = $request->bb;
        $tb = $request->tb;
        $lk = $request->lk;
        $imt = $this->get_imt($bb, $tb);
        $tgl_periksa = \Carbon\Carbon::now()->toDateString();
        $jk = $request->jk;
        $usia_bulan = $request->usia_bulan;
        $status_gizi = $this->get_status_gizi($bb, $tb, $jk, $usia_bulan);
        $get_nutrisi = $this->get_nutrisi_harian($usia_bulan);
        $kalori = $get_nutrisi['kalori'];
        $protein = $get_nutrisi['protein'];
        $lemak = $get_nutrisi['lemak'];
        $karbo = $get_nutrisi['karbo'];
        $serat = $get_nutrisi['serat'];
        
        $pemeriksaan = Pemeriksaan::create([
            'id_bayi' => $id_bayi,
            'bb' => $bb,
            'tb' => $tb,
            'lk' => $lk,
            'imt' => $imt,
            'tgl_periksa' => $tgl_periksa,
            'status_gizi' => $status_gizi,
            'kalori' => $kalori,
            'protein' => $protein,
            'lemak' => $lemak,
            'karbo' => $karbo,
            'serat' => $serat
        ]);

        return redirect()->route('detail_pemeriksaan', $pemeriksaan->id)->with(['message' => 'Pemeriksaan berhasil ditambahkan']);
    }

    public function detail_pemeriksaan($id): View
    {
        $pemeriksaan = Pemeriksaan::with('bayi')->findOrFail($id);
        $usia_bulan = Carbon::parse($pemeriksaan->bayi->tanggal_lahir)->diffInMonths(now());
        return view('puskesmas.pemeriksaan.detail-pemeriksaan', compact('pemeriksaan', 'usia_bulan'));
    }
    
    public function edit_pemeriksaan($id): View
    {
        $pemeriksaan = Pemeriksaan::with('bayi')->findOrFail($id);
        $usia_bulan = Carbon::parse($pemeriksaan->bayi->tanggal_lahir)->diffInMonths(now());
        return view('puskesmas.pemeriksaan.edit-pemeriksaan', compact('pemeriksaan', 'usia_bulan'));
    }

    public function update_pemeriksaan(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'lk' => 'nullable|numeric',
        ], [
            'bb.required' => 'Berat Badan tidak boleh kosong.',
            'bb.numeric' => 'Penulisan harus menggunakan titik (.)',
            'tb.required' => 'Tinggi Badan tidak boleh kosong.',
            'tb.numeric' => 'Penulisan harus menggunakan titik (.)',
            'lk.numeric' => 'Penulisan harus menggunakan titik (.)',
        ]);
        
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $bb = $request->bb;
        $tb = $request->tb;
        $lk = $request->lk;
        $imt = $this->get_imt($bb, $tb);
        $jk = $request->jk;
        $usia_bulan = $request->usia_bulan;
        $status_gizi = $this->get_status_gizi($bb, $tb, $jk, $usia_bulan);
        $get_nutrisi = $this->get_nutrisi_harian($usia_bulan);
        $kalori = $get_nutrisi['kalori'];
        $protein = $get_nutrisi['protein'];
        $lemak = $get_nutrisi['lemak'];
        $karbo = $get_nutrisi['karbo'];
        $serat = $get_nutrisi['serat'];

        $pemeriksaan->update([
            'bb' => $bb,
            'tb' => $tb,
            'lk' => $lk,
            'imt' => $imt,
            'status_gizi' => $status_gizi,
            'kalori' => $kalori,
            'protein' => $protein,
            'lemak' => $lemak,
            'karbo' => $karbo,
            'serat' => $serat
        ]);

        return redirect()->route('detail_pemeriksaan', $id)->with(['message' => 'Pemeriksaan berhasil diedit']);
    }
    
    public function delete_pemeriksaan($id): RedirectResponse
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        
        $pemeriksaan->delete();

        return redirect()->route('pemeriksaan')->with(['message' => 'Pemeriksaan berhasil dihapus']);
    }

    public function get_imt($bb, $tb)
    {
        $imt = $bb / (($tb/100)**2);

        return $imt;
    }

    public function get_nutrisi_harian($usia_bulan)
    {
        $kalori = 0;
        $protein = 0;
        $lemak = 0;
        $karbo = 0;
        $serat = 0;

        if ($usia_bulan >= 0 && $usia_bulan < 6) {
            $kalori = 550;
            $protein = 9;
            $lemak = 31;
            $karbo = 59;
            $serat = 0;
        } elseif ($usia_bulan >= 6 && $usia_bulan < 12) {
            $kalori = 800; 
            $protein = 15;
            $lemak = 35;
            $karbo = 105;
            $serat = 11;
        } elseif ($usia_bulan >= 12 && $usia_bulan < 37) {
            $kalori = 1350; 
            $protein = 20;
            $lemak = 45;
            $karbo = 215;
            $serat = 19;
        } elseif ($usia_bulan >= 37 && $usia_bulan <= 60) {
            $kalori = 1400; 
            $protein = 25;
            $lemak = 50;
            $karbo = 220;
            $serat = 20;
        } else {
            $kalori = 0; 
            $protein = 0;
            $lemak = 0;
            $karbo = 0;
            $serat = 0;
        }

        return [
            'kalori' => $kalori,
            'protein' => $protein,
            'lemak' => $lemak,
            'karbo' => $karbo,
            'serat' => $serat,
        ];
    }

    public function get_status_gizi($bb, $tb, $jk, $usia_bulan)
    {
        $imt = $this->get_imt($bb, $tb);

        // jk l usia 0
        if($jk == "Laki-laki" && $usia_bulan == 0 && $imt < 10.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 0 && $imt >= 10.2 && $imt < 11.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 0 && $imt >= 11.1 && $imt <= 14.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 0 && $imt > 14.8 && $imt <= 16.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 0 && $imt > 16.3 && $imt <= 18.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 0 && $imt > 18.1){$status_gizi = "Obesitas";}

        // jk l usia 1
        elseif($jk == "Laki-laki" && $usia_bulan == 1 && $imt < 11.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 1 && $imt >= 11.3 && $imt < 12.4){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 1 && $imt >= 12.4 && $imt <= 16.3){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 1 && $imt > 16.3 && $imt <= 17.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 1 && $imt > 17.8 && $imt <= 19.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 1 && $imt > 19.4){$status_gizi = "Obesitas";}

        // jk l usia 2
        elseif($jk == "Laki-laki" && $usia_bulan == 2 && $imt < 12.5){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 2 && $imt >= 12.5 && $imt < 13.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 2 && $imt >= 13.7 && $imt <= 17.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 2 && $imt > 17.8 && $imt <= 19.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 2 && $imt > 19.4 && $imt <= 21.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 2 && $imt > 21.1){$status_gizi = "Obesitas";}

        // jk l usia 3
        elseif($jk == "Laki-laki" && $usia_bulan == 3 && $imt < 13.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 3 && $imt >= 13.1 && $imt < 14.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 3 && $imt >= 14.3 && $imt <= 18.4){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 3 && $imt > 18.4 && $imt <= 20){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 3 && $imt > 20 && $imt <= 21.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 3 && $imt > 21.8){$status_gizi = "Obesitas";}

        // jk l usia 4
        elseif($jk == "Laki-laki" && $usia_bulan == 4 && $imt < 13.4){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 4 && $imt >= 13.4 && $imt < 14.5){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 4 && $imt >= 14.5 && $imt <= 18.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 4 && $imt > 18.7 && $imt <= 20.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 4 && $imt > 20.3 && $imt <= 22.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 4 && $imt > 22.1){$status_gizi = "Obesitas";}

        // jk l usia 5
        elseif($jk == "Laki-laki" && $usia_bulan == 5 && $imt < 13.5){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 5 && $imt >= 13.5 && $imt < 14.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 5 && $imt >= 14.7 && $imt <= 18.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 5 && $imt > 18.8 && $imt <= 20.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 5 && $imt > 20.5 && $imt <= 22.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 5 && $imt > 22.3){$status_gizi = "Obesitas";}

        // jk l usia 6
        elseif($jk == "Laki-laki" && $usia_bulan == 6 && $imt < 13.6){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 6 && $imt >= 13.6 && $imt < 14.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 6 && $imt >= 14.7 && $imt <= 18.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 6 && $imt > 18.8 && $imt <= 20.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 6 && $imt > 20.5 && $imt <= 22.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 6 && $imt > 22.3){$status_gizi = "Obesitas";}

        // jk l usia 7
        elseif($jk == "Laki-laki" && $usia_bulan == 7 && $imt < 13.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 7 && $imt >= 13.7 && $imt < 14.8){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 7 && $imt >= 14.8 && $imt <= 18.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 7 && $imt > 18.8 && $imt <= 20.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 7 && $imt > 20.5 && $imt <= 22.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 7 && $imt > 22.3){$status_gizi = "Obesitas";}

        // jk l usia 8
        elseif($jk == "Laki-laki" && $usia_bulan == 8 && $imt < 13.6){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 8 && $imt >= 13.6 && $imt < 14.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 8 && $imt >= 14.7 && $imt <= 18.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 8 && $imt > 18.7 && $imt <= 20.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 8 && $imt > 20.4 && $imt <= 22.2){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 8 && $imt > 22.2){$status_gizi = "Obesitas";}

        // jk l usia 9
        elseif($jk == "Laki-laki" && $usia_bulan == 9 && $imt < 13.6){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 9 && $imt >= 13.6 && $imt < 14.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 9 && $imt >= 14.7 && $imt <= 18.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 9 && $imt > 18.6 && $imt <= 20.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 9 && $imt > 20.3 && $imt <= 22.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 9 && $imt > 22.1){$status_gizi = "Obesitas";}

        // jk l usia 10
        elseif($jk == "Laki-laki" && $usia_bulan == 10 && $imt < 13.5){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 10 && $imt >= 13.5 && $imt < 14.6){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 10 && $imt >= 14.6 && $imt <= 18.5){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 10 && $imt > 18.5 && $imt <= 20.1){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 10 && $imt > 20.1 && $imt <= 22){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 10 && $imt > 22){$status_gizi = "Obesitas";}

        // jk l usia 11
        elseif($jk == "Laki-laki" && $usia_bulan == 11 && $imt < 13.4){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 11 && $imt >= 13.4 && $imt < 14.5){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 11 && $imt >= 14.5 && $imt <= 18.4){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 11 && $imt > 18.4 && $imt <= 20){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 11 && $imt > 20 && $imt <= 21.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 11 && $imt > 21.8){$status_gizi = "Obesitas";}

        // jk l usia 12
        elseif($jk == "Laki-laki" && $usia_bulan == 12 && $imt < 13.4){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 12 && $imt >= 13.4 && $imt < 14.4){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 12 && $imt >= 14.4 && $imt <= 18.2){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 12 && $imt > 18.2 && $imt <= 19.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 12 && $imt > 19.8 && $imt <= 21.6){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 12 && $imt > 21.6){$status_gizi = "Obesitas";}

        // jk l usia 13
        elseif($jk == "Laki-laki" && $usia_bulan == 13 && $imt < 13.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 13 && $imt >= 13.3 && $imt < 14.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 13 && $imt >= 14.3 && $imt <= 18.1){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 13 && $imt > 18.1 && $imt <= 19.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 13 && $imt > 19.7 && $imt <= 21.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 13 && $imt > 21.5){$status_gizi = "Obesitas";}

        // jk l usia 14
        elseif($jk == "Laki-laki" && $usia_bulan == 14 && $imt < 13.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 14 && $imt >= 13.2 && $imt < 14.2){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 14 && $imt >= 14.2 && $imt <= 18){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 14 && $imt > 18 && $imt <= 19.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 14 && $imt > 19.5 && $imt <= 21.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 14 && $imt > 21.3){$status_gizi = "Obesitas";}

        // jk l usia 15
        elseif($jk == "Laki-laki" && $usia_bulan == 15 && $imt < 13.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 15 && $imt >= 13.1 && $imt < 14.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 15 && $imt >= 14.1 && $imt <= 17.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 15 && $imt > 17.8 && $imt <= 19.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 15 && $imt > 19.4 && $imt <= 21.2){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 15 && $imt > 21.2){$status_gizi = "Obesitas";}

        // jk l usia 16
        elseif($jk == "Laki-laki" && $usia_bulan == 16 && $imt < 13.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 16 && $imt >= 13.1 && $imt < 14){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 16 && $imt >= 14 && $imt <= 17.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 16 && $imt > 17.7 && $imt <= 19.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 16 && $imt > 19.3 && $imt <= 21){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 16 && $imt > 21){$status_gizi = "Obesitas";}

        // jk l usia 17
        elseif($jk == "Laki-laki" && $usia_bulan == 17 && $imt < 13){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 17 && $imt >= 13 && $imt < 13.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 17 && $imt >= 13.9 && $imt <= 17.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 17 && $imt > 17.6 && $imt <= 19.1){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 17 && $imt > 19.1 && $imt <= 20.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 17 && $imt > 20.9){$status_gizi = "Obesitas";}

        // jk l usia 18
        elseif($jk == "Laki-laki" && $usia_bulan == 18 && $imt < 12.9){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 18 && $imt >= 12.9 && $imt < 13.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 18 && $imt >= 13.9 && $imt <= 17.5){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 18 && $imt > 17.5 && $imt <= 19){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 18 && $imt > 19 && $imt <= 20.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 18 && $imt > 20.8){$status_gizi = "Obesitas";}

        // jk l usia 19
        elseif($jk == "Laki-laki" && $usia_bulan == 19 && $imt < 12.9){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 19 && $imt >= 12.9 && $imt < 13.8){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 19 && $imt >= 13.8 && $imt <= 17.4){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 19 && $imt > 17.4 && $imt <= 18.9){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 19 && $imt > 18.9 && $imt <= 20.7){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 19 && $imt > 20.7){$status_gizi = "Obesitas";}

        // jk l usia 20
        elseif($jk == "Laki-laki" && $usia_bulan == 20 && $imt < 12.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 20 && $imt >= 12.8 && $imt < 13.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 20 && $imt >= 13.7 && $imt <= 17.3){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 20 && $imt > 17.3 && $imt <= 18.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 20 && $imt > 18.8 && $imt <= 20.6){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 20 && $imt > 20.6){$status_gizi = "Obesitas";}

        // jk l usia 21
        elseif($jk == "Laki-laki" && $usia_bulan == 21 && $imt < 12.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 21 && $imt >= 12.8 && $imt < 13.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 21 && $imt >= 13.7 && $imt <= 17.2){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 21 && $imt > 17.2 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 21 && $imt > 18.7 && $imt <= 20.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 21 && $imt > 20.5){$status_gizi = "Obesitas";}

        // jk l usia 22
        elseif($jk == "Laki-laki" && $usia_bulan == 22 && $imt < 12.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 22 && $imt >= 12.7 && $imt < 13.6){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 22 && $imt >= 13.6 && $imt <= 17.2){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 22 && $imt > 17.2 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 22 && $imt > 18.7 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 22 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk l usia 23
        elseif($jk == "Laki-laki" && $usia_bulan == 23 && $imt < 12.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 23 && $imt >= 12.7 && $imt < 13.6){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 23 && $imt >= 13.6 && $imt <= 17.1){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 23 && $imt > 17.1 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 23 && $imt > 18.6 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 23 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk l usia 24
        elseif($jk == "Laki-laki" && $usia_bulan == 24 && $imt < 12.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 24 && $imt >= 12.7 && $imt < 13.6){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 24 && $imt >= 13.6 && $imt <= 17){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 24 && $imt > 17 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 24 && $imt > 18.5 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 24 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk l usia 25
        elseif($jk == "Laki-laki" && $usia_bulan == 25 && $imt < 12.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 25 && $imt >= 12.8 && $imt < 13.8){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 25 && $imt >= 13.8 && $imt <= 17.3){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 25 && $imt > 17.3 && $imt <= 18.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 25 && $imt > 18.8 && $imt <= 20.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 25 && $imt > 20.5){$status_gizi = "Obesitas";}

        // jk l usia 26
        elseif($jk == "Laki-laki" && $usia_bulan == 26 && $imt < 12.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 26 && $imt >= 12.8 && $imt < 13.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 26 && $imt >= 13.7 && $imt <= 17.3){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 26 && $imt > 17.3 && $imt <= 18.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 26 && $imt > 18.8 && $imt <= 20.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 26 && $imt > 20.5){$status_gizi = "Obesitas";}

        // jk l usia 27
        elseif($jk == "Laki-laki" && $usia_bulan == 27 && $imt < 12.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 27 && $imt >= 12.7 && $imt < 13.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 27 && $imt >= 13.7 && $imt <= 17.2){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 27 && $imt > 17.2 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 27 && $imt > 18.7 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 27 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk l usia 28
        elseif($jk == "Laki-laki" && $usia_bulan == 28 && $imt < 12.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 28 && $imt >= 12.7 && $imt < 13.6){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 28 && $imt >= 13.6 && $imt <= 17.2){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 28 && $imt > 17.2 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 28 && $imt > 18.7 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 28 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk l usia 29
        elseif($jk == "Laki-laki" && $usia_bulan == 29 && $imt < 12.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 29 && $imt >= 12.7 && $imt < 13.6){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 29 && $imt >= 13.6 && $imt <= 17.1){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 29 && $imt > 17.1 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 29 && $imt > 18.6 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 29 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk l usia 30
        elseif($jk == "Laki-laki" && $usia_bulan == 30 && $imt < 12.6){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 30 && $imt >= 12.6 && $imt < 13.6){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 30 && $imt >= 13.6 && $imt <= 17.1){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 30 && $imt > 17.1 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 30 && $imt > 18.6 && $imt <= 20.2){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 30 && $imt > 20.2){$status_gizi = "Obesitas";}

        // jk l usia 31
        elseif($jk == "Laki-laki" && $usia_bulan == 31 && $imt < 12.6){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 31 && $imt >= 12.6 && $imt < 13.5){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 31 && $imt >= 13.5 && $imt <= 17.1){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 31 && $imt > 17.1 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 31 && $imt > 18.5 && $imt <= 20.2){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 31 && $imt > 20.2){$status_gizi = "Obesitas";}

        // jk l usia 32
        elseif($jk == "Laki-laki" && $usia_bulan == 32 && $imt < 12.5){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 32 && $imt >= 12.5 && $imt < 13.5){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 32 && $imt >= 13.5 && $imt <= 17){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 32 && $imt > 17 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 32 && $imt > 18.5 && $imt <= 20.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 32 && $imt > 20.1){$status_gizi = "Obesitas";}

        // jk l usia 33
        elseif($jk == "Laki-laki" && $usia_bulan == 33 && $imt < 12.5){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 33 && $imt >= 12.5 && $imt < 13.5){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 33 && $imt >= 13.5 && $imt <= 17){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 33 && $imt > 17 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 33 && $imt > 18.5 && $imt <= 20.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 33 && $imt > 20.1){$status_gizi = "Obesitas";}

        // jk l usia 34
        elseif($jk == "Laki-laki" && $usia_bulan == 34 && $imt < 12.5){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 34 && $imt >= 12.5 && $imt < 13.4){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 34 && $imt >= 13.4 && $imt <= 17){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 34 && $imt > 17 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 34 && $imt > 18.4 && $imt <= 20){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 34 && $imt > 20){$status_gizi = "Obesitas";}

        // jk l usia 35
        elseif($jk == "Laki-laki" && $usia_bulan == 35 && $imt < 12.4){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 35 && $imt >= 12.4 && $imt < 13.4){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 35 && $imt >= 13.4 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 35 && $imt > 16.9 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 35 && $imt > 18.4 && $imt <= 20){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 35 && $imt > 20){$status_gizi = "Obesitas";}

        // jk l usia 36
        elseif($jk == "Laki-laki" && $usia_bulan == 36 && $imt < 12.4){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 36 && $imt >= 12.4 && $imt < 13.4){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 36 && $imt >= 13.4 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 36 && $imt > 16.9 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 36 && $imt > 18.4 && $imt <= 20){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 36 && $imt > 20){$status_gizi = "Obesitas";}

        // jk l usia 37
        elseif($jk == "Laki-laki" && $usia_bulan == 37 && $imt < 12.4){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 37 && $imt >= 12.4 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 37 && $imt >= 13.3 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 37 && $imt > 16.9 && $imt <= 18.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 37 && $imt > 18.3 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 37 && $imt > 19.9){$status_gizi = "Obesitas";}

        // jk l usia 38
        elseif($jk == "Laki-laki" && $usia_bulan == 38 && $imt < 12.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 38 && $imt >= 12.3 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 38 && $imt >= 13.3 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 38 && $imt > 16.8 && $imt <= 18.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 38 && $imt > 18.3 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 38 && $imt > 19.9){$status_gizi = "Obesitas";}

        // jk l usia 39
        elseif($jk == "Laki-laki" && $usia_bulan == 39 && $imt < 12.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 39 && $imt >= 12.3 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 39 && $imt >= 13.3 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 39 && $imt > 16.8 && $imt <= 18.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 39 && $imt > 18.3 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 39 && $imt > 19.9){$status_gizi = "Obesitas";}

        // jk l usia 40
        elseif($jk == "Laki-laki" && $usia_bulan == 40 && $imt < 12.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 40 && $imt >= 12.3 && $imt < 13.2){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 40 && $imt >= 13.2 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 40 && $imt > 16.8 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 40 && $imt > 18.2 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 40 && $imt > 19.9){$status_gizi = "Obesitas";}

        // jk l usia 41
        elseif($jk == "Laki-laki" && $usia_bulan == 41 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 41 && $imt >= 12.2 && $imt < 13.2){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 41 && $imt >= 13.2 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 41 && $imt > 16.8 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 41 && $imt > 18.2 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 41 && $imt > 19.9){$status_gizi = "Obesitas";}

        // jk l usia 42
        elseif($jk == "Laki-laki" && $usia_bulan == 42 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 42 && $imt >= 12.2 && $imt < 13.2){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 42 && $imt >= 13.2 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 42 && $imt > 16.8 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 42 && $imt > 18.2 && $imt <= 19.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 42 && $imt > 19.8){$status_gizi = "Obesitas";}

        // jk l usia 43
        elseif($jk == "Laki-laki" && $usia_bulan == 43 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 43 && $imt >= 12.2 && $imt < 13.2){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 43 && $imt >= 13.2 && $imt <= 16.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 43 && $imt > 16.7 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 43 && $imt > 18.2 && $imt <= 19.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 43 && $imt > 19.8){$status_gizi = "Obesitas";}

        // jk l usia 44
        elseif($jk == "Laki-laki" && $usia_bulan == 44 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 44 && $imt >= 12.2 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 44 && $imt >= 13.1 && $imt <= 16.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 44 && $imt > 16.7 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 44 && $imt > 18.2 && $imt <= 19.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 44 && $imt > 19.8){$status_gizi = "Obesitas";}

        // jk l usia 45
        elseif($jk == "Laki-laki" && $usia_bulan == 45 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 45 && $imt >= 12.2 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 45 && $imt >= 13.1 && $imt <= 16.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 45 && $imt > 16.7 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 45 && $imt > 18.2 && $imt <= 19.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 45 && $imt > 19.8){$status_gizi = "Obesitas";}

        // jk l usia 46
        elseif($jk == "Laki-laki" && $usia_bulan == 46 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 46 && $imt >= 12.1 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 46 && $imt >= 13.1 && $imt <= 16.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 46 && $imt > 16.7 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 46 && $imt > 18.2 && $imt <= 19.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 46 && $imt > 19.8){$status_gizi = "Obesitas";}

        // jk l usia 47
        elseif($jk == "Laki-laki" && $usia_bulan == 47 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 47 && $imt >= 12.1 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 47 && $imt >= 13.1 && $imt <= 16.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 47 && $imt > 16.7 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 47 && $imt > 18.2 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 47 && $imt > 19.9){$status_gizi = "Obesitas";}
        elseif($jk == "Laki-laki" && $usia_bulan == 46 && $imt > 19.8){$status_gizi = "Obesitas";}

        // jk l usia 48
        elseif($jk == "Laki-laki" && $usia_bulan == 48 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 48 && $imt >= 12.1 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 48 && $imt >= 13.1 && $imt <= 16.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 48 && $imt > 16.7 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 48 && $imt > 18.2 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 48 && $imt > 19.9){$status_gizi = "Obesitas";}

        // jk l usia 49
        elseif($jk == "Laki-laki" && $usia_bulan == 49 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 49 && $imt >= 12.1 && $imt < 13){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 49 && $imt >= 13 && $imt <= 16.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 49 && $imt > 16.7 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 49 && $imt > 18.2 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 49 && $imt > 19.9){$status_gizi = "Obesitas";}

        // jk l usia 50
        elseif($jk == "Laki-laki" && $usia_bulan == 50 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 50 && $imt >= 12.1 && $imt < 13){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 50 && $imt >= 13 && $imt <= 16.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 50 && $imt > 16.7 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 50 && $imt > 18.2 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 50 && $imt > 19.9){$status_gizi = "Obesitas";}

        // jk l usia 51
        elseif($jk == "Laki-laki" && $usia_bulan == 51 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 51 && $imt >= 12.1 && $imt < 13){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 51 && $imt >= 13 && $imt <= 16.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 51 && $imt > 16.6 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 51 && $imt > 18.2 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 51 && $imt > 19.9){$status_gizi = "Obesitas";}

        // jk l usia 52
        elseif($jk == "Laki-laki" && $usia_bulan == 52 && $imt < 12){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 52 && $imt >= 12 && $imt < 13){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 52 && $imt >= 13 && $imt <= 16.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 52 && $imt > 16.6 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 52 && $imt > 18.2 && $imt <= 19.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 52 && $imt > 19.9){$status_gizi = "Obesitas";}

        // jk l usia 53
        elseif($jk == "Laki-laki" && $usia_bulan == 53 && $imt < 12){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 53 && $imt >= 12 && $imt < 13){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 53 && $imt >= 13 && $imt <= 16.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 53 && $imt > 16.6 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 53 && $imt > 18.2 && $imt <= 20){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 53 && $imt > 20){$status_gizi = "Obesitas";}

        // jk l usia 54
        elseif($jk == "Laki-laki" && $usia_bulan == 54 && $imt < 12){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 54 && $imt >= 12 && $imt < 13){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 54 && $imt >= 13 && $imt <= 16.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 54 && $imt > 16.6 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 54 && $imt > 18.2 && $imt <= 20){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 54 && $imt > 20){$status_gizi = "Obesitas";}

        // jk l usia 55
        elseif($jk == "Laki-laki" && $usia_bulan == 55 && $imt < 12){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 55 && $imt >= 12 && $imt < 13){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 55 && $imt >= 13 && $imt <= 16.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 55 && $imt > 16.6 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 55 && $imt > 18.2 && $imt <= 20){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 55 && $imt > 20){$status_gizi = "Obesitas";}
        elseif($jk == "Laki-laki" && $usia_bulan == 54 && $imt > 20){$status_gizi = "Obesitas";}

        // jk l usia 56
        elseif($jk == "Laki-laki" && $usia_bulan == 56 && $imt < 12){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 56 && $imt >= 12 && $imt < 12.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 56 && $imt >= 12.9 && $imt <= 16.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 56 && $imt > 16.6 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 56 && $imt > 18.2 && $imt <= 20.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 56 && $imt > 20.1){$status_gizi = "Obesitas";}

        // jk l usia 57
        elseif($jk == "Laki-laki" && $usia_bulan == 57 && $imt < 12){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 57 && $imt >= 12 && $imt < 12.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 57 && $imt >= 12.9 && $imt <= 16.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 57 && $imt > 16.6 && $imt <= 18.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 57 && $imt > 18.2 && $imt <= 20.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 57 && $imt > 20.1){$status_gizi = "Obesitas";}

        // jk l usia 58
        elseif($jk == "Laki-laki" && $usia_bulan == 58 && $imt < 12){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 58 && $imt >= 12 && $imt < 12.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 58 && $imt >= 12.9 && $imt <= 16.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 58 && $imt > 16.6 && $imt <= 18.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 58 && $imt > 18.3 && $imt <= 20.2){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 58 && $imt > 20.2){$status_gizi = "Obesitas";}

        // jk l usia 59
        elseif($jk == "Laki-laki" && $usia_bulan == 59 && $imt < 12){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 59 && $imt >= 12 && $imt < 12.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 59 && $imt >= 12.9 && $imt <= 16.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 59 && $imt > 16.6 && $imt <= 18.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 59 && $imt > 18.3 && $imt <= 20.2){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 59 && $imt > 20.2){$status_gizi = "Obesitas";}

        // jk l usia 60
        elseif($jk == "Laki-laki" && $usia_bulan == 60 && $imt < 12){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Laki-laki" && $usia_bulan == 60 && $imt >= 12 && $imt < 12.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Laki-laki" && $usia_bulan == 60 && $imt >= 12.9 && $imt <= 16.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Laki-laki" && $usia_bulan == 60 && $imt > 16.6 && $imt <= 18.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 60 && $imt > 18.3 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Laki-laki" && $usia_bulan == 60 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk p usia 0
        elseif($jk == "Perempuan" && $usia_bulan == 0 && $imt < 10.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 0 && $imt >= 10.1 && $imt < 11.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 0 && $imt >= 11.1 && $imt <= 14.6){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 0 && $imt > 14.6 && $imt <= 16.1){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 0 && $imt > 16.1 && $imt <= 17.7){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 0 && $imt > 17.7){$status_gizi = "Obesitas";}

        // jk p usia 1
        elseif($jk == "Perempuan" && $usia_bulan == 1 && $imt < 10.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 1 && $imt >= 10.8 && $imt < 12.0){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 1 && $imt >= 12.0 && $imt <= 16.0){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 1 && $imt > 16.0 && $imt <= 17.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 1 && $imt > 17.5 && $imt <= 19.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 1 && $imt > 19.1){$status_gizi = "Obesitas";}

        // jk p usia 2
        elseif($jk == "Perempuan" && $usia_bulan == 2 && $imt < 11.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 2 && $imt >= 11.8 && $imt < 13.0){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 2 && $imt >= 13.0 && $imt <= 17.3){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 2 && $imt > 17.3 && $imt <= 19.0){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 2 && $imt > 19.0 && $imt <= 20.7){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 2 && $imt > 20.7){$status_gizi = "Obesitas";}

        // jk p usia 3
        elseif($jk == "Perempuan" && $usia_bulan == 3 && $imt < 12.4){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 3 && $imt >= 12.4 && $imt < 13.6){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 3 && $imt >= 13.6 && $imt <= 17.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 3 && $imt > 17.9 && $imt <= 19.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 3 && $imt > 19.7 && $imt <= 21.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 3 && $imt > 21.5){$status_gizi = "Obesitas";}

        // jk p usia 4
        elseif($jk == "Perempuan" && $usia_bulan == 4 && $imt < 12.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 4 && $imt >= 12.7 && $imt < 13.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 4 && $imt >= 13.9 && $imt <= 18.3){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 4 && $imt > 18.3 && $imt <= 20.0){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 4 && $imt > 20.0 && $imt <= 22.0){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 4 && $imt > 22.0){$status_gizi = "Obesitas";}

        // jk p usia 5
        elseif($jk == "Perempuan" && $usia_bulan == 5 && $imt < 12.9){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 5 && $imt >= 12.9 && $imt < 14.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 5 && $imt >= 14.1 && $imt <= 18.4){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 5 && $imt > 18.4 && $imt <= 20.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 5 && $imt > 20.2 && $imt <= 22.2){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 5 && $imt > 22.2){$status_gizi = "Obesitas";}

        // jk p usia 6
        elseif($jk == "Perempuan" && $usia_bulan == 6 && $imt < 13.0){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 6 && $imt >= 13.0 && $imt < 14.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 6 && $imt >= 14.1 && $imt <= 18.5){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 6 && $imt > 18.5 && $imt <= 20.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 6 && $imt > 20.3 && $imt <= 22.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 6 && $imt > 22.3){$status_gizi = "Obesitas";}

        // jk p usia 7
        elseif($jk == "Perempuan" && $usia_bulan == 7 && $imt < 13.0){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 7 && $imt >= 13.0 && $imt < 14.2){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 7 && $imt >= 14.2 && $imt <= 18.5){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 7 && $imt > 18.5 && $imt <= 20.3){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 7 && $imt > 20.3 && $imt <= 22.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 7 && $imt > 22.3){$status_gizi = "Obesitas";}

        // jk p usia 8
        elseif($jk == "Perempuan" && $usia_bulan == 8 && $imt < 13.0){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 8 && $imt >= 13.0 && $imt < 14.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 8 && $imt >= 14.1 && $imt <= 18.4){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 8 && $imt > 18.4 && $imt <= 20.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 8 && $imt > 20.2 && $imt <= 22.2){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 8 && $imt > 22.2){$status_gizi = "Obesitas";}

        // jk p usia 9
        elseif($jk == "Perempuan" && $usia_bulan == 9 && $imt < 12.9){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 9 && $imt >= 12.9 && $imt < 14.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 9 && $imt >= 14.1 && $imt <= 18.3){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 9 && $imt > 18.3 && $imt <= 20.1){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 9 && $imt > 20.1 && $imt <= 22.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 9 && $imt > 22.1){$status_gizi = "Obesitas";}

        // jk p usia 10
        elseif($jk == "Perempuan" && $usia_bulan == 10 && $imt < 12.9){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 10 && $imt >= 12.9 && $imt < 14.0){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 10 && $imt >= 14.0 && $imt <= 18.2){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 10 && $imt > 18.2 && $imt <= 19.9){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 10 && $imt > 19.9 && $imt <= 21.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 10 && $imt > 21.9){$status_gizi = "Obesitas";}

        // jk p usia 11
        elseif($jk == "Perempuan" && $usia_bulan == 11 && $imt < 12.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 11 && $imt >= 12.8 && $imt < 13.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 11 && $imt >= 13.9 && $imt <= 18.0){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 11 && $imt > 18.0 && $imt <= 19.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 11 && $imt > 19.8 && $imt <= 21.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 11 && $imt > 21.8){$status_gizi = "Obesitas";}

        // jk p usia 12
        elseif($jk == "Perempuan" && $usia_bulan == 12 && $imt < 12.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 12 && $imt >= 12.7 && $imt < 13.8){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 12 && $imt >= 13.8 && $imt <= 17.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 12 && $imt > 17.9 && $imt <= 19.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 12 && $imt > 19.6 && $imt <= 21.6){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 12 && $imt > 21.6){$status_gizi = "Obesitas";}

        // jk p usia 13
        elseif($jk == "Perempuan" && $usia_bulan == 13 && $imt < 12.6){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 13 && $imt >= 12.6 && $imt < 13.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 13 && $imt >= 13.7 && $imt <= 17.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 13 && $imt > 17.7 && $imt <= 19.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 13 && $imt > 19.5 && $imt <= 21.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 13 && $imt > 21.4){$status_gizi = "Obesitas";}

        // jk p usia 14
        elseif($jk == "Perempuan" && $usia_bulan == 14 && $imt < 12.6){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 14 && $imt >= 12.6 && $imt < 13.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 14 && $imt >= 13.7 && $imt <= 17.7){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 14 && $imt > 17.7 && $imt <= 19.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 14 && $imt > 19.5 && $imt <= 21.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 14 && $imt > 21.4){$status_gizi = "Obesitas";}

        // jk p usia 15
        elseif($jk == "Perempuan" && $usia_bulan == 15 && $imt < 12.5){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 15 && $imt >= 12.5 && $imt < 13.5){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 15 && $imt >= 13.5 && $imt <= 17.5){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 15 && $imt > 17.5 && $imt <= 19.2){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 15 && $imt > 19.2 && $imt <= 21.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 15 && $imt > 21.1){$status_gizi = "Obesitas";}

        // jk p usia 16
        elseif($jk == "Perempuan" && $usia_bulan == 16 && $imt < 12.4){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 16 && $imt >= 12.4 && $imt < 13.5){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 16 && $imt >= 13.5 && $imt <= 17.4){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 16 && $imt > 17.4 && $imt <= 19.1){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 16 && $imt > 19.1 && $imt <= 21.0){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 16 && $imt > 21.0){$status_gizi = "Obesitas";}

        // jk p usia 17
        elseif($jk == "Perempuan" && $usia_bulan == 17 && $imt < 12.4){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 17 && $imt >= 12.4 && $imt < 13.4){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 17 && $imt >= 13.4 && $imt <= 17.3){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 17 && $imt > 17.3 && $imt <= 18.9){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 17 && $imt > 18.9 && $imt <= 20.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 17 && $imt > 20.9){$status_gizi = "Obesitas";}

        // jk p usia 18
        elseif($jk == "Perempuan" && $usia_bulan == 18 && $imt < 12.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 18 && $imt >= 12.3 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 18 && $imt >= 13.3 && $imt <= 17.2){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 18 && $imt > 17.2 && $imt <= 18.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 18 && $imt > 18.8 && $imt <= 20.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 18 && $imt > 20.8){$status_gizi = "Obesitas";}

        // jk p usia 19
        elseif($jk == "Perempuan" && $usia_bulan == 19 && $imt < 12.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 19 && $imt >= 12.3 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 19 && $imt >= 13.3 && $imt <= 17.1){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 19 && $imt > 17.1 && $imt <= 18.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 19 && $imt > 18.8 && $imt <= 20.7){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 19 && $imt > 20.7){$status_gizi = "Obesitas";}

        // jk p usia 20
        elseif($jk == "Perempuan" && $usia_bulan == 20 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 20 && $imt >= 12.2 && $imt < 13.2){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 20 && $imt >= 13.2 && $imt <= 17.0){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 20 && $imt > 17.0 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 20 && $imt > 18.7 && $imt <= 20.6){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 20 && $imt > 20.6){$status_gizi = "Obesitas";}

        // jk p usia 21
        elseif($jk == "Perempuan" && $usia_bulan == 21 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 21 && $imt >= 12.2 && $imt < 13.2){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 21 && $imt >= 13.2 && $imt <= 17.0){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 21 && $imt > 17.0 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 21 && $imt > 18.6 && $imt <= 20.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 21 && $imt > 20.5){$status_gizi = "Obesitas";}

        // jk p usia 22
        elseif($jk == "Perempuan" && $usia_bulan == 22 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 22 && $imt >= 12.2 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 22 && $imt >= 13.1 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 22 && $imt > 16.9 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 22 && $imt > 18.5 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 22 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk p usia 23
        elseif($jk == "Perempuan" && $usia_bulan == 23 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 23 && $imt >= 12.2 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 23 && $imt >= 13.1 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 23 && $imt > 16.9 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 23 && $imt > 18.5 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 23 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk p usia 24
        elseif($jk == "Perempuan" && $usia_bulan == 24 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 24 && $imt >= 12.1 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 24 && $imt >= 13.1 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 24 && $imt > 16.8 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 24 && $imt > 18.4 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 24 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk p usia 25
        elseif($jk == "Perempuan" && $usia_bulan == 25 && $imt < 12.4){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 25 && $imt >= 12.4 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 25 && $imt >= 13.3 && $imt <= 17.1){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 25 && $imt > 17.1 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 25 && $imt > 18.7 && $imt <= 20.6){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 25 && $imt > 20.6){$status_gizi = "Obesitas";}

        // jk p usia 26
        elseif($jk == "Perempuan" && $usia_bulan == 26 && $imt < 12.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 26 && $imt >= 12.3 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 26 && $imt >= 13.3 && $imt <= 17.0){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 26 && $imt > 17.0 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 26 && $imt > 18.7 && $imt <= 20.6){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 26 && $imt > 20.6){$status_gizi = "Obesitas";}

        // jk p usia 27
        elseif($jk == "Perempuan" && $usia_bulan == 27 && $imt < 12.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 27 && $imt >= 12.3 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 27 && $imt >= 13.3 && $imt <= 17.0){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 27 && $imt > 17.0 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 27 && $imt > 18.6 && $imt <= 20.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 27 && $imt > 20.5){$status_gizi = "Obesitas";}

        // jk p usia 28
        elseif($jk == "Perempuan" && $usia_bulan == 28 && $imt < 12.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 28 && $imt >= 12.3 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 28 && $imt >= 13.3 && $imt <= 17.0){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 28 && $imt > 17.0 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 28 && $imt > 18.6 && $imt <= 20.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 28 && $imt > 20.5){$status_gizi = "Obesitas";}

        // jk p usia 29
        elseif($jk == "Perempuan" && $usia_bulan == 29 && $imt < 12.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 29 && $imt >= 12.3 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 29 && $imt >= 13.3 && $imt <= 17.0){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 29 && $imt > 17.0 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 29 && $imt > 18.6 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 29 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk p usia 30
        elseif($jk == "Perempuan" && $usia_bulan == 30 && $imt < 12.3){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 30 && $imt >= 12.3 && $imt < 13.3){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 30 && $imt >= 13.3 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 30 && $imt > 16.9 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 30 && $imt > 18.5 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 30 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk p usia 31
        elseif($jk == "Perempuan" && $usia_bulan == 31 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 31 && $imt >= 12.2 && $imt < 13.2){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 31 && $imt >= 13.2 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 31 && $imt > 16.9 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 31 && $imt > 18.5 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 31 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk p usia 32
        elseif($jk == "Perempuan" && $usia_bulan == 32 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 32 && $imt >= 12.2 && $imt < 13.2){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 32 && $imt >= 13.2 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 32 && $imt > 16.9 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 32 && $imt > 18.5 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 32 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk p usia 33
        elseif($jk == "Perempuan" && $usia_bulan == 33 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 33 && $imt >= 12.2 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 33 && $imt >= 13.1 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 33 && $imt > 16.9 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 33 && $imt > 18.5 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 33 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk p usia 34
        elseif($jk == "Perempuan" && $usia_bulan == 34 && $imt < 12.2){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 34 && $imt >= 12.2 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 34 && $imt >= 13.1 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 34 && $imt > 16.8 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 34 && $imt > 18.5 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 34 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk p usia 35
        elseif($jk == "Perempuan" && $usia_bulan == 35 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 35 && $imt >= 12.1 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 35 && $imt >= 13.1 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 35 && $imt > 16.8 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 35 && $imt > 18.4 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 35 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk p usia 36
        elseif($jk == "Perempuan" && $usia_bulan == 36 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 36 && $imt >= 12.1 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 36 && $imt >= 13.1 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 36 && $imt > 16.8 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 36 && $imt > 18.4 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 36 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk p usia 37
        elseif($jk == "Perempuan" && $usia_bulan == 37 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 37 && $imt >= 12.1 && $imt < 13.1){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 37 && $imt >= 13.1 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 37 && $imt > 16.8 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 37 && $imt > 18.4 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 37 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk p usia 38
        elseif($jk == "Perempuan" && $usia_bulan == 38 && $imt < 12.1){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 38 && $imt >= 12.1 && $imt < 13.0){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 38 && $imt >= 13.0 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 38 && $imt > 16.8 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 38 && $imt > 18.4 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 38 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk p usia 39
        elseif($jk == "Perempuan" && $usia_bulan == 39 && $imt < 12.0){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 39 && $imt >= 12.0 && $imt < 13.0){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 39 && $imt >= 13.0 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 39 && $imt > 16.8 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 39 && $imt > 18.4 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 39 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk p usia 40
        elseif($jk == "Perempuan" && $usia_bulan == 40 && $imt < 12.0){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 40 && $imt >= 12.0 && $imt < 13.0){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 40 && $imt >= 13.0 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 40 && $imt > 16.8 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 40 && $imt > 18.4 && $imt <= 20.3){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 40 && $imt > 20.3){$status_gizi = "Obesitas";}

        // jk p usia 41
        elseif($jk == "Perempuan" && $usia_bulan == 41 && $imt < 12.0){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 41 && $imt >= 12.0 && $imt < 13.0){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 41 && $imt >= 13.0 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 41 && $imt > 16.8 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 41 && $imt > 18.4 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 41 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk p usia 42
        elseif($jk == "Perempuan" && $usia_bulan == 42 && $imt < 12.0){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 42 && $imt >= 12.0 && $imt < 12.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 42 && $imt >= 12.9 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 42 && $imt > 16.8 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 42 && $imt > 18.4 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 42 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk p usia 43
        elseif($jk == "Perempuan" && $usia_bulan == 43 && $imt < 11.9){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 43 && $imt >= 11.9 && $imt < 12.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 43 && $imt >= 12.9 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 43 && $imt > 16.8 && $imt <= 18.4){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 43 && $imt > 18.4 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 43 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk p usia 44
        elseif($jk == "Perempuan" && $usia_bulan == 44 && $imt < 11.9){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 44 && $imt >= 11.9 && $imt < 12.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 44 && $imt >= 12.9 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 44 && $imt > 16.8 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 44 && $imt > 18.5 && $imt <= 20.4){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 44 && $imt > 20.4){$status_gizi = "Obesitas";}

        // jk p usia 45
        elseif($jk == "Perempuan" && $usia_bulan == 45 && $imt < 11.9){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 45 && $imt >= 11.9 && $imt < 12.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 45 && $imt >= 12.9 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 45 && $imt > 16.8 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 45 && $imt > 18.5 && $imt <= 20.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 45 && $imt > 20.5){$status_gizi = "Obesitas";}

        // jk p usia 46
        elseif($jk == "Perempuan" && $usia_bulan == 46 && $imt < 11.9){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 46 && $imt >= 11.9 && $imt < 12.9){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 46 && $imt >= 12.9 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 46 && $imt > 16.8 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 46 && $imt > 18.5 && $imt <= 20.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 46 && $imt > 20.5){$status_gizi = "Obesitas";}

        // jk p usia 47
        elseif($jk == "Perempuan" && $usia_bulan == 47 && $imt < 11.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 47 && $imt >= 11.8 && $imt < 12.8){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 47 && $imt >= 12.8 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 47 && $imt > 16.8 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 47 && $imt > 18.5 && $imt <= 20.5){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 47 && $imt > 20.5){$status_gizi = "Obesitas";}

        // jk p usia 48
        elseif($jk == "Perempuan" && $usia_bulan == 48 && $imt < 11.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 48 && $imt >= 11.8 && $imt < 12.8){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 48 && $imt >= 12.8 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 48 && $imt > 16.8 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 48 && $imt > 18.5 && $imt <= 20.6){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 48 && $imt > 20.6){$status_gizi = "Obesitas";}

        // jk p usia 49
        elseif($jk == "Perempuan" && $usia_bulan == 49 && $imt < 11.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 49 && $imt >= 11.8 && $imt < 12.8){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 49 && $imt >= 12.8 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 49 && $imt > 16.8 && $imt <= 18.5){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 49 && $imt > 18.5 && $imt <= 20.6){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 49 && $imt > 20.6){$status_gizi = "Obesitas";}

        // jk p usia 50
        elseif($jk == "Perempuan" && $usia_bulan == 50 && $imt < 11.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 50 && $imt >= 11.8 && $imt < 12.8){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 50 && $imt >= 12.8 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 50 && $imt > 16.8 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 50 && $imt > 18.6 && $imt <= 20.7){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 50 && $imt > 20.7){$status_gizi = "Obesitas";}

        // jk p usia 51
        elseif($jk == "Perempuan" && $usia_bulan == 51 && $imt < 11.8){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 51 && $imt >= 11.8 && $imt < 12.8){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 51 && $imt >= 12.8 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 51 && $imt > 16.8 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 51 && $imt > 18.6 && $imt <= 20.7){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 51 && $imt > 20.7){$status_gizi = "Obesitas";}

        // jk p usia 52
        elseif($jk == "Perempuan" && $usia_bulan == 52 && $imt < 11.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 52 && $imt >= 11.7 && $imt < 12.8){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 52 && $imt >= 12.8 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 52 && $imt > 16.8 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 52 && $imt > 18.6 && $imt <= 20.7){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 52 && $imt > 20.7){$status_gizi = "Obesitas";}

        // jk p usia 53
        elseif($jk == "Perempuan" && $usia_bulan == 53 && $imt < 11.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 53 && $imt >= 11.7 && $imt < 12.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 53 && $imt >= 12.7 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 53 && $imt > 16.8 && $imt <= 18.6){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 53 && $imt > 18.6 && $imt <= 20.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 53 && $imt > 20.8){$status_gizi = "Obesitas";}

        // jk p usia 54
        elseif($jk == "Perempuan" && $usia_bulan == 54 && $imt < 11.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 54 && $imt >= 11.7 && $imt < 12.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 54 && $imt >= 12.7 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 54 && $imt > 16.8 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 54 && $imt > 18.7 && $imt <= 20.8){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 54 && $imt > 20.8){$status_gizi = "Obesitas";}

        // jk p usia 55
        elseif($jk == "Perempuan" && $usia_bulan == 55 && $imt < 11.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 55 && $imt >= 11.7 && $imt < 12.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 55 && $imt >= 12.7 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 55 && $imt > 16.8 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 55 && $imt > 18.7 && $imt <= 20.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 55 && $imt > 20.9){$status_gizi = "Obesitas";}

        // jk p usia 56
        elseif($jk == "Perempuan" && $usia_bulan == 56 && $imt < 11.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 56 && $imt >= 11.7 && $imt < 12.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 56 && $imt >= 12.7 && $imt <= 16.8){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 56 && $imt > 16.8 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 56 && $imt > 18.7 && $imt <= 20.9){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 56 && $imt > 20.9){$status_gizi = "Obesitas";}

        // jk p usia 57
        elseif($jk == "Perempuan" && $usia_bulan == 57 && $imt < 11.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 57 && $imt >= 11.7 && $imt < 12.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 57 && $imt >= 12.7 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 57 && $imt > 16.9 && $imt <= 18.7){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 57 && $imt > 18.7 && $imt <= 21.0){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 57 && $imt > 21.0){$status_gizi = "Obesitas";}

        // jk p usia 58
        elseif($jk == "Perempuan" && $usia_bulan == 58 && $imt < 11.7){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 58 && $imt >= 11.7 && $imt < 12.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 58 && $imt >= 12.7 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 58 && $imt > 16.9 && $imt <= 18.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 58 && $imt > 18.8 && $imt <= 21.0){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 58 && $imt > 21.0){$status_gizi = "Obesitas";}

        // jk p usia 59
        elseif($jk == "Perempuan" && $usia_bulan == 59 && $imt < 11.6){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 59 && $imt >= 11.6 && $imt < 12.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 59 && $imt >= 12.7 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 59 && $imt > 16.9 && $imt <= 18.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 59 && $imt > 18.8 && $imt <= 21.0){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 59 && $imt > 21.0){$status_gizi = "Obesitas";}

        // jk p usia 60
        elseif($jk == "Perempuan" && $usia_bulan == 60 && $imt < 11.6){$status_gizi = "Gizi Buruk";}
        elseif($jk == "Perempuan" && $usia_bulan == 60 && $imt >= 11.6 && $imt < 12.7){$status_gizi = "Gizi Kurang";}
        elseif($jk == "Perempuan" && $usia_bulan == 60 && $imt >= 12.7 && $imt <= 16.9){$status_gizi = "Gizi Baik";}
        elseif($jk == "Perempuan" && $usia_bulan == 60 && $imt > 16.9 && $imt <= 18.8){$status_gizi = "Beresiko Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 60 && $imt > 18.8 && $imt <= 21.1){$status_gizi = "Gizi Lebih";}
        elseif($jk == "Perempuan" && $usia_bulan == 60 && $imt > 21.1){$status_gizi = "Obesitas";}

        // excepion
        else{$status_gizi = "status gizi tidak ditemukan";}

        return $status_gizi;
    }

    //kader
    public function pemeriksaan_kader(): View
    {
        $pemeriksaans = Pemeriksaan::all();
        $bayis = Bayi::all();
        return view('kader.pemeriksaan.main-pemeriksaan', compact('pemeriksaans','bayis'));
    }
    
    public function tambah_pemeriksaan_kader($id_bayi): View
    {
        $bayi = Bayi::findOrFail($id_bayi);
        $usia_bulan = Carbon::parse($bayi->tanggal_lahir)->diffInMonths(now());

        return view('kader.pemeriksaan.tambah-pemeriksaan', compact('bayi', 'usia_bulan'));
    }

    public function store_pemeriksaan_kader(Request $request): RedirectResponse
    {
        $id_bayi = $request->id_bayi;
        $bb = $request->bb;
        $tb = $request->tb;
        $lk = $request->lk;
        $imt = $this->get_imt($bb, $tb);
        $tgl_periksa = \Carbon\Carbon::now()->toDateString();
        $jk = $request->jk;
        $usia_bulan = $request->usia_bulan;
        $status_gizi = $this->get_status_gizi($bb, $tb, $jk, $usia_bulan);
        $get_nutrisi = $this->get_nutrisi_harian($usia_bulan);
        $kalori = $get_nutrisi['kalori'];
        $protein = $get_nutrisi['protein'];
        $lemak = $get_nutrisi['lemak'];
        $karbo = $get_nutrisi['karbo'];
        $serat = $get_nutrisi['serat'];
        
        $pemeriksaan = Pemeriksaan::create([
            'id_bayi' => $id_bayi,
            'bb' => $bb,
            'tb' => $tb,
            'lk' => $lk,
            'imt' => $imt,
            'tgl_periksa' => $tgl_periksa,
            'status_gizi' => $status_gizi,
            'kalori' => $kalori,
            'protein' => $protein,
            'lemak' => $lemak,
            'karbo' => $karbo,
            'serat' => $serat
        ]);

        return redirect()->route('detail_pemeriksaann', $pemeriksaan->id)->with(['message' => 'Pemeriksaan berhasil ditambahkan']);
    }

    public function detail_pemeriksaan_kader($id): View
    {
        $pemeriksaan = Pemeriksaan::with('bayi')->findOrFail($id);
        $usia_bulan = Carbon::parse($pemeriksaan->bayi->tanggal_lahir)->diffInMonths(now());
        return view('kader.pemeriksaan.detail-pemeriksaan', compact('pemeriksaan', 'usia_bulan'));
    }
    
    public function edit_pemeriksaan_kader($id): View
    {
        $pemeriksaan = Pemeriksaan::with('bayi')->findOrFail($id);
        $usia_bulan = Carbon::parse($pemeriksaan->bayi->tanggal_lahir)->diffInMonths(now());
        return view('kader.pemeriksaan.edit-pemeriksaan', compact('pemeriksaan', 'usia_bulan'));
    }

    public function update_pemeriksaan_kader(Request $request, $id): RedirectResponse
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $bb = $request->bb;
        $tb = $request->tb;
        $lk = $request->lk;
        $imt = $this->get_imt($bb, $tb);
        $jk = $request->jk;
        $usia_bulan = $request->usia_bulan;
        $status_gizi = $this->get_status_gizi($bb, $tb, $jk, $usia_bulan);
        $get_nutrisi = $this->get_nutrisi_harian($usia_bulan);
        $kalori = $get_nutrisi['kalori'];
        $protein = $get_nutrisi['protein'];
        $lemak = $get_nutrisi['lemak'];
        $karbo = $get_nutrisi['karbo'];
        $serat = $get_nutrisi['serat'];

        $pemeriksaan->update([
            'bb' => $bb,
            'tb' => $tb,
            'lk' => $lk,
            'imt' => $imt,
            'status_gizi' => $status_gizi,
            'kalori' => $kalori,
            'protein' => $protein,
            'lemak' => $lemak,
            'karbo' => $karbo,
            'serat' => $serat
        ]);

        return redirect()->route('detail_pemeriksaann', $id)->with(['message' => 'Pemeriksaan berhasil diedit']);
    }
    
    public function delete_pemeriksaan_kader($id): RedirectResponse
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        
        $pemeriksaan->delete();

        return redirect()->route('pemeriksaan_kader')->with(['message' => 'Pemeriksaan berhasil dihapus']);
    }

    
    //API Mobile
    public function get_pemeriksaan($id)
    {
        $pemeriksaan = Pemeriksaan::with('bayi')->findOrFail($id);
        $usia_bulan = Carbon::parse($pemeriksaan->bayi->tanggal_lahir)->diffInMonths(now());
        return response()->json([
            'pemeriksaan' => $pemeriksaan,
            'usia_bulan' => $usia_bulan
        ]);
    }

}
