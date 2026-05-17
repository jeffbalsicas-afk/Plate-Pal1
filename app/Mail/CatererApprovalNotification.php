<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CatererApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $caterer, public string $status, public ?string $reason = null) {}

    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved'
            ? 'Your PlatePal Profile Has Been Approved! 🎉'
            : 'Your PlatePal Profile Needs Updates';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.caterer-approval',
            with: [
                'caterer' => $this->caterer,
                'status' => $this->status,
                'reason' => $this->reason,
            ]
        );
    }
}
