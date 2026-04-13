<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IntentionStatusUpdated extends Notification
{
    use Queueable;

    private $intention;

    public function __construct($intention)
    {
        $this->intention = $intention;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $refId = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($this->intention->id, 0, 8));
        return (new MailMessage)
                    ->subject('Mass Intention Update - ' . $this->intention->status)
                    ->line('Your mass intention status has been updated.')
                    ->line('Current Status: ' . strtoupper($this->intention->status))
                    ->action('Track Status', route('track', ['reference_id' => $refId]))
                    ->line('Thank you for using Parish Pal!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'intention',
            'id' => $this->intention->id,
            'status' => $this->intention->status,
            'message' => 'Intention status updated to ' . $this->intention->status,
        ];
    }
}
