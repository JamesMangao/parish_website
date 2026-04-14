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
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
                    ->subject('Sacramental Inquiry Update - ' . strtoupper($this->inquiry->status))
                    ->line('Your sacramental inquiry status has been updated.')
                    ->line('Current Status: ' . strtoupper($this->inquiry->status));

        if ($this->inquiry->status === 'declined' && $this->inquiry->rejection_reason) {
            $message->line('Reason for status: ' . $this->inquiry->rejection_reason);
        }

        return $message->action('Track Status', route('track.status', ['refId' => $this->inquiry->reference_id]))
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
