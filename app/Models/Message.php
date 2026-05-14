<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'caterer_id',
        'body',
        'is_read',
        'sender',
        'attachment_path',
        'attachment_original_name',
        'attachment_mime',
        'attachment_size',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }

    public function hasAttachment(): bool
    {
        return filled($this->attachment_path);
    }

    public function isAttachmentImage(): bool
    {
        return str_starts_with((string) $this->attachment_mime, 'image/');
    }

    public function attachmentName(): ?string
    {
        if (! $this->hasAttachment()) {
            return null;
        }

        return $this->attachment_original_name ?: basename($this->attachment_path);
    }

    public function formattedAttachmentSize(): ?string
    {
        if ($this->attachment_size === null) {
            return null;
        }

        if ($this->attachment_size < 1024) {
            return $this->attachment_size.' B';
        }

        if ($this->attachment_size < 1024 * 1024) {
            return round($this->attachment_size / 1024, 1).' KB';
        }

        return round($this->attachment_size / (1024 * 1024), 1).' MB';
    }

    public function previewText(): string
    {
        if (filled($this->body)) {
            return $this->body;
        }

        if (! $this->hasAttachment()) {
            return '';
        }

        return $this->isAttachmentImage() ? 'Sent an image' : 'Sent a file';
    }

    public function attachmentPayload(): ?array
    {
        if (! $this->hasAttachment()) {
            return null;
        }

        return [
            'url' => route('messages.attachment', $this),
            'name' => $this->attachmentName(),
            'mime' => $this->attachment_mime,
            'size' => $this->attachment_size,
            'size_label' => $this->formattedAttachmentSize(),
            'is_image' => $this->isAttachmentImage(),
        ];
    }

    protected static function booted(): void
    {
        static::deleted(function (Message $message) {
            if ($message->attachment_path) {
                Storage::disk('public')->delete($message->attachment_path);
            }
        });
    }
}
