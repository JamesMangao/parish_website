@component('mail::message')
# Sacramental Inquiry Update

Dear {{ $inquiry->full_name ?? 'Parishioner' }},

Your sacramental inquiry submission (Ref: **{{ $refId }}**) for **{{ $inquiry->inquiry_type }}** has been updated to **{{ strtoupper($inquiry->status) }}**.

@if($inquiry->status === 'declined' && $inquiry->rejection_reason)
**Reason:**
> {{ $inquiry->rejection_reason }}
@endif

---

### Final Instructions
@if($inquiry->status === 'accepted')
Your inquiry has been accepted and forwarded to the parish office. Please prepare any necessary documents required for the sacrament and wait for our staff to contact you to finalize your schedule.
@elseif($inquiry->status === 'declined')
We are unable to process your inquiry at this time. Please refer to the reason provided above. You may submit a new inquiry or visit the parish office for assistance.
@else
Your inquiry is currently under review.
@endif

@component('mail::button', ['url' => route('track.status', ['refId' => $refId])])
Track Status Online
@endcomponent

Thank you for using Parish Pal!<br>
God bless,<br>
**Sto. Rosario Parish**
@endcomponent
