<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ibu;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{

public function register(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|string|email|unique:ibus,email', // Validasi email unik
            'password' => 'required|string|min:8', // Password minimal 8 karakter
            'nik' => 'required|string|size:16', // NIK harus 16 karakter
            'nama_ibu' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
        ]);

        $ibus = new Ibu();
        $ibus->email = $request->email;
        $ibus->password = Hash::make($request->password);
        $ibus->nik = $request->nik;
        $ibus->nama_ibu = $request->nama_ibu;
        $ibus->tempat_lahir = $request->tempat_lahir;
        $ibus->tanggal_lahir = $request->tanggal_lahir;
        $ibus->save();

        return response()->json([
            'message' => 'Pendaftaran berhasil',
            'data' => $ibus,
        ], 201); // Gunakan status code 201 untuk resource yang berhasil dibuat
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Terjadi error',
            'error' => $e->getMessage(),
        ], 500);
    }

    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
                'fcm_token' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),  // Perbaiki bagian ini
                ], 422);
            }

            $ibus = Ibu::where('email', $request->email)->first();

            if (!$ibus || !Hash::check($request->password, $ibus->password)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }

            if ($request->filled('fcm_token')) {
                $ibus->fcm_token = $request->fcm_token;
                $ibus->save();
            }

            // Membuat token menggunakan Sanctum
            $token = $ibus->createToken('MyApp')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user_id' => $ibus->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
