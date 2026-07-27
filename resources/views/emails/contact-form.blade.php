<x-mail::message>
# New Contact Form Submission

You have received a new message from the website contact form.

**Name:** {{ $name }}
**Email:** {{ $email }}
**Phone:** {{ $phone }}
**Subject:** {{ $subjectLine }}

**Message:**
{{ $bodyMessage }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
