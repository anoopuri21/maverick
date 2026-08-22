<?php

namespace App\Providers;

use App\Settings\ZohoSettings;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class ZohoMailServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Mail::extend('zoho', function () {
            $settings = safe_settings(ZohoSettings::class);
            $host = filled($settings->smtp_host ?? null) ? $settings->smtp_host : 'smtp.zoho.com';
            $port = (int) ($settings->port ?: 587);
            $encryption = strtolower((string) ($settings->encryption ?: 'tls'));
            $implicitTls = $encryption === 'ssl';

            $transport = new EsmtpTransport($host, $port, $implicitTls ?: null);

            if (filled($settings->username ?? null)) {
                $transport->setUsername($settings->username);
            }

            if (filled($settings->password ?? null)) {
                $transport->setPassword($settings->password);
            }

            return $transport;
        });
    }
}
