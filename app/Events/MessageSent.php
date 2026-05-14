<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
        $this->message->loadMissing(['user', 'caterer']);
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("messages.{$this->message->user_id}.{$this->message->caterer_id}");
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        $author = $this->message->sender === 'caterer'
            ? ($this->message->caterer->business_name ?? $this->message->caterer->name ?? 'Caterer')
            : ($this->message->user->name ?? 'Client');

        return [
            'id' => $this->message->id,
            'user_id' => $this->message->user_id,
            'caterer_id' => $this->message->caterer_id,
            'sender' => $this->message->sender,
            'author' => $author,
            'body' => $this->message->body,
            'attachment' => $this->message->attachmentPayload(),
            'created_at' => $this->message->created_at?->toISOString(),
            'created_at_label' => $this->message->created_at?->format('g:i A'),
        ];
    }
}
