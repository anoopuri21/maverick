<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ $heading }}</title>
</head>
<body style="margin:0; padding:0; background-color:#eef0f4; -webkit-font-smoothing:antialiased;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#eef0f4;">
        <tr>
            <td align="center" style="padding:28px 12px;">

                {{-- Outer card --}}
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:600px; max-width:100%; background-color:#ffffff; border-radius:10px; overflow:hidden; font-family:Helvetica, Arial, sans-serif;">

                    {{-- Brand band: Maverick logo --}}
                    <tr>
                        <td style="background-color:#071444; border-bottom:3px solid #b20202; padding:22px 32px;" align="left">
                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="display:block; height:40px; width:auto; border:0; max-width:70%;">
                            @else
                                <span style="display:block; height:40px; line-height:40px; color:#ffffff; font-size:19px; font-weight:bold; letter-spacing:0.5px;">{{ $siteName }}</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:30px 32px 34px;">

                            {{-- Heading --}}
                            <h1 style="margin:0 0 18px; font-size:19px; line-height:1.35; color:#071444; font-weight:700;">{{ $heading }}</h1>

                            {{-- Header rich text (admin managed, optional) --}}
                            @if ($headerHtml)
                                <div style="margin:0 0 20px; padding:14px 16px; background-color:#f5f0eb; border-left:3px solid #0f2983; border-radius:0 6px 6px 0; font-size:14px; line-height:1.6; color:#3d4556;">
                                    {!! $headerHtml !!}
                                </div>
                            @endif

                            {{-- Form details --}}
                            @if (count($rows) === 1)
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="width:100%; font-size:14px; color:#333a47;">
                                    @foreach ($rows as $row)
                                        <tr>
                                            <td style="padding:6px 0; line-height:1.6; word-break:break-word;"><strong style="color:#071444;">{{ $row['label'] }}:</strong> {{ $row['value'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="width:100%; font-size:14px; color:#333a47; border-collapse:collapse;">
                                    @foreach ($rows as $i => $row)
                                        <tr style="background-color:{{ $i % 2 === 1 ? '#f8f9fb' : '#ffffff' }};">
                                            <td width="32%" style="padding:10px 12px; border-bottom:1px solid #e6e8ee; vertical-align:top; font-weight:bold; color:#071444;">{{ $row['label'] }}</td>
                                            <td style="padding:10px 12px; border-bottom:1px solid #e6e8ee; vertical-align:top; line-height:1.55; word-break:break-word;">{{ $row['value'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                            {{-- Footer / regards rich text (admin managed, optional — no fallback) --}}
                            @if ($footerHtml)
                                <div style="margin-top:24px; padding-top:16px; border-top:2px solid #0f2983; font-size:14px; line-height:1.65; color:#3d4556;">
                                    {!! $footerHtml !!}
                                </div>
                            @endif

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>
</html>
