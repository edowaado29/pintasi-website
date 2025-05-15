<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function sendTestNotification(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array'
        ]);

        try {
            $this->firebase->sendNotification(
                $request->fcm_token,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            return response()->json(['message' => 'Notifikasi berhasil dikirim'], 200);
        } catch (\Throwable $e) {
            Log::error('Firebase Notification Error: '.$e->getMessage());

            return response()->json([
                'message' => 'Gagal mengirim notifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
