<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $actionLink;

    public function __construct($user, $actionLink)
    {
        $this->user = $user;
        $this->actionLink = $actionLink;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('auth.reset_email_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email-templates.forgot-template',
        );
    }
}