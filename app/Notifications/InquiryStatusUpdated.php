<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InquiryStatusUpdated extends Notification
{
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
        return (new MailMessage)
            ->subject('Sacramental Inquiry Update: ' . strtoupper($this->inquiry->status))
            ->markdown('emails.inquiry_status', [
                'inquiry' => $this->inquiry,
                'refId' => $this->inquiry->reference_id
            ]);
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
