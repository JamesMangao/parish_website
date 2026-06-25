<?php

namespace App\Jobs;

use App\Models\Inquiry;
use App\Notifications\InquirySubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendInquirySubmittedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public Inquiry $inquiry
    ) {}

    public function handle(): void
    {
        Notification::route('mail', $this->inquiry->email)
            ->notify(new InquirySubmitted($this->inquiry));
    }
}
