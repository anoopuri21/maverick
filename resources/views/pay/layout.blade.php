<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="referrer" content="no-referrer">
    <title>@yield('title', 'Secure payment') · Maverick Business Academy</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        :root {
            --blue: #0f2983;
            --dark-blue: #071444;
            --red: #b20202;
            --ink: #0a0a0a;
            --muted: #5b6472;
            --line: #e5e7ef;
            --bg: #f6f7fb;
            --white: #fff;
            --ok: #0f7b3f;
        }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font: 400 16px/1.6 "Poppins", system-ui, -apple-system, "Segoe UI", sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .shell { max-width: 880px; margin: 0 auto; padding: 24px 18px 56px; }
        .topbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 8px 0 22px; }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 600; letter-spacing: .01em; }
        .brand span { font-size: 15px; }
        .brand .dot { width: 10px; height: 10px; border-radius: 50%; background: var(--red); }
        .secure { display: inline-flex; align-items: center; gap: 7px; font-size: 12.5px; color: var(--muted); }
        .card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 26px;
            box-shadow: 0 10px 30px rgba(7, 20, 68, .06);
        }
        .card + .card { margin-top: 18px; }
        h1 { font-size: clamp(22px, 3.2vw, 28px); margin: 0 0 6px; letter-spacing: -.01em; }
        h2 { font-size: 17px; margin: 0 0 14px; }
        p.lede { margin: 0; color: var(--muted); font-size: 14.5px; }
        .meta { display: grid; gap: 12px; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); margin: 22px 0 0; }
        .meta div { background: #fafbff; border: 1px solid var(--line); border-radius: 12px; padding: 12px 14px; }
        .meta dt { font-size: 11.5px; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); margin: 0 0 4px; }
        .meta dd { margin: 0; font-weight: 500; font-size: 15px; }
        .amount { font-variant-numeric: tabular-nums; }
        .progress { margin: 22px 0 0; }
        .bar { height: 8px; border-radius: 999px; background: #e9ecf5; overflow: hidden; }
        .bar > i { display: block; height: 100%; background: var(--blue); }
        .progress-row { display: flex; justify-content: space-between; font-size: 13px; color: var(--muted); margin-top: 8px; }
        table { width: 100%; border-collapse: collapse; font-size: 14.5px; }
        th, td { text-align: left; padding: 11px 8px; border-bottom: 1px solid var(--line); }
        th { font-size: 11.5px; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); font-weight: 500; }
        td.num, th.num { text-align: right; font-variant-numeric: tabular-nums; }
        tr:last-child td { border-bottom: 0; }
        .tag { display: inline-block; font-size: 11.5px; padding: 3px 9px; border-radius: 999px; background: #eef1f9; color: var(--muted); }
        .tag--paid { background: #e8f6ee; color: var(--ok); }
        .tag--due { background: #fdecec; color: var(--red); }
        .tag--next { background: #eaefff; color: var(--blue); }
        .pay-box { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            border: 0; border-radius: 10px; padding: 13px 22px; cursor: pointer;
            font: 500 15px/1 "Poppins", sans-serif; text-decoration: none;
        }
        .btn--primary { background: var(--blue); color: #fff; }
        .btn--primary:hover { background: var(--dark-blue); }
        .btn--ghost { background: transparent; border: 1px solid var(--line); color: var(--ink); }
        .btn--ghost:hover { border-color: var(--blue); color: var(--blue); }
        .pay-total { font-size: 13px; color: var(--muted); }
        .pay-total strong { display: block; font-size: 24px; color: var(--ink); font-variant-numeric: tabular-nums; }
        .notice { border-radius: 12px; padding: 13px 15px; font-size: 14px; margin-bottom: 18px; }
        .notice--error { background: #fdecec; color: #7d0101; border: 1px solid #f7cfcf; }
        .notice--ok { background: #e8f6ee; color: #075f31; border: 1px solid #c8e9d7; }
        .notice--info { background: #eef2ff; color: #1b2f7a; border: 1px solid #d9e0ff; }
        .fine { font-size: 12.5px; color: var(--muted); margin: 14px 0 0; }
        .trust { display: flex; flex-wrap: wrap; gap: 14px 22px; margin-top: 16px; font-size: 12.5px; color: var(--muted); }
        .foot { margin-top: 22px; font-size: 12.5px; color: var(--muted); display: flex; flex-wrap: wrap; gap: 8px 18px; justify-content: space-between; }
        .foot a { color: var(--muted); }
        .pay-form { margin: 0; }
        @media (max-width: 560px) {
            .card { padding: 20px; }
            .pay-box { flex-direction: column; align-items: stretch; }
            .pay-box .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <div class="topbar">
            <div class="brand"><span class="dot" aria-hidden="true"></span> <span>Maverick Business Academy</span></div>
            <div class="secure">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                Secure payment
            </div>
        </div>

        @yield('content')

        <div class="foot">
            <span>© {{ date('Y') }} Maverick Business Academy</span>
            <span>
                <a href="mailto:{{ config('payments.page.support_email') }}">{{ config('payments.page.support_email') }}</a>
                @if (config('payments.page.support_phone')) · {{ config('payments.page.support_phone') }}@endif
            </span>
        </div>
    </main>
</body>
</html>
