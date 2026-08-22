{{ $heading }}

@foreach ($rows as $row)
{{ $row['label'] }}: {{ $row['value'] }}

@endforeach
Thanks,
{{ config('app.name') }}
