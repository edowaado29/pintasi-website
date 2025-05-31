<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('puskesmas.auth.login');
    }

    public function loginUser(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ],
            [
                'email.required' => 'Email tidak boleh kosong.',
                'password.required' => 'Password tidak boleh kosong.',
                'password.min' => 'Password minimal 8 karakter.',
            ]
        );

        // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

        //     $kaders = User::where('email', '=', $request->email)->firstOrFail();
        //     $request->session()->regenerate();

        //     if ($request->has('remember')) {
        //         $cookie = cookie('remember', $request->email, 60 * 24 * 30); // 30 days
        //         return redirect()->route('dashboard')->with(['message' => 'Login Berhasil'])->cookie($cookie);
        //     }

        //     if ($kaders->role == 'kader') {
        //         return redirect()->route('dashboard_kader')->with(['message' => 'Login Berhasil']);
        //     } elseif ($kaders->role == 'bidan') {
        //         return redirect()->route('dashboard')->with(['message' => 'Login Berhasil']);
        //     }
        // }
        
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password], remember: $request->has('remember'))) {
                    $kaders = Auth::user();
                    $request->session()->regenerate();

                    if ($kaders->role == 'kader') {
                        return redirect()->route('dashboard_kader')->with(['message' => 'Login Berhasil']);
                    } elseif ($kaders->role == 'bidan') {
                        return redirect()->route('dashboard')->with(['message' => 'Login Berhasil']);
                    }
                }
        return redirect()->route('login')->with(['message' => 'Login Gagal, Periksa Kembali Email dan Password Anda'])
            ->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with(['message' => 'Berhasil Logout']);
    }
}
