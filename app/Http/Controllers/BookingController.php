<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmedNotification;
use App\Mail\NewBookingNotification;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function accept(Booking $booking)
    {
        abort_unless($booking->caterer_id === auth()->id(), 403);
        abort_unless($booking->status === 'pending', 422, 'Only pending bookings can be accepted.');

        $booking->update(['status' => 'confirmed']);

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

        return view('client.booking-edit', ['booking' => $booking]);
    }

    public function update(Booking $booking, Request $request)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless(in_array($booking->status, ['pending', 'confirmed']), 422, 'Only pending or confirmed bookings can be edited.');

        $minGuests = $booking->caterer->min_guest ?? 1;
        $maxGuests = $booking->caterer->max_guest ?? 10000;

        $validated = $request->validate([
            'event_title' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after:today'],
            'guests' => ['required', 'integer', 'min:' . $minGuests, 'max:' . $maxGuests],
        ]);

        $booking->update($validated);

        return redirect()->route('client.bookings.show', $booking)->with('success', 'Booking updated successfully!');
    }
}
