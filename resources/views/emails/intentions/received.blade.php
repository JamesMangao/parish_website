<x-mail::message>
# Mass Intention Received

Dear {{ $intention->full_name }},

Peace be with you!

We have successfully received your mass intention submission. Our parish office will review the details and include it in our scheduled masses once validated.

**Reference Number:** `{{ $intention->reference_number }}`

**Intention Details:**
- **Type:** {{ $intention->intention_type }}
- **Preferred Date:** {{ $intention->preferred_date?->format('F d, Y') ?? 'N/A' }}
- **Mass Time:** {{ $intention->mass_time ?? 'N/A' }}

**Your Message:**
> {{ $intention->raw_message }}

@if($intention->payment_method)
**Donation Method:** {{ $intention->payment_method }}
@endif

Please quote the reference number above if you need to follow up with the parish office via email or phone.

Thank you for your faith and support.

God bless,

**{{ config('app.name') }} Team**
</x-mail::message>
