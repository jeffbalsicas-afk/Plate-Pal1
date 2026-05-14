<?php

namespace App\Http\Controllers;

use App\Models\SystemFeedback;
use Illuminate\Http\Request;

class SystemFeedbackController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $displayName = $user->business_name ?? $user->name;
        $initials = $user->initials;
        $dashboardRoute = match ($user->role) {
            'caterer' => route('caterer.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('client.dashboard'),
        };

        return response()
            ->view('feedback.create', compact('user', 'displayName', 'initials', 'dashboardRoute'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:bug,suggestion,experience,other'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'max:2000'],
            'page_url' => ['nullable', 'string', 'max:500'],
        ]);

        SystemFeedback::create([
            'user_id' => $user->id,
            'role' => $user->role,
            'type' => $validated['type'],
            'rating' => $validated['rating'] ?? null,
            'message' => $validated['message'],
            'page_url' => $validated['page_url'] ?? null,
            'user_agent' => $request->userAgent(),
            'status' => 'new',
        ]);

        return back()->with('success', 'Thanks for the feedback. Your note was sent to the PlatePal team.');
    }
}
