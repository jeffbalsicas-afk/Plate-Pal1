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

    public function test_caterer_menu_items_are_available_without_admin_approval(): void
    {
        $caterer = $this->approvedCaterer();

        $response = $this->actingAs($caterer)->post(route('menu.items.store'), [
            'name' => 'Approval Test Chicken',
            'description' => 'Crispy chicken platter.',
            'price' => '350',
            'unit' => 'tray',
            'category' => 'main',
        ]);

        $response->assertRedirect(route('caterer.menu-pricing', ['tab' => 'items']));
        $response->assertSessionHas('success', 'Menu item created successfully.');

        $item = MenuItem::where('name', 'Approval Test Chicken')->firstOrFail();

        $this->assertSame($caterer->id, $item->caterer_id);
        $this->assertSame('menu', $item->type);
    }

    public function test_package_submissions_are_live_without_admin_approval(): void
    {
        $caterer = $this->approvedCaterer();

        $response = $this->actingAs($caterer)->post(route('menu.packages.store'), [
            'name' => 'Approval Test Package',
            'description' => 'Package available immediately.',
            'price' => '450',
            'min_guests' => '30',
        ]);

        $response->assertRedirect(route('caterer.menu-pricing', ['tab' => 'packages']));
        $response->assertSessionHas('success', 'Package created successfully.');

        $package = Package::where('name', 'Approval Test Package')->firstOrFail();

        $this->assertSame('live', $package->status);
    }

    public function test_clients_see_saved_menu_and_package_entries_immediately(): void
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
            'name' => 'Formerly Pending Fiesta Package',
            'description' => 'Visible without package approval.',
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
        ]);

        MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'New Roast Chicken',
            'description' => 'Visible without item approval.',
            'price' => 300,
            'unit' => 'tray',
            'type' => 'menu',
            'category' => 'main',
        ]);

        $response = $this->actingAs($client)->get(route('caterer.detail', $caterer));

        $response->assertOk();
        $response->assertSee('Visible Fiesta Package');
        $response->assertSee('Formerly Pending Fiesta Package');
        $response->assertSee('Visible Roast Chicken');
        $response->assertSee('New Roast Chicken');
    }

    public function test_caterer_menu_pricing_filters_active_menu_tab(): void
    {
        $caterer = $this->approvedCaterer();

        MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'Chocolate Cake Tray',
            'description' => 'Dessert table favorite.',
            'price' => 750,
            'unit' => 'tray',
            'type' => 'menu',
            'category' => 'dessert',
        ]);

        MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'Garlic Chicken Tray',
            'description' => 'Main dish platter.',
            'price' => 500,
            'unit' => 'tray',
            'type' => 'menu',
            'category' => 'main',
        ]);

        MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'Iced Tea Dispenser',
            'description' => 'Beverage service.',
            'price' => 300,
            'unit' => 'dispenser',
            'type' => 'menu',
            'category' => 'beverage',
        ]);

        $response = $this->actingAs($caterer)->get(route('caterer.menu-pricing', [
            'tab' => 'items',
            'category' => 'dessert',
        ]));

        $response->assertOk();
        $response->assertSee('Chocolate Cake Tray');
        $response->assertDontSee('Garlic Chicken Tray');
        $response->assertDontSee('Iced Tea Dispenser');
    }

    public function test_caterer_menu_pricing_price_sort_replaces_default_latest_sort(): void
    {
        $caterer = $this->approvedCaterer();

        MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'Budget Noodles',
            'description' => 'Affordable tray.',
            'price' => 150,
            'unit' => 'tray',
            'type' => 'menu',
            'category' => 'main',
            'created_at' => now()->subMinutes(2),
            'updated_at' => now()->subMinutes(2),
        ]);

        MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'Premium Roast Beef',
            'description' => 'Premium tray.',
            'price' => 900,
            'unit' => 'tray',
            'type' => 'menu',
            'category' => 'main',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        MenuItem::create([
            'caterer_id' => $caterer->id,
            'name' => 'Classic Chicken',
            'description' => 'Mid-range tray.',
            'price' => 450,
            'unit' => 'tray',
            'type' => 'menu',
            'category' => 'main',
            'created_at' => now()->subMinute(),
            'updated_at' => now()->subMinute(),
        ]);

        $response = $this->actingAs($caterer)->get(route('caterer.menu-pricing', [
            'tab' => 'items',
            'sort' => 'price_asc',
        ]));

        $response->assertOk();
        $response->assertSeeInOrder([
            'Budget Noodles',
            'Classic Chicken',
            'Premium Roast Beef',
        ]);
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
}
