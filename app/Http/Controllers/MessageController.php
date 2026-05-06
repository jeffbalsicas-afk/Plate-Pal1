<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $conversations = Message::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('caterer_id', $user->id);
        })
        ->latest()
        ->get()
        ->groupBy(function ($message) use ($user) {
            return $user->role === 'caterer' 
                ? $message->user_id 
                : $message->caterer_id;
        });

        return view('messages.index', compact('conversations', 'user'));
    }

    public function show(User $recipient)
    {
        $user = auth()->user();
        
        $messages = Message::where(function ($query) use ($user, $recipient) {
            $query->where('user_id', $user->id)->where('caterer_id', $recipient->id)
                  ->orWhere('user_id', $recipient->id)->where('caterer_id', $user->id);
        })
        ->orderBy('created_at')
        ->get();

        Message::where('recipient_id', $user->id)
            ->where('sender_id', $recipient->id)
            ->update(['is_read' => true]);

        return view('messages.show', compact('messages', 'recipient', 'user'));
    }

    public function store(Request $request, User $recipient)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $caterer_id = $user->role === 'caterer' ? $user->id : $recipient->id;
        $user_id = $user->role === 'client' ? $user->id : $recipient->id;

        Message::create([
            'user_id' => $user_id,
            'caterer_id' => $caterer_id,
            'sender' => $user->role,
            'body' => $validated['body'],
            'is_read' => false,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    public function markAsRead(Message $message)
    {
        abort_unless(auth()->id() === $message->caterer_id || auth()->id() === $message->user_id, 403);
        
        $message->update(['is_read' => true]);
        
        return back();
    }
}
