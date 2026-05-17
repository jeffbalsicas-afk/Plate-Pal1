<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmedNotification;
use App\Mail\NewBookingNotification;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\MenuItem;
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

        $response->assertRedirect(route('caterer.detail', $caterer));
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

    public function test_package_minimum_guests_is_a_guideline_for_booking_requests(): void
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
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('bookings', [
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'package_id' => $package->id,
            'guests' => 40,
        ]);
    }

    public function test_custom_booking_budget_is_included_when_completed_with_items(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $caterer = $this->approvedCaterer();

        $booking = Booking::create([
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'event_title' => 'Custom Birthday',
            'event_date' => now()->addDays(3)->toDateString(),
            'guests' => 50,
            'client_budget' => 22000,
            'status' => 'confirmed',
        ]);

        $riceTray = MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'Rice Tray',
            'price' => 250,
            'type' => 'addon',
            'category' => 'main',
        ]);

        $caseOfCoke = MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'Case of Coke',
            'price' => 500,
            'type' => 'addon',
            'category' => 'beverage',
        ]);

        BookingItem::create([
            'booking_id' => $booking->id,
            'menu_item_id' => $riceTray->id,
            'item_name' => 'Rice Tray',
            'item_type' => 'addon',
            'item_price' => 250,
            'quantity' => 1,
        ]);

        BookingItem::create([
            'booking_id' => $booking->id,
            'menu_item_id' => $caseOfCoke->id,
            'item_name' => 'Case of Coke',
            'item_type' => 'addon',
            'item_price' => 500,
            'quantity' => 1,
        ]);

        $this->actingAs($caterer)
            ->post(route('bookings.complete', $booking))
            ->assertSessionHas('success');

        $booking->refresh()->load('bookingItems');

        $this->assertSame('completed', $booking->status);
        $this->assertSame(22750.0, (float) $booking->final_price);
        $this->assertSame(22750.0, $booking->estimated_total);

        $booking->forceFill(['final_price' => 750])->save();

        $this->assertSame(22750.0, $booking->refresh()->load('bookingItems')->estimated_total);
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
