<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_pages_are_available_for_each_role(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Welcome Back, Client');

        $this->get('/caterer/login')
            ->assertOk()
            ->assertSee('Welcome Back, Caterer');

        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Welcome Back, Admin');
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

    public function test_client_login_rejects_caterer_and_admin_accounts(): void
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

        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->post('/login', [
            'email' => $admin->email,
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

    public function test_caterer_login_rejects_client_and_admin_accounts(): void
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

        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->post('/caterer/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_login_only_accepts_admin_accounts(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_login_rejects_client_and_caterer_accounts(): void
    {
        $client = User::factory()->create([
            'email' => 'client@example.com',
            'role' => 'client',
        ]);

        $this->post('/admin/login', [
            'email' => $client->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();

        $caterer = User::factory()->create([
            'email' => 'caterer@example.com',
            'role' => 'caterer',
        ]);

        $this->post('/admin/login', [
            'email' => $caterer->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_guests_are_sent_to_the_matching_login_page_for_protected_areas(): void
    {
        $this->get('/dashboard')
            ->assertRedirect(route('login'));

        $this->get('/caterer/dashboard')
            ->assertRedirect(route('caterer.login'));

        $this->get('/admin/dashboard')
            ->assertRedirect(route('admin.login'));
    }

    public function test_local_password_reset_request_redirects_to_reset_form_for_simulation(): void
    {
        $user = User::factory()->create([
            'email' => 'client@example.com',
            'role' => 'client',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'CLIENT@example.com',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('status', 'Simulation mode: reset link opened for you.');

        $location = $response->headers->get('Location');

        $this->assertIsString($location);
        $this->assertStringContainsString('/reset-password/', $location);
        $this->assertStringContainsString('email='.urlencode($user->email), $location);
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
