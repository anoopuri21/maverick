<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Form Submission from ' . ($this->data['name'] ?? 'Guest'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-form',
            with: [
                'name' => $this->data['name'] ?? '',
                'email' => $this->data['email'] ?? '',
                'phone' => $this->data['phone'] ?? 'N/A',
                'subjectLine' => $this->data['subject'] ?? 'General Inquiry',
                'bodyMessage' => $this->data['message'] ?? '',
            ]
        );
    }
}
