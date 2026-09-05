{{ $siteName }}

{{ $heading }}
================

@if ($headerText)
{{ $headerText }}

@endif
@foreach ($rows as $row)
{{ $row['label'] }}: {{ $row['value'] }}

@endforeach
@if ($footerText)
{{ $footerText }}
@endif
