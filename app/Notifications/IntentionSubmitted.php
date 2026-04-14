<?php

namespace App\Notifications;

use App\Models\MassIntention;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class IntentionSubmitted extends Notification
{
    use Queueable;

    protected $intention;

    public function __construct(MassIntention $intention)
    {
        $this->intention = $intention;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $refId = $this->intention->reference_number;
        
        return (new MailMessage)
            ->subject('Mass Intention Received - ' . $refId)
            ->greeting('Hello ' . $this->intention->full_name . '!')
            ->line('We have received your request for a Mass Intention.')
            ->line('Reference ID: ' . $refId)
            ->line('Type: ' . $this->intention->intention_type)
            ->line('Date: ' . \Carbon\Carbon::parse($this->intention->preferred_date)->format('M d, Y'))
            ->line('Time: ' . $this->intention->mass_time)
            ->line('Message: ' . $this->intention->raw_message)
            ->line('Our parish staff will review your request shortly.')
            ->action('Track Status', route('track.status', ['refId' => $refId]))
            ->line('Thank you for being part of our community.');
    }

    public function toArray($notifiable)
    {
        return [
            'intention_id' => $this->intention->id,
            'message' => 'New mass intention received from ' . $this->intention->full_name,
        ];
    }
}
