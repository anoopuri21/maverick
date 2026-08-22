<?php

namespace App\Mail;

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
        return new Content(
            markdown: 'emails.generic-form',
            text: 'emails.generic-form-text',
            with: [
                'heading' => $this->emailSubject,
                'rows' => $this->rows,
            ],
        );
    }
}
