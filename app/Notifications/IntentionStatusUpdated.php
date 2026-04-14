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
        $refId = $this->intention->reference_number ?? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($this->intention->id, 0, 8));
        $message = (new MailMessage)
                    ->subject('Mass Intention Update: ' . strtoupper($this->intention->status))
                    ->line('Dear ' . ($this->intention->full_name ?? 'Parishioner') . ',')
                    ->line('Your mass intention submission (Ref: ' . $refId . ') has been ' . $this->intention->status . '.');
        
        if ($this->intention->status === 'rejected' && $this->intention->rejection_reason) {
            $message->line('Reason for rejection: ' . $this->intention->rejection_reason);
        }

        return $message->action('Track Status', route('track', ['reference_id' => $refId]))
                    ->line('Thank you for your patience and faith.')
                    ->line('God bless!');
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
