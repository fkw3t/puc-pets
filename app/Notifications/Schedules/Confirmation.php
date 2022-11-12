<?php

namespace App\Notifications\Schedules;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Confirmation extends Notification
{
    use Queueable;

    private Schedule $schedule;
    private string $confirmationUrl;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Schedule $schedule, string $confirmationUrl)
    {
        $this->schedule = $schedule;
        $this->confirmationUrl = $confirmationUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Schedule confirmation')
            ->line("Hi there, we want you to confirm your pending schedule at {$this->schedule->date}")
            ->action('Confirm', $this->confirmationUrl)
            ->line('Thank\'s for using ours services!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
