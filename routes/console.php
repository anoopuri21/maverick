<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
| Shared hosting cron (cPanel → Cron Jobs):
|   * * * * * cd /home/USER/maverick-academy && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
|
| No long-running queue:work daemon. If QUEUE_CONNECTION=database, drain one batch
| per minute with --stop-when-empty so the process always exits.
*/
if (config('queue.default') === 'database') {
    Schedule::command('queue:work --stop-when-empty --max-time=50 --tries=3 --sleep=1')
        ->everyMinute()
        ->withoutOverlapping(5);
}
