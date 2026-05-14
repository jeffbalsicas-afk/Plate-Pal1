<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_caterer_menu_submissions_are_pending_until_admin_approval(): void
    {
        $caterer = $this->approvedCaterer();

        $response = $this->actingAs($caterer)->post(route('menu.items.store'), [
            'name' => 'Approval Test Chicken',
            'description' => 'Crispy chicken platter.',
            'price' => '350',
            'unit' => 'tray',
            'category' => 'main',
            'status' => 'live',
        ]);

        $response->assertRedirect(route('caterer.menu-pricing', ['tab' => 'items']));
        $response->assertSessionHas('success', 'Menu item created and submitted for admin approval.');

        $item = MenuItem::where('name', 'Approval Test Chicken')->firstOrFail();

        $this->assertSame('pending', $item->status);

        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.menu-items.approve', $item))
            ->assertRedirect()
            ->assertSessionHas('success', 'Approval Test Chicken is now live.');

        $this->assertSame('live', $item->refresh()->status);
    }

    public function test_package_submissions_can_be_rejected_by_admin(): void
    {
        $caterer = $this->approvedCaterer();

        $this->actingAs($caterer)->post(route('menu.packages.store'), [
            'name' => 'Approval Test Package',
            'description' => 'Package awaiting review.',
            'price' => '450',
            'min_guests' => '30',
            'status' => 'pending',
        ]);

        $package = Package::where('name', 'Approval Test Package')->firstOrFail();

        $this->assertSame('pending', $package->status);

        $this->actingAs($this->admin())
            ->post(route('admin.packages.reject', $package))
            ->assertRedirect()
            ->assertSessionHas('success', 'Approval Test Package has been rejected.');

        $this->assertSame('rejected', $package->refresh()->status);
    }

    public function test_clients_only_see_live_menu_and_package_entries(): void
    {
        $caterer = $this->approvedCaterer();
        $client = User::factory()->create(['role' => 'client']);

        Package::create([
            'caterer_id' => $caterer->id,
            'name' => 'Visible Fiesta Package',
            'description' => 'Approved package.',
            'price' => 500,
            'min_guests' => 20,
            'status' => 'live',
            'category' => 'bundled',
        ]);

        Package::create([
            'caterer_id' => $caterer->id,
            'name' => 'Pending Fiesta Package',
            'description' => 'Pending package.',
            'price' => 500,
            'min_guests' => 20,
            'status' => 'pending',
            'category' => 'bundled',
        ]);

        MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'Visible Roast Chicken',
            'description' => 'Approved item.',
            'price' => 300,
            'unit' => 'tray',
            'type' => 'menu',
            'category' => 'main',
            'status' => 'live',
        ]);

        MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'Pending Roast Chicken',
            'description' => 'Pending item.',
            'price' => 300,
            'unit' => 'tray',
            'type' => 'menu',
            'category' => 'main',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($client)->get(route('caterer.detail', $caterer));

        $response->assertOk();
        $response->assertSee('Visible Fiesta Package');
        $response->assertSee('Visible Roast Chicken');
        $response->assertDontSee('Pending Fiesta Package');
        $response->assertDontSee('Pending Roast Chicken');
    }

    private function approvedCaterer(): User
    {
        return User::factory()->create([
            'role' => 'caterer',
            'business_name' => 'Approval Test Catering',
            'barangay' => 'Apokon',
            'cuisine' => 'Filipino',
            'approval_status' => 'approved',
            'is_active' => true,
            'is_verified' => true,
        ]);
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'is_verified' => true,
            'approval_status' => 'approved',
        ]);
    }
}
