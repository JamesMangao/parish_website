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
                'refId' => $this->inquiry->reference_id,
                'requirements' => $this->getRequirements($this->inquiry->inquiry_type)
            ]);
    }

    private function getRequirements($type)
    {
        $requirements = [
            'Baptism' => ['Photocopy of PSA Birth Certificate', 'Marriage Contract of Parents', 'List of Godparents (Ninong/Ninang)'],
            'First Communion' => ['Photocopy of Baptismal Certificate'],
            'Confirmation' => ['Photocopy of Baptismal Certificate'],
            'Wedding' => ['PSA Birth Certificate & CENOMAR', 'Baptismal & Confirmation Certificate (New copy, for Marriage Purpose)', 'Marriage License or Canonical Interview', 'Pre-Cana Seminar Certificate'],
            'Funeral Mass' => ['Photocopy of Death Certificate'],
            'Baptismal Certificate' => ['Full Name of the person', 'Date of Baptism', 'Names of Parents'],
            'Confirmation Certificate' => ['Full Name of the person', 'Date of Confirmation', 'Names of Parents'],
            'Marriage Certificate' => ['Names of Couple', 'Date of Wedding'],
            'Car Blessing' => ['Vehicle Type & Plate Number', 'Preferred Time'],
            'House Blessing' => ['Exact Address/Location', 'Preferred Time'],
        ];

        return $requirements[$type] ?? [];
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
