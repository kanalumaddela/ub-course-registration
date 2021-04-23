<?php

namespace App\Notifications;

use App\Models\StudentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentRegistered extends Notification
{
    use Queueable;

    protected $studentRegistration;

    protected $course;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(StudentRegistration $studentRegistration = null)
    {
        $studentRegistration->load([
            'student',
            'courseSection.course'
        ]);

        $this->course = $studentRegistration->courseSection->course;

        $this->studentRegistration = $studentRegistration;
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
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'text' => $this->studentRegistration->student->name.' is waiting for approval to register for: '.$this->course->name_shorthand.'-'.$this->studentRegistration->courseSection->number,
            'url' => route('advisor.registrations.student', $this->studentRegistration->student)
        ];
    }
}
