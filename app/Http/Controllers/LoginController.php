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
                'email' => 'required|string',
                'password' => 'required|string',
            ]);

            $ibus = new Ibu();
            $ibus->email = $request->email;
            $ibus->password = Hash::make($request->password);
            $ibus->save();

            return response()->json([
                'message' => 'anjay berhasil',
                'data' => $ibus,
            ], 201);
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
    
            // Membuat token menggunakan Sanctum
            $token = $ibus->createToken('MyApp')->plainTextToken;
    
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }    
}
