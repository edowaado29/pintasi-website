<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], remember: $request->has('remember'))) {
            $kaders = Auth::user();
            $request->session()->regenerate();

            if ($kaders->role == 'kader') {
                return redirect()->route('k/dashboard')->with(['message' => 'Login Berhasil']);
            } elseif ($kaders->role == 'bidan') {
                return redirect()->route('b/dashboard')->with(['message' => 'Login Berhasil']);
            }
        }
        // return redirect()->route('login')->with(['message' => 'Login Gagal, Periksa Kembali Email dan Password Anda'])
        //     ->withInput($request->only('email', 'remember'));
        return redirect()->route('login')->with(['failed' => 'Login Gagal, Periksa Kembali Email dan Password Anda']);
    }
    

    public function forgot_password_act(Request $request)
    {
        $customMessage = [
            'email.required'    => 'Email tidak boleh kosong',
            'email.email'       => 'Email tidak valid',
            'email.exists'      => 'Email tidak terdaftar',
        ];

        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], $customMessage);

        $token = Str::random(60);

        PasswordResetToken::updateOrCreate(
            [
                'email' => $request->email
            ],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]
        );

        Mail::to($request->email)->send(new ResetPasswordMail($token));

        return redirect()->route('login')->with('success', 'Permintaan berhasil dikirim ke email Anda');
    }

    public function validasi_forgot_password(Request $request, $token)
    {
        $getToken = PasswordResetToken::where('token', $token)->first();

        if (!$getToken) {
            return redirect()->route('login')->with('failed', 'Token tidak valid');
        }

        return view('puskesmas.auth.validasi-token', compact('token'));
    }

    public function validasi_forgot_password_act(Request $request)
    {
        $customMessage = [
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password harus terdiri dari 8-16 karakter',
            'password.max' => 'Password harus terdiri dari 8-16 karakter',
        ];

        $request->validate([
            'password' => 'required|min:8|max:16'
        ], $customMessage);

        $token = PasswordResetToken::where('token', $request->token)->first();

        if (!$token) {
            return redirect()->route('login')->with('failed', 'Token tidak valid');
        }

        $user = User::where('email', $token->email)->first();

        if (!$user) {
            return redirect()->route('login')->with('failed', 'Email tidak terdaftar');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $token->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with(['message' => 'Berhasil Logout']);
    }
}



// <?php
// namespace App\Http\Controllers;

// use App\Models\Akun;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Http\Request;

// class ResetPasswordController extends Controller
// {
//     // Mengirim email OTP
//     private function sendOtpEmail($email, $otp)
//     {
//         Mail::raw("
//             Halo,

//             Anda telah meminta reset password untuk akun 
//             di aplikasi Pintasi.
//             Kode OTP Anda adalah: $otp

//             Abaikan email ini jika Anda tidak merasa meminta 
//             reset password.

//             Salam,
//             Tim Pintasi
//         ", function ($message) use ($email) {
//             $message->to($email)
//                     ->subject('Kode OTP - Reset Password Pintasi');
//         });
//     }

//     // Request OTP atau Kirim OTP
//     public function requestOtp(Request $request)
//     {
//         $request->validate([
//             'email' => 'required|email',
//         ]);

//         $akun = Akun::where('email', $request->email)->first();
//         if (!$akun) {
//             return response()->json(['success' => false, 'message' => 'Email tidak terdaftar'], 404);
//         }

//         $otp = rand(1000, 9999);

//         DB::table('password_reset_tokens')->updateOrInsert(
//             ['email' => $request->email],
//             ['token' => $otp, 'created_at' => now()]
//         );

//         // Kirim email OTP
//         $this->sendOtpEmail($request->email, $otp);

//         return response()->json(['success' => true]);
//     }

//     // Verifikasi OTP
//     public function verifyOtp(Request $request)
//     {
//         $request->validate([
//             'email' => 'required|email',
//             'otp' => 'required|numeric|digits:4',
//         ]);

//         $check = DB::table('password_reset_tokens')
//             ->where('email', $request->email)
//             ->where('token', $request->otp)
//             ->first();

//         if (!$check) {
//             return response()->json(['success' => false, 'message' => 'OTP salah atau sudah kadaluarsa'], 400);
//         }

//         return response()->json(['success' => true]);
//     }

//     // Reset Password
//     public function resetPassword(Request $request)
//     {
//         $request->validate([
//             'email' => 'required|email',
//             'password' => 'required|min:8|confirmed',
//         ]);

//         $akun = Akun::where('email', $request->email)->first();
//         if (!$akun) {
//             return response()->json(['success' => false, 'message' => 'Email tidak terdaftar'], 404);
//         }

//         // Update password baru
//         $akun->password = Hash::make($request->password);
//         $akun->save();

//         // Hapus data OTP
//         DB::table('password_reset_tokens')->where('email', $request->email)->delete();

//         return response()->json(['success' => true, 'message' => 'Password berhasil diperbarui']);
//     }
// }
