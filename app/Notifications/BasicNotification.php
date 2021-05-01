<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BasicNotification extends Notification
{
    use Queueable;

    protected $message;

    protected $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $url = null)
    {
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'text' => $this->message,
            'url'  => $this->url,
        ];
    }
}
