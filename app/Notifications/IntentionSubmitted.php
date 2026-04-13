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
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $refId = Str::upper(Str::substr($this->intention->id, 0, 8));
        
        return (new MailMessage)
            ->subject('Mass Intention Received - ' . $refId)
            ->greeting('Hello ' . $this->intention->full_name . '!')
            ->line('We have received your request for a Mass Intention.')
            ->line('Reference ID: ' . $refId)
            ->line('Type: ' . $this->intention->intention_type)
            ->line('Date: ' . $this->intention->preferred_date)
            ->line('Time: ' . $this->intention->mass_time)
            ->line('Message: ' . $this->intention->raw_message)
            ->line('Our parish staff will review your request shortly.')
            ->action('Track Status', url('/track?reference_id=' . $refId))
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
