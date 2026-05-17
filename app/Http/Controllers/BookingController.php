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

        $booking->update([
            'status' => 'confirmed',
            'client_viewed_at' => null, // Reset so client gets notified
        ]);

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
            'client_viewed_at' => null, // Reset so client gets notified
        ]);

        return back()->with('success', 'Booking declined. The client has been notified.');
    }

    public function complete(Booking $booking)
    {
        abort_unless($booking->caterer_id === auth()->id(), 403);
        abort_unless($booking->status === 'confirmed', 422, 'Only confirmed bookings can be marked as complete.');

        // If no final_price is set, try to calculate from package or items
        if (!$booking->final_price) {
            // First try package price
            if ($booking->package_price) {
                $booking->final_price = $booking->package_price;
            }
            // Otherwise calculate from booking items (menu items + add-ons)
            elseif ($booking->bookingItems()->exists()) {
                $itemsTotal = $booking->bookingItems->sum(function($item) {
                    return ($item->item_price ?? 0) * ($item->quantity ?? 1);
                });
                if ($itemsTotal > 0) {
                    $booking->final_price = $itemsTotal;
                }
            }
        }

        // Caterer must set final price before completing (or have package/items price)
        if (!$booking->final_price) {
            return back()->withErrors(['final_price' => 'Please set the final agreed price before marking as complete.']);
        }

        $booking->update([
            'status' => 'completed',
            'final_price' => $booking->final_price, // Save it if it was just calculated
            'client_viewed_at' => null, // Reset so client gets notified
        ]);

        return back()->with('success', 'Booking marked as complete! The client can now leave a review.');
    }

    public function setFinalPrice(Booking $booking, Request $request)
    {
        abort_unless($booking->caterer_id === auth()->id(), 403);
        abort_unless($booking->status === 'confirmed', 422, 'Only confirmed bookings can have final price set.');

        $validated = $request->validate([
            'final_price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
        ]);

        $booking->update([
            'final_price' => $validated['final_price'],
        ]);

        return back()->with('success', 'Final price set successfully!');
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
        $activeBookings = Booking::where('user_id', $user->id)->whereNull('client_viewed_at')->count();
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

        $validated = $request->validate([
            'event_title' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'guests' => ['required', 'integer', 'min:1', 'max:10000'],
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

        // Package min_guests is now just a guideline - caterer can accept any booking

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

    public function cancel(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless(in_array($booking->status, ['pending', 'confirmed']), 422, 'Only pending or confirmed bookings can be cancelled.');

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('client.bookings')->with('success', 'Booking cancelled successfully.');
    }
}
