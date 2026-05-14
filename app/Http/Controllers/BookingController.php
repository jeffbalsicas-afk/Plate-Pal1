<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmedNotification;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function accept(Booking $booking)
    {
        abort_unless($booking->caterer_id === auth()->id(), 403);
        abort_unless($booking->status === 'pending', 422, 'Only pending bookings can be accepted.');

        $booking->update(['status' => 'confirmed']);

        $booking->loadMissing(['user', 'caterer', 'package']);
        Mail::to($booking->user->email)->send(new BookingConfirmedNotification($booking));

        return back()->with('success', 'Booking confirmed! The client has been notified.');
    }

    public function decline(Booking $booking, Request $request)
    {
        abort_unless($booking->caterer_id === auth()->id(), 403);
        abort_unless($booking->status === 'pending', 422, 'Only pending bookings can be declined.');

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $booking->update([
            'status' => 'cancelled',
            'decline_reason' => $validated['reason'] ?? null,
        ]);

        return back()->with('success', 'Booking declined. The client has been notified.');
    }

    public function complete(Booking $booking)
    {
        abort_unless($booking->caterer_id === auth()->id(), 403);
        abort_unless($booking->status === 'confirmed', 422, 'Only confirmed bookings can be marked as complete.');

        $booking->update(['status' => 'completed']);

        return back()->with('success', 'Booking marked as complete! The client can now leave a review.');
    }

    public function edit(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless(in_array($booking->status, ['pending', 'confirmed']), 422, 'Only pending or confirmed bookings can be edited.');

        $booking->load('caterer');
        $packages = Package::where('caterer_id', $booking->caterer_id)
            ->where(function ($query) use ($booking) {
                $query->where('status', 'live')
                    ->orWhere('id', $booking->package_id);
            })
            ->orderBy('name')
            ->get();

        $user = auth()->user();
        $activeBookings = Booking::where('user_id', $user->id)->whereIn('status', ['pending', 'confirmed'])->count();
        $unreadMessages = \App\Models\Message::where('user_id', $user->id)->where('is_read', false)->where('sender', 'caterer')->count();
        $statusCounts = [
            'all' => Booking::where('user_id', $user->id)->count(),
        ];

        return view('client.booking-edit', compact('booking', 'packages', 'user', 'activeBookings', 'unreadMessages', 'statusCounts'));
    }

    public function update(Booking $booking, Request $request)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless(in_array($booking->status, ['pending', 'confirmed']), 422, 'Only pending or confirmed bookings can be edited.');

        $minGuests = $booking->caterer->min_guest ?? 1;
        $maxGuests = $booking->caterer->max_guest ?? 10000;

        $validated = $request->validate([
            'event_title' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'guests' => ['required', 'integer', 'min:' . $minGuests, 'max:' . $maxGuests],
            'package_id' => [
                'nullable',
                'integer',
                Rule::exists('packages', 'id')->where(fn ($query) => $query
                    ->where('caterer_id', $booking->caterer_id)
                    ->where(function ($query) use ($booking) {
                        $query->where('status', 'live')
                            ->orWhere('id', $booking->package_id);
                    })),
            ],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ]);

        $package = isset($validated['package_id'])
            ? Package::where('caterer_id', $booking->caterer_id)
                ->where(function ($query) use ($booking) {
                    $query->where('status', 'live')
                        ->orWhere('id', $booking->package_id);
                })
                ->find($validated['package_id'])
            : null;

        if ($package && (int) $validated['guests'] < (int) $package->min_guests) {
            return back()
                ->withErrors(['guests' => "This package requires at least {$package->min_guests} guests."])
                ->withInput();
        }

        $booking->update([
            'event_title' => $validated['event_title'],
            'event_date' => $validated['event_date'],
            'guests' => $validated['guests'],
            'package_id' => $package?->id,
            'package_name' => $package?->name,
            'package_price' => $package?->price,
            'special_requests' => $validated['special_requests'] ?? null,
        ]);

        return redirect()->route('client.bookings.show', $booking)->with('success', 'Booking updated successfully!');
    }
}
