<?php

namespace Tests\Feature;

use App\Mail\PasswordChangedNotification;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class RoleLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_pages_are_available_for_client_and_caterer(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Welcome Back, Client');

        $this->get('/caterer/login')
            ->assertOk()
            ->assertSee('Welcome Back, Caterer');
    }

    public function test_client_login_only_accepts_client_accounts(): void
    {
        $client = User::factory()->create([
            'email' => 'client@example.com',
            'role' => 'client',
        ]);

        $this->post('/login', [
            'email' => 'client@example.com',
            'password' => 'password',
        ])->assertRedirect(route('client.dashboard'));

        $this->assertAuthenticatedAs($client);
    }

    public function test_client_login_rejects_caterer_accounts(): void
    {
        $caterer = User::factory()->create([
            'email' => 'caterer@example.com',
            'role' => 'caterer',
        ]);

        $this->post('/login', [
            'email' => $caterer->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_caterer_login_only_accepts_caterer_accounts(): void
    {
        $caterer = User::factory()->create([
            'email' => 'caterer@example.com',
            'role' => 'caterer',
        ]);

        $this->post('/caterer/login', [
            'email' => 'caterer@example.com',
            'password' => 'password',
        ])->assertRedirect(route('caterer.dashboard'));

        $this->assertAuthenticatedAs($caterer);
    }

    public function test_caterer_login_rejects_client_accounts(): void
    {
        $client = User::factory()->create([
            'email' => 'client@example.com',
            'role' => 'client',
        ]);

        $this->post('/caterer/login', [
            'email' => $client->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_still_use_the_regular_login_page(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_guests_are_sent_to_the_matching_login_page_for_protected_areas(): void
    {
        $this->get('/dashboard')
            ->assertRedirect(route('login'));

        $this->get('/caterer/dashboard')
            ->assertRedirect(route('caterer.login'));

        $this->get('/admin/dashboard')
            ->assertRedirect(route('login'));
    }

    public function test_password_reset_request_sends_reset_link_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'client@example.com',
            'role' => 'client',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'CLIENT@example.com',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_password_reset_sends_password_changed_email(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'client@example.com',
            'role' => 'client',
            'password' => Hash::make('old-password'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHas('status');

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
        Mail::assertSent(PasswordChangedNotification::class, fn (PasswordChangedNotification $mail) => $mail->hasTo($user->email));
    }

    public function test_authenticated_users_cannot_cross_into_another_role_dashboard(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $this->actingAs($client)
            ->get('/caterer/dashboard')
            ->assertRedirect(route('client.dashboard'));

        $caterer = User::factory()->create(['role' => 'caterer']);
        $this->actingAs($caterer)
            ->get('/dashboard')
            ->assertRedirect(route('caterer.dashboard'));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertRedirect(route('admin.dashboard'));
    }
}
