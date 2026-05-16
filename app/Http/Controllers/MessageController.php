<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Booking;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Mark all unviewed bookings as viewed for clients when they access messages
        if ($user->role === 'client') {
            Booking::where('user_id', $user->id)
                ->whereNull('client_viewed_at')
                ->update(['client_viewed_at' => now()]);
        }
        
        // Recalculate stats AFTER marking as viewed
        [
            'activeBookings' => $activeBookings,
            'unreadMessages' => $unreadMessages,
            'pendingBookings' => $pendingBookings,
        ] = $this->messagePageStats($user);

        $statusCounts = ['all' => 0];
        if ($user->role === 'client') {
            $statusCounts = ['all' => Booking::where('user_id', $user->id)->count()];
        }

        $conversations = Message::with(['user', 'caterer'])
            ->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('caterer_id', $user->id);
        })
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->get()
        ->groupBy(function ($message) use ($user) {
            return $user->role === 'caterer' 
                ? $message->user_id 
                : $message->caterer_id;
        })
        ->map(function ($messages) use ($user) {
            $lastMessage = $messages->first();

            return [
                'recipient' => $user->role === 'caterer' ? $lastMessage->user : $lastMessage->caterer,
                'lastMessage' => $lastMessage,
                'unread' => $messages->where('is_read', false)->where('sender', '!=', $user->role)->count(),
            ];
        })
        ->filter(fn ($conversation) => $conversation['recipient'] !== null)
        ->sortByDesc(fn ($conversation) => $conversation['lastMessage']->created_at)
        ->values();

        $existingRecipientIds = $conversations
            ->map(fn ($conversation) => $conversation['recipient']->id)
            ->all();

        $contactSuggestions = $this->contactSuggestions($user, $existingRecipientIds);

        return view('messages.index', compact('conversations', 'contactSuggestions', 'user', 'activeBookings', 'unreadMessages', 'pendingBookings', 'statusCounts'));
    }

    public function show(User $recipient)
    {
        $user = auth()->user();
        [$clientId, $catererId] = $this->conversationPair($user, $recipient);
        
        // Mark all unviewed bookings as viewed for clients when they access messages
        if ($user->role === 'client') {
            Booking::where('user_id', $user->id)
                ->whereNull('client_viewed_at')
                ->update(['client_viewed_at' => now()]);
        }
        
        $messages = Message::with(['user', 'caterer'])
        ->where('user_id', $clientId)
        ->where('caterer_id', $catererId)
        ->orderBy('created_at')
        ->orderBy('id')
        ->get();

        Message::where('user_id', $clientId)
            ->where('caterer_id', $catererId)
            ->where('sender', '!=', $user->role)
            ->update(['is_read' => true]);

        // Recalculate stats AFTER marking as viewed
        [
            'activeBookings' => $activeBookings,
            'unreadMessages' => $unreadMessages,
            'pendingBookings' => $pendingBookings,
        ] = $this->messagePageStats($user);

        $statusCounts = ['all' => 0];
        if ($user->role === 'client') {
            $statusCounts = ['all' => Booking::where('user_id', $user->id)->count()];
        }

        return view('messages.show', compact('messages', 'recipient', 'user', 'clientId', 'catererId', 'activeBookings', 'unreadMessages', 'pendingBookings', 'statusCounts'));
    }

    public function store(Request $request, User $recipient)
    {
        $user = auth()->user();
        [$clientId, $catererId] = $this->conversationPair($user, $recipient);

        $validated = $request->validate([
            'body' => ['nullable', 'required_without:attachment', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv'],
        ]);

        $attachment = $request->file('attachment');
        $attachmentData = [];

        if ($attachment) {
            $path = $attachment->store("message-attachments/{$clientId}-{$catererId}", 'public');

            abort_unless($path, 500, 'Unable to upload attachment.');

            $attachmentData = [
                'attachment_path' => $path,
                'attachment_original_name' => $attachment->getClientOriginalName(),
                'attachment_mime' => $attachment->getMimeType(),
                'attachment_size' => $attachment->getSize(),
            ];
        }

        $message = Message::create([
            'user_id' => $clientId,
            'caterer_id' => $catererId,
            'sender' => $user->role,
            'body' => trim((string) ($validated['body'] ?? '')),
            'is_read' => false,
        ] + $attachmentData);

        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (Throwable $exception) {
            report($exception);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->messagePayload($message),
            ], 201);
        }

        return back()->with('success', 'Message sent successfully.');
    }

    public function markAsRead(Message $message)
    {
        abort_unless(auth()->id() === $message->caterer_id || auth()->id() === $message->user_id, 403);
        
        $message->update(['is_read' => true]);
        
        return back();
    }

    private function conversationPair(User $user, User $recipient): array
    {
        abort_unless(
            in_array($user->role, ['client', 'caterer'], true)
            && in_array($recipient->role, ['client', 'caterer'], true)
            && $user->role !== $recipient->role,
            403
        );

        return $user->role === 'client'
            ? [$user->id, $recipient->id]
            : [$recipient->id, $user->id];
    }

    private function contactSuggestions(User $user, array $excludeIds)
    {
        if ($user->role === 'client') {
            return User::where('role', 'caterer')
                ->where('approval_status', 'approved')
                ->where('is_active', true)
                ->whereNotIn('id', $excludeIds)
                ->orderBy('business_name')
                ->orderBy('name')
                ->take(8)
                ->get();
        }

        return User::where('role', 'client')
            ->whereHas('bookings', fn ($query) => $query->where('caterer_id', $user->id))
            ->whereNotIn('id', $excludeIds)
            ->orderBy('name')
            ->take(8)
            ->get();
    }

    private function messagePageStats(User $user): array
    {
        if ($user->role === 'client') {
            return [
                'activeBookings' => Booking::where('user_id', $user->id)
                    ->whereNull('client_viewed_at')
                    ->count(),
                'unreadMessages' => Message::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->where('sender', 'caterer')
                    ->count(),
                'pendingBookings' => 0,
            ];
        }

        return [
            'activeBookings' => 0,
            'unreadMessages' => Message::where('caterer_id', $user->id)
                ->where('is_read', false)
                ->where('sender', 'client')
                ->count(),
            'pendingBookings' => Booking::where('caterer_id', $user->id)
                ->where('status', 'pending')
                ->count(),
        ];
    }

    public function latest(User $recipient)
    {
        $user = auth()->user();
        [$clientId, $catererId] = $this->conversationPair($user, $recipient);

        $lastMessageId = request('after');

        $messages = Message::where('user_id', $clientId)
            ->where('caterer_id', $catererId)
            ->when($lastMessageId, fn($q) => $q->where('id', '>', $lastMessageId))
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->map(fn($message) => $this->messagePayload($message));

        Message::where('user_id', $clientId)
            ->where('caterer_id', $catererId)
            ->where('sender', '!=', $user->role)
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }

    public function attachment(Message $message)
    {
        abort_unless(auth()->id() === $message->user_id || auth()->id() === $message->caterer_id, 403);
        abort_unless($message->attachment_path && Storage::disk('public')->exists($message->attachment_path), 404);

        $headers = [];

        if ($message->attachment_mime) {
            $headers['Content-Type'] = $message->attachment_mime;
        }

        if ($message->isAttachmentImage()) {
            return response()->file(Storage::disk('public')->path($message->attachment_path), $headers);
        }

        return Storage::disk('public')->download(
            $message->attachment_path,
            $message->attachmentName(),
            $headers
        );
    }

    private function messagePayload(Message $message): array
    {
        return [
            'id' => $message->id,
            'user_id' => $message->user_id,
            'caterer_id' => $message->caterer_id,
            'sender' => $message->sender,
            'body' => $message->body,
            'attachment' => $message->attachmentPayload(),
            'created_at' => $message->created_at?->toISOString(),
            'created_at_label' => $message->created_at?->format('g:i A'),
        ];
    }

    public function destroy(Message $message)
    {
        abort_unless(auth()->id() === $message->user_id || auth()->id() === $message->caterer_id, 403);
        abort_unless($message->sender === auth()->user()->role, 403);

        if ($message->attachment_path) {
            Storage::disk('public')->delete($message->attachment_path);
        }

        $message->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Message deleted successfully.');
    }
}
