<?php


namespace App\Notifications;


class TestNotification extends BaseNotification
{
    public function __construct($data = null)
    {

    }

    public function toArray($notifiable): array
    {
        return [
            'url' => route('index'),
            'text' => 'This is a test notification',
        ];
    }
}
