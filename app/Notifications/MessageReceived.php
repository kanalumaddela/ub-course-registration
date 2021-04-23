<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MessageReceived extends Notification
{
    use Queueable;

    /**
     * @var \App\Models\User
     */
    private $user;

    /**
     * @var \App\Models\Conversation
     */
    private $conversation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Conversation $conversation, User $user)
    {
        $this->conversation = $conversation;
        $this->user = $user;
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
            'text' => $this->user->name.' has sent you a message',
            'url'  => route('messages.index', $this->conversation),
        ];
    }
}
