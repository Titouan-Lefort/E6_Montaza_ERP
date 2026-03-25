<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Models\DevisTuyauterie;

class DevisEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public DevisTuyauterie $devis,
        public string $customSubject,
        public ?string $customMessage,
        public string $pdfPath,
        public string $senderEmail,
        public string $senderName
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->senderEmail, $this->senderName),
            subject: $this->customSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.devis',
            with: [
                'devis' => $this->devis,
                'customMessage' => $this->customMessage,
                'senderEmail' => $this->senderEmail,
                'senderName' => $this->senderName,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('Devis_' . ($this->devis->reference_projet ?? $this->devis->id) . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
