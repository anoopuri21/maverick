<x-mail::message>
# {{ $heading }}

@foreach ($rows as $row)
**{{ $row['label'] }}:** {{ $row['value'] }}

@endforeach
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
