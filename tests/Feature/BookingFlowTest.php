<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmedNotification;
use App\Mail\NewBookingNotification;
use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_request_package_booking_and_caterer_can_complete_it(): void
    {
        Mail::fake();

        $client = User::factory()->create([
            'role' => 'client',
            'email' => 'client@example.com',
        ]);

        $caterer = $this->approvedCaterer();

        $package = Package::create([
            'caterer_id' => $caterer->id,
            'name' => 'Fiesta Package',
            'description' => 'Full buffet setup',
            'price' => 450,
            'min_guests' => 30,
            'status' => 'live',
            'category' => 'bundled',
        ]);

        $response = $this->actingAs($client)->post(route('client.bookings.store', $caterer), [
            'event_title' => 'Birthday Dinner',
            'event_date' => now()->addDays(10)->toDateString(),
            'guests' => 35,
            'package_id' => $package->id,
            'special_requests' => 'Please include one vegetarian tray.',
        ]);

        $booking = Booking::first();

        $response->assertRedirect(route('client.bookings.show', $booking));
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'package_id' => $package->id,
            'package_name' => 'Fiesta Package',
            'guests' => 35,
            'special_requests' => 'Please include one vegetarian tray.',
            'status' => 'pending',
        ]);
        $this->assertSame(450.0, (float) $booking->package_price);
        $this->assertSame(450.0, $booking->estimated_total);

        Mail::assertSent(NewBookingNotification::class, fn (NewBookingNotification $mail) => $mail->hasTo($caterer->email));

        $this->actingAs($caterer)
            ->post(route('bookings.accept', $booking))
            ->assertSessionHas('success');

        $this->assertSame('confirmed', $booking->refresh()->status);
        Mail::assertSent(BookingConfirmedNotification::class, fn (BookingConfirmedNotification $mail) => $mail->hasTo($client->email));

        $this->actingAs($caterer)
            ->post(route('bookings.complete', $booking))
            ->assertSessionHas('success');

        $this->assertSame('completed', $booking->refresh()->status);
    }

    public function test_booking_package_must_match_caterer_and_minimum_guests(): void
    {
        Mail::fake();

        $client = User::factory()->create(['role' => 'client']);
        $caterer = $this->approvedCaterer();

        $package = Package::create([
            'caterer_id' => $caterer->id,
            'name' => 'Wedding Package',
            'description' => null,
            'price' => 650,
            'min_guests' => 80,
            'status' => 'live',
            'category' => 'bundled',
        ]);

        $response = $this->actingAs($client)
            ->from(route('caterer.detail', $caterer))
            ->post(route('client.bookings.store', $caterer), [
                'event_title' => 'Small Dinner',
                'event_date' => now()->addDays(5)->toDateString(),
                'guests' => 40,
                'package_id' => $package->id,
            ]);

        $response->assertRedirect(route('caterer.detail', $caterer));
        $response->assertSessionHasErrors('guests');
        $this->assertDatabaseCount('bookings', 0);
    }

    private function approvedCaterer(): User
    {
        return User::factory()->create([
            'role' => 'caterer',
            'email' => fake()->unique()->safeEmail(),
            'business_name' => 'Tagum Feast Co.',
            'barangay' => 'Apokon',
            'phone' => '09171234567',
            'cuisine' => 'Filipino',
            'price_min' => 300,
            'price_max' => 700,
            'min_guest' => 20,
            'max_guest' => 200,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
    }
}
