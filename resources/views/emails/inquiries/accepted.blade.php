<x-mail::message>
# New Validated Inquiry

A new service inquiry has been reviewed and validated by the SocCom/Admin team.

**Inquiry Details:**
- **Full Name:** {{ $inquiry->full_name }}
- **Email:** {{ $inquiry->email }}
- **Phone:** {{ $inquiry->phone ?? 'N/A' }}
- **Service Type:** {{ $inquiry->inquiry_type }}
@if(str_contains($inquiry->message, '[Wants to request a document: YES]'))
- **Request a Document:** YES ✅
@elseif(str_contains($inquiry->message, '[Wants to request a document: NO]'))
- **Request a Document:** NO ❌
@endif

**Message:**
{{ $inquiry->message }}

Please process this inquiry accordingly.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
