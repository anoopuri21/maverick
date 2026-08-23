<?php

namespace App\Services;

use App\Mail\GenericFormMail;
use App\Settings\SiteSettings;
use App\Settings\ZohoSettings;
use Illuminate\Support\Facades\Mail;
use Throwable;

class FormMailer
{
    private const MAX_VALUE_LENGTH = 5000;

    private const SKIP_KEYS = [
        '_token',
        '_method',
        'website',
        'honeypot',
    ];

    /**
     * @param  array<string, mixed>  $fields
     * @param  array<string, mixed>  $options  to, reply_to, labels
     */
    public function send(array $fields, string $subject, array $options = []): bool
    {
        try {
            $rows = $this->normalizeRows($fields, $options['labels'] ?? []);

            if ($rows === []) {
                logger()->info('FormMailer skipped send: no non-empty fields.');

                return false;
            }

            $zoho = safe_settings(ZohoSettings::class);
            $site = safe_settings(SiteSettings::class);

            $recipient = $options['to']
                ?? (filled($zoho->default_recipient ?? null) ? $zoho->default_recipient : null)
                ?? (filled($site->email ?? null) ? $site->email : null)
                ?? 'admissions@mbalondon.org.uk';

            $replyTo = $options['reply_to']
                ?? (filled($zoho->reply_to ?? null) ? $zoho->reply_to : null)
                ?? $this->firstEmailValue($rows);

            $fromAddress = filled($zoho->username ?? null) ? $zoho->username : null;
            $fromName = filled($zoho->from_name ?? null) ? $zoho->from_name : null;

            $mailable = new GenericFormMail(
                $subject,
                $rows,
                $fromAddress,
                $fromName,
                $replyTo,
            );

            $mailer = ! empty($zoho->enabled) ? Mail::mailer('zoho') : Mail::mailer();
            $mailer->to($recipient)->send($mailable);

            return true;
        } catch (Throwable $e) {
            logger()->error('FormMailer failed: '.$e->getMessage(), [
                'subject' => $subject,
            ]);

            return false;
        }
    }

    /**
     * @param  array<string, mixed>  $fields
     * @param  array<string, string>  $labels
     * @return list<array{label: string, value: string}>
     */
    public function normalizeRows(array $fields, array $labels = []): array
    {
        $rows = [];

        foreach ($fields as $key => $value) {
            if (in_array((string) $key, self::SKIP_KEYS, true)) {
                continue;
            }

            $text = $this->sanitizeValue($value);

            if ($text === '') {
                continue;
            }

            $label = $labels[$key] ?? $this->humanizeKey((string) $key);
            $rows[] = [
                'label' => $label,
                'value' => $text,
            ];
        }

        return $rows;
    }

    private function sanitizeValue(mixed $value): string
    {
        if (is_array($value)) {
            $value = implode(', ', array_map(fn ($item) => $this->sanitizeValue($item), $value));
        }

        $text = trim(strip_tags((string) $value));
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        if (mb_strlen($text) > self::MAX_VALUE_LENGTH) {
            $text = mb_substr($text, 0, self::MAX_VALUE_LENGTH);
        }

        return $text;
    }

    private function humanizeKey(string $key): string
    {
        if ($key === '') {
            return 'Field';
        }

        if (str_contains($key, ' ')) {
            return $key;
        }

        return ucwords(str_replace(['_', '-'], ' ', $key));
    }

    /**
     * @param  list<array{label: string, value: string}>  $rows
     */
    private function firstEmailValue(array $rows): ?string
    {
        foreach ($rows as $row) {
            if (filter_var($row['value'], FILTER_VALIDATE_EMAIL)) {
                return $row['value'];
            }
        }

        return null;
    }
}
