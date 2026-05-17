<?php

namespace Tests\Feature;

use App\Mail\PasswordChangedNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ClientProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_change_password_from_profile_settings(): void
    {
        Mail::fake();

        $client = User::factory()->create([
            'role' => 'client',
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($client)->post(route('client.profile.update'), [
            'form_type' => 'password',
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Password changed successfully!');

        $this->assertTrue(Hash::check('new-password', $client->refresh()->password));
        Mail::assertSent(PasswordChangedNotification::class, fn (PasswordChangedNotification $mail) => $mail->hasTo($client->email));
    }

    public function test_client_password_change_requires_current_password(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($client)
            ->from(route('client.profile'))
            ->post(route('client.profile.update'), [
                'form_type' => 'password',
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertRedirect(route('client.profile'));
        $response->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('old-password', $client->refresh()->password));
    }
}
