<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\AndroidNotification;


class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials'));

        $this->messaging = $factory->createMessaging();
    }

    /**
     * Kirim notifikasi push ke device dengan FCM token tertentu
     *
     * @param string $fcmToken Token FCM dari device Flutter
     * @param string $title Judul notifikasi
     * @param string $body Isi notifikasi
     * @param array $data (optional) Data tambahan yang ingin dikirim
     * @return void
     */
    public function sendNotification(string $fcmToken, string $title, string $body, array $data = [])
    {
        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token', $fcmToken)
            ->withNotification($notification)
            ->withData($data);

        $this->messaging->send($message);
    }

    // public function sendMulticastNotification(array $tokens, $title, $body, $data = [])
    // {
    //     $notification = Notification::create($title, $body);

    //     $messages = collect($tokens)->map(function ($token) use ($notification, $data) {
    //         return CloudMessage::withTarget('token', $token)
    //             ->withNotification($notification)
    //             ->withData($data);
    //     })->all();

    //     $report = $this->messaging->sendAll($messages);
    //     return $report;
    // }

    public function sendMulticastNotification(array $tokens, string $title, string $body, array $data = [])
    {
        $data = array_merge([
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'id' => '1',
            'status' => 'done',
        ], $data);

        $notification = Notification::create($title, $body);

        $androidConfig = AndroidConfig::fromArray([
            'priority' => 'high',
            'notification' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ]);

        $messages = collect($tokens)->map(function ($token) use ($notification, $data, $androidConfig) {
            return CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data)
                ->withAndroidConfig($androidConfig);
        })->all();

        $report = $this->messaging->sendAll($messages);
        return $report;
    }
}
