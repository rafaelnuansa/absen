<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class AlertController extends Controller
{
    public function sendSOS()
    {
        fcm()
        ->toTopic('push-notifications')
        ->priority('high')
        ->timeToLive(0)
        ->notification([
            'title' => 'PERINGATAN SOS',
            'body' => 'Peringatan Harap tetap tenang, dan evakuasi ketempat yang aman.',
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
        ])
        ->send();

        return response()->json([
            'success' => true,
            'message' => 'Permintaan SOS berhasil dikirim'], 200);
    }
}
