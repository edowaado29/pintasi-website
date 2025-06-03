<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ibu;
use App\Models\Jadwal;
use App\Services\FirebaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function b_jadwal(): View
    {
        $jadwals = Jadwal::all()->sortBy('created_at');
        return view('puskesmas.jadwal.main-jadwal', compact('jadwals'));
    }

    public function b_tambah_jadwal(): View
    {
        return view('puskesmas.jadwal.tambah-jadwal');
    }

    public function b_add_jadwal(Request $request): RedirectResponse
    {
        $request->validate([
            'tanggal_pemeriksaan' => 'required|date',
            'jam_pemeriksaan' => 'required|date_format:H:i',
            'jenis_pemeriksaan' => 'required|in:Imunisasi,Posyandu',
            'tempat' => 'required|string|max:255',
        ], [
            'tanggal_pemeriksaan.required' => 'Tanggal pemeriksaan tidak boleh kosong.',
            'jam_pemeriksaan.required' => 'Jam pemeriksaan tidak boleh kosong.',
            'jenis_pemeriksaan.required' => 'Jenis pemeriksaan tidak boleh kosong.',
            'jenis_pemeriksaan.in' => 'Jenis pemeriksaan harus berupa Imunisasi atau Posyandu.',
            'tempat.required' => 'Tempat pemeriksaan tidak boleh kosong.',
        ]);

        try {
            
            $jadwal = Jadwal::create([
                'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
                'jam_pemeriksaan' => $request->jam_pemeriksaan,
                'jenis_pemeriksaan' => $request->jenis_pemeriksaan,
                'tempat' => $request->tempat,
            ]);

            
            $tokens = Ibu::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

            if (count($tokens) > 0) {
                $firebase = new FirebaseService();

                // Kirim notifikasi dalam chunk 1000 token
                foreach (array_chunk($tokens, 1000) as $chunk) {
                    $firebase->sendMulticastNotification(
                        $chunk,
                        'Jadwal Baru',
                        'Jadwal pemeriksaan' . $jadwal->jenis_pemeriksaan . 'telah ditambahkan.',
                        [
                            'jadwal_id' => (string) $jadwal->id,
                            'tanggal' => $jadwal->tanggal_pemeriksaan,
                            'jam' => $jadwal->jam_pemeriksaan,
                            'tempat' => $jadwal->tempat,
                        ]
                    );
                }
            }

            return redirect()->route('b/jadwal')->with(['message' => 'Jadwal berhasil ditambahkan']);
        } catch (\Exception $e) {
            return redirect()->route('b/tambah_jadwal')->with(['error' => 'Gagal menambahkan jadwal: ' . $e->getMessage()]);
        }
    }

    public function b_edit_jadwal(string $id): View
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('puskesmas.jadwal.edit-jadwal', compact('jadwal'));
    }

    public function b_update_jadwal(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'tanggal_pemeriksaan' => 'required|date',
            'jam_pemeriksaan' => 'required|date_format:H:i',
            'jenis_pemeriksaan' => 'required|in:Imunisasi,Posyandu',
            'tempat' => 'required|string|max:255',
        ], [
            'tanggal_pemeriksaan.required' => 'Tanggal pemeriksaan tidak boleh kosong.',
            'jam_pemeriksaan.required' => 'Jam pemeriksaan tidak boleh kosong.',
            'jenis_pemeriksaan.required' => 'Jenis pemeriksaan tidak boleh kosong.',
            'jenis_pemeriksaan.in' => 'Jenis pemeriksaan harus berupa Imunisasi atau Posyandu.',
            'tempat.required' => 'Tempat pemeriksaan tidak boleh kosong.',
        ]);

        try {
            // Cari jadwal berdasarkan ID
            $jadwal = Jadwal::findOrFail($id);

            // Perbarui data jadwal
            $jadwal->update([
                'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
                'jam_pemeriksaan' => $request->jam_pemeriksaan,
                'jenis_pemeriksaan' => $request->jenis_pemeriksaan,
                'tempat' => $request->tempat,
            ]);

            // Ambil semua token FCM dari tabel Ibu
            $tokens = Ibu::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

            if (count($tokens) > 0) {
                $firebase = new FirebaseService();

                // Kirim notifikasi dalam chunk 1000 token
                foreach (array_chunk($tokens, 1000) as $chunk) {
                    $firebase->sendMulticastNotification(
                        $chunk,
                        'Jadwal Diperbarui',
                        'Jadwal pemeriksaan "' . $jadwal->jenis_pemeriksaan . '" telah diperbarui.',
                        [
                            'jadwal_id' => (string) $jadwal->id,
                            'tanggal' => $jadwal->tanggal_pemeriksaan,
                            'jam' => $jadwal->jam_pemeriksaan,
                            'tempat' => $jadwal->tempat,
                        ]
                    );
                }
            }

            return redirect()->route('b/jadwal')->with(['message' => 'Jadwal berhasil diperbarui']);
        } catch (\Exception $e) {
            return redirect()->route('b/tambah_jadwal')->with(['error' => 'Gagal memperbarui jadwal: ' . $e->getMessage()]);
        }
    }

    public function b_hapus_jadwal(string $id): RedirectResponse
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        return redirect()->route('b/jadwal')->with(['message' => 'Jadwal berhasil dihapus']);
    }


    //API Mobile
    public function index() {
        $penjadwalans = Jadwal::orderBy('created_at', 'desc')->get();
        return response()->json($penjadwalans);
    }
}
