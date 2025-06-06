<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Seminaire;

class SeminaireValideMail extends Mailable
{
    use Queueable, SerializesModels;

    public $seminaire; // Déclarez la propriété publique pour la rendre disponible dans la vue

    /**
     * Create a new message instance.
     */
    public function __construct(Seminaire $seminaire)
    {
        $this->seminaire = $seminaire;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre demande de séminaire a été validée !',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.seminaires.valide', 
            with: [
                'seminaire' => $this->seminaire,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
