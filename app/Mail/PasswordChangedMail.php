<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Define o Assunto do E-mail (Subject)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('auth.password_changed_email_subject'),
        );
    }

    /**
     * Define a View do E-mail
     */
    public function content(): Content
    {
        return new Content(
            view: 'email-templates.password-changes-template',
        );
    }
}