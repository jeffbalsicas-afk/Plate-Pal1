<?php

namespace Tests\Feature;

use App\Mail\PasswordChangedNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatererProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_caterer_can_update_profile_fields_and_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('caterers/old.jpg', 'old-photo');

        $caterer = User::factory()->create([
            'role' => 'caterer',
            'phone' => '09170000000',
            'business_name' => 'Old Kitchen',
            'profile_image' => '/storage/caterers/old.jpg',
            'approval_status' => 'approved',
            'is_verified' => true,
            'rejection_reason' => 'Old reason',
        ]);

        $response = $this->actingAs($caterer)->post(route('caterer.profile.update'), [
            'business_name' => 'Tagum Feast Co.',
            'barangay' => 'Apokon',
            'phone' => '+63 917 123 4567',
            'cuisine' => 'Filipino BBQ',
            'description' => 'Family-style catering for events.',
            'price_min' => '250',
            'price_max' => '650',
            'min_guest' => '20',
            'max_guest' => '150',
            'profile_image' => UploadedFile::fake()->image('business.jpg', 800, 600)->size(600),
        ]);

        $response->assertRedirect(route('caterer.profile'));
        $response->assertSessionHas('success');

        $caterer->refresh();

        $this->assertSame('Tagum Feast Co.', $caterer->business_name);
        $this->assertSame('Apokon', $caterer->barangay);
        $this->assertSame('+639171234567', $caterer->phone);
        $this->assertSame('Filipino BBQ', $caterer->cuisine);
        $this->assertSame('Family-style catering for events.', $caterer->description);
        $this->assertSame(250.0, (float) $caterer->price_min);
        $this->assertSame(650.0, (float) $caterer->price_max);
        $this->assertSame(20, (int) $caterer->min_guest);
        $this->assertSame(150, (int) $caterer->max_guest);
        $this->assertSame('pending', $caterer->approval_status);
        $this->assertFalse((bool) $caterer->is_verified);
        $this->assertNull($caterer->rejection_reason);
        $this->assertStringStartsWith('/storage/caterers/', $caterer->profile_image);

        Storage::disk('public')->assertExists(str_replace('/storage/', '', $caterer->profile_image));
        Storage::disk('public')->assertMissing('caterers/old.jpg');
    }

    public function test_caterer_profile_rejects_duplicate_phone_numbers(): void
    {
        User::factory()->create([
            'role' => 'client',
            'phone' => '09171234567',
        ]);

        $caterer = User::factory()->create([
            'role' => 'caterer',
            'phone' => '09170000000',
        ]);

        $response = $this->actingAs($caterer)
            ->from(route('caterer.profile'))
            ->post(route('caterer.profile.update'), $this->validPayload([
                'phone' => '0917 123 4567',
            ]));

        $response->assertRedirect(route('caterer.profile'));
        $response->assertSessionHasErrors('phone');

        $this->assertSame('09170000000', $caterer->refresh()->phone);
    }

    public function test_caterer_profile_requires_at_least_11_phone_digits(): void
    {
        $caterer = User::factory()->create([
            'role' => 'caterer',
            'phone' => '09170000000',
        ]);

        $response = $this->actingAs($caterer)
            ->from(route('caterer.profile'))
            ->post(route('caterer.profile.update'), $this->validPayload([
                'phone' => '0917 123',
            ]));

        $response->assertRedirect(route('caterer.profile'));
        $response->assertSessionHasErrors('phone');

        $this->assertSame('09170000000', $caterer->refresh()->phone);
    }

    public function test_caterer_profile_rejects_invalid_price_and_guest_ranges(): void
    {
        $caterer = User::factory()->create(['role' => 'caterer']);

        $response = $this->actingAs($caterer)
            ->from(route('caterer.profile'))
            ->post(route('caterer.profile.update'), $this->validPayload([
                'price_min' => '700',
                'price_max' => '250',
                'min_guest' => '80',
                'max_guest' => '20',
            ]));

        $response->assertRedirect(route('caterer.profile'));
        $response->assertSessionHasErrors(['price_max', 'max_guest']);
    }

    public function test_about_update_submits_profile_for_admin_approval(): void
    {
        $caterer = User::factory()->create([
            'role' => 'caterer',
            'approval_status' => 'approved',
            'is_verified' => true,
            'rejection_reason' => 'Old reason',
        ]);

        $response = $this->actingAs($caterer)->post(route('caterer.profile.update'), [
            'form_type' => 'about',
            'our_story' => 'We cook family recipes for celebrations.',
            'what_makes_special' => "Fresh ingredients\nFriendly service",
            'services_offered' => ['Weddings & Receptions', 'Fiestas'],
        ]);

        $response->assertRedirect(route('caterer.profile').'#about');
        $response->assertSessionHas('success', 'About section submitted for admin approval.');

        $caterer->refresh();

        $this->assertSame('We cook family recipes for celebrations.', $caterer->our_story);
        $this->assertSame("Fresh ingredients\nFriendly service", $caterer->what_makes_special);
        $this->assertSame(['Weddings & Receptions', 'Fiestas'], $caterer->services_offered);
        $this->assertSubmittedForReview($caterer);
    }

    public function test_gallery_upload_submits_profile_for_admin_approval(): void
    {
        Storage::fake('public');

        $caterer = User::factory()->create([
            'role' => 'caterer',
            'gallery_images' => ['/storage/gallery/old.jpg'],
            'approval_status' => 'approved',
            'is_verified' => true,
            'rejection_reason' => 'Old reason',
        ]);

        $response = $this->actingAs($caterer)->post(route('caterer.profile.update'), [
            'form_type' => 'gallery',
            'gallery_images' => [
                UploadedFile::fake()->image('event-setup.jpg', 800, 600)->size(600),
            ],
        ]);

        $response->assertRedirect(route('caterer.profile').'#gallery');
        $response->assertSessionHas('success', 'Gallery changes submitted for admin approval.');

        $caterer->refresh();

        $this->assertCount(2, $caterer->gallery_images);
        $this->assertSame('/storage/gallery/old.jpg', $caterer->gallery_images[0]);
        $this->assertStringStartsWith('/storage/gallery/', $caterer->gallery_images[1]);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $caterer->gallery_images[1]));
        $this->assertSubmittedForReview($caterer);
    }

    public function test_gallery_delete_submits_profile_for_admin_approval(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/delete-me.jpg', 'old-photo');

        $caterer = User::factory()->create([
            'role' => 'caterer',
            'gallery_images' => ['/storage/gallery/delete-me.jpg', '/storage/gallery/keep-me.jpg'],
            'approval_status' => 'approved',
            'is_verified' => true,
            'rejection_reason' => 'Old reason',
        ]);

        $response = $this->actingAs($caterer)->delete(route('caterer.profile.gallery.delete', 0));

        $response->assertRedirect(route('caterer.profile').'#gallery');
        $response->assertSessionHas('success', 'Gallery changes submitted for admin approval.');

        $caterer->refresh();

        $this->assertSame(['/storage/gallery/keep-me.jpg'], $caterer->gallery_images);
        Storage::disk('public')->assertMissing('gallery/delete-me.jpg');
        $this->assertSubmittedForReview($caterer);
    }

    public function test_caterer_can_change_password_without_profile_approval_review(): void
    {
        Mail::fake();

        $caterer = User::factory()->create([
            'role' => 'caterer',
            'approval_status' => 'approved',
            'is_verified' => true,
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($caterer)->post(route('caterer.profile.update'), [
            'form_type' => 'password',
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('caterer.profile').'#security');
        $response->assertSessionHas('success', 'Password changed successfully.');

        $caterer->refresh();

        $this->assertTrue(Hash::check('new-password', $caterer->password));
        $this->assertSame('approved', $caterer->approval_status);
        $this->assertTrue((bool) $caterer->is_verified);
        Mail::assertSent(PasswordChangedNotification::class, fn (PasswordChangedNotification $mail) => $mail->hasTo($caterer->email));
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'business_name' => 'Tagum Feast Co.',
            'barangay' => 'Apokon',
            'phone' => '09171234567',
            'cuisine' => 'Filipino BBQ',
            'description' => 'Family-style catering for events.',
            'price_min' => '250',
            'price_max' => '650',
            'min_guest' => '20',
            'max_guest' => '150',
        ], $overrides);
    }

    private function assertSubmittedForReview(User $caterer): void
    {
        $this->assertSame('pending', $caterer->approval_status);
        $this->assertFalse((bool) $caterer->is_verified);
        $this->assertNull($caterer->rejection_reason);
    }
}
