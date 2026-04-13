<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InquiryStatusUpdated extends Notification
{
    use Queueable;

    private $inquiry;

    public function __construct($inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Sacramental Inquiry Update - ' . $this->inquiry->status)
                    ->line('Your sacramental inquiry status has been updated.')
                    ->line('Current Status: ' . strtoupper($this->inquiry->status))
                    ->action('Track Status', route('track', ['reference_id' => $this->inquiry->reference_id]))
                    ->line('Thank you for using Parish Pal!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'inquiry',
            'id' => $this->inquiry->id,
            'status' => $this->inquiry->status,
            'message' => 'Inquiry status updated to ' . $this->inquiry->status,
        ];
    }
}
