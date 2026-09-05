<?php

namespace App\Mail;

use App\Settings\MailTemplateSettings;
use App\Settings\SiteSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  list<array{label: string, value: string}>  $rows
     */
    public function __construct(
        public string $emailSubject,
        public array $rows,
        public ?string $fromAddress = null,
        public ?string $fromName = null,
        public ?string $replyToAddress = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: filled($this->fromAddress)
                ? new Address($this->fromAddress, $this->fromName ?: '')
                : null,
            replyTo: filled($this->replyToAddress)
                ? [new Address($this->replyToAddress)]
                : [],
            subject: $this->emailSubject,
        );
    }

    public function content(): Content
    {
        $template = safe_settings(MailTemplateSettings::class);
        $site = safe_settings(SiteSettings::class);

        return new Content(
            view: 'emails.generic-form',
            text: 'emails.generic-form-text',
            with: [
                'heading' => $this->emailSubject,
                'rows' => $this->rows,
                'headerHtml' => $headerHtml = $this->cleanRichText((string) ($template->header_html ?? '')),
                'footerHtml' => $footerHtml = $this->cleanRichText((string) ($template->footer_html ?? '')),
                'headerText' => $this->htmlToText($headerHtml),
                'footerText' => $this->htmlToText($footerHtml),
                'logoUrl' => $this->absoluteUrl(
                    media_url($site->logo_white_url ?? null, 'assets/images/logo-white.png')
                ),
                'siteName' => 'Maverick Business Academy',
            ],
        );
    }

    /**
     * Trim helper — blank rich text (empty <p></p>) counts as empty.
     */
    private function cleanRichText(string $html): ?string
    {
        $text = trim(strip_tags($html));

        if ($text === '') {
            return null;
        }

        return trim($html);
    }

    /**
     * Emails need absolute URLs — relative asset paths resolved against app.url.
     */
    private function absoluteUrl(?string $url): ?string
    {
        if (! $url || ! str_starts_with($url, '/')) {
            return $url;
        }

        return rtrim((string) config('app.url'), '/').$url;
    }

    /**
     * Rich HTML → readable plain text for the text/plain part.
     */
    private function htmlToText(?string $html): ?string
    {
        if (! $html) {
            return null;
        }

        $text = preg_replace('#<(br|/p|/li|/h[1-6]|/tr)\s*/?>#i', "\n", $html) ?? $html;
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace("/\n{3,}/u", "\n\n", $text) ?? $text;

        return trim($text);
    }
}
