<?php

namespace App\Notifications;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InquirySubmitted extends Notification
{
    use Queueable;

    protected $inquiry;

    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Parish Inquiry Received - ' . $this->inquiry->reference_id)
            ->greeting('Hello ' . $this->inquiry->full_name . '!')
            ->line('We have received your inquiry regarding ' . $this->inquiry->inquiry_type . '.')
            ->line('Reference ID: ' . $this->inquiry->reference_id)
            ->line('Type: ' . $this->inquiry->inquiry_type);

        if ($this->inquiry->preferred_date) {
            $mail->line('Preferred Date: ' . $this->inquiry->preferred_date->format('M d, Y'));
        }

        return $mail->line('Our parish team is reviewing your request and will get back to you soon.')
            ->action('Track Status', route('track.status', ['refId' => $this->inquiry->reference_id]))
            ->line('Thank you for contacting Sto. Rosario Parish.');
    }

    public function toArray($notifiable)
    {
        return [
            'inquiry_id' => $this->inquiry->id,
            'message' => 'New inquiry: ' . $this->inquiry->inquiry_type . ' from ' . $this->inquiry->full_name,
        ];
    }
}
