<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IntentionStatusUpdated extends Notification
{
    private $intention;

    public function __construct($intention)
    {
        $this->intention = $intention;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $refId = $this->intention->reference_number ?? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($this->intention->id, 0, 8));
        
        return (new MailMessage)
            ->subject('Mass Intention Update: ' . strtoupper($this->intention->status))
            ->markdown('emails.intention_status', [
                'intention' => $this->intention,
                'refId' => $refId
            ]);
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
