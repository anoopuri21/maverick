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
     * @param  array<string, mixed>  $options  to (email or email list), reply_to, labels
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

            $toRecipients = $this->normalizeRecipients($options['to'] ?? null);
            $toRecipients = $toRecipients !== []
                ? $toRecipients
                : $this->normalizeRecipients($zoho->default_recipient ?? null);
            $toRecipients = $toRecipients !== []
                ? $toRecipients
                : $this->normalizeRecipients($site->email ?? null);
            $toRecipients = $toRecipients !== []
                ? $toRecipients
                : ['fazil@mbalondon.org.uk'];

            $bccRecipients = $this->normalizeRecipients($zoho->additional_recipients ?? []);
            $toLookup = array_fill_keys(array_map('strtolower', $toRecipients), true);
            $bccRecipients = array_values(array_filter(
                $bccRecipients,
                fn (string $recipient): bool => ! isset($toLookup[strtolower($recipient)])
            ));

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
            $pendingMail = $mailer->to($toRecipients);

            if ($bccRecipients !== []) {
                $pendingMail->bcc($bccRecipients);
            }

            $pendingMail->send($mailable);

            return true;
        } catch (Throwable $e) {
            logger()->error('FormMailer failed: '.$e->getMessage(), [
                'subject' => $subject,
            ]);

            return false;
        }
    }

    /**
     * Accept email strings or Filament repeater rows, then filter and deduplicate them.
     *
     * @return list<string>
     */
    private function normalizeRecipients(mixed $recipients): array
    {
        if (is_string($recipients)) {
            $recipients = [$recipients];
        }

        if (! is_array($recipients)) {
            return [];
        }

        $validRecipients = [];

        foreach ($recipients as $recipient) {
            if (is_array($recipient)) {
                $recipient = $recipient['email'] ?? null;
            }

            if (! is_string($recipient)) {
                continue;
            }

            $email = trim($recipient);
            if (
                $email === ''
                || strlen($email) > 254
                || filter_var($email, FILTER_VALIDATE_EMAIL) === false
            ) {
                continue;
            }

            $validRecipients[strtolower($email)] = $email;
        }

        return array_values($validRecipients);
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
