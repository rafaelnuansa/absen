<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\FcmMessage;

class SOSNotification extends Notification
{
    use Queueable;

    protected $employee;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @param Employee $employee
     * @param string $message
     * @return void
     */
    public function __construct($employee, $message)
    {
        $this->employee = $employee;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm']; // Menggunakan Firebase Cloud Messaging
    }

    /**
     * Get the FCM representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\FcmMessage
     */
    public function toFcm($notifiable)
    {
        return  fcm()
        ->toTopic('push-notifications')
        ->priority('normal')
        ->timeToLive(0)
        ->notification([
            'title' => 'SOS',
            'body' => $this->message,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
        ])
        ->send();
    }
}
