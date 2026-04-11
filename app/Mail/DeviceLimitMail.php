<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeviceLimitMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $code, public $browser, public $platform, public $device, public $ip)
    {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Login Attempt',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.device-limit',
            with: [
                'code' => $this->code,
                'browser' => $this->browser,
                'platform' => $this->platform,
                'device' => $this->device,
                'ip' => $this->ip

            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
