<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function editMenuItem(MenuItem $menuItem)
    {
        abort_if($menuItem->caterer_id !== auth()->id(), 403);

        return view('caterer.menu-edit', ['item' => $menuItem, 'type' => 'menu']);
    }

    public function storeMenuItem(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|in:head,tray,whole,bottle,box',
            'category' => 'required|string|in:main,side,dessert,beverage',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu-items', 'public');
            $validated['image_path'] = '/storage/'.$path;
        }

        MenuItem::create([
            'caterer_id' => auth()->id(),
            'type' => 'menu',
            ...$validated,
        ]);

        return redirect()
            ->route('caterer.menu-pricing', ['tab' => 'items'])
            ->with('success', 'Menu item created successfully.');
    }

    public function updateMenuItem(Request $request, MenuItem $menuItem)
    {
        abort_if($menuItem->caterer_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|in:head,tray,whole,bottle,box',
            'category' => 'required|string|in:main,side,dessert,beverage',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($menuItem->image_path && str_starts_with($menuItem->image_path, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $menuItem->image_path));
            }
            $path = $request->file('image')->store('menu-items', 'public');
            $validated['image_path'] = '/storage/'.$path;
        }

        $menuItem->update($validated);

        return redirect()
            ->route('caterer.menu-pricing', ['tab' => 'items'])
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroyMenuItem(MenuItem $menuItem)
    {
        abort_if($menuItem->caterer_id !== auth()->id(), 403);
        $menuItem->delete();

        return redirect()->route('caterer.menu-pricing', ['tab' => 'items'])->with('success', 'Menu item deleted successfully!');
    }

    public function editAddOn(MenuItem $menuItem)
    {
        abort_if($menuItem->caterer_id !== auth()->id(), 403);

        return view('caterer.addon-edit', ['addon' => $menuItem]);
    }

    public function storeAddOn(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|in:head,tray,bottle,box',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('addons', 'public');
            $validated['image_path'] = '/storage/'.$path;
        }

        MenuItem::create([
            'caterer_id' => auth()->id(),
            'type' => 'addon',
            'category' => 'addon',
            ...$validated,
        ]);

        return redirect()
            ->route('caterer.menu-pricing', ['tab' => 'addons'])
            ->with('success', 'Add-on created successfully.');
    }

    public function updateAddOn(Request $request, MenuItem $menuItem)
    {
        abort_if($menuItem->caterer_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|in:head,tray,bottle,box',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($menuItem->image_path && str_starts_with($menuItem->image_path, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $menuItem->image_path));
            }
            $path = $request->file('image')->store('addons', 'public');
            $validated['image_path'] = '/storage/'.$path;
        }

        $menuItem->update($validated);

        return redirect()
            ->route('caterer.menu-pricing', ['tab' => 'addons'])
            ->with('success', 'Add-on updated successfully.');
    }

    public function destroyAddOn(MenuItem $menuItem)
    {
        abort_if($menuItem->caterer_id !== auth()->id(), 403);
        $menuItem->delete();

        return redirect()->route('caterer.menu-pricing', ['tab' => 'addons'])->with('success', 'Add-on deleted successfully!');
    }

    public function storePackage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_guests' => 'required|integer|min:1',
            'includes' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['status'] = 'live';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('packages', 'public');
            $validated['image'] = $path;
        }

        Package::create([
            'caterer_id' => auth()->id(),
            'category' => 'bundled',
            ...$validated,
        ]);

        return redirect()
            ->route('caterer.menu-pricing', ['tab' => 'packages'])
            ->with('success', 'Package created successfully.');
    }

    public function updatePackage(Request $request, Package $package)
    {
        abort_if($package->caterer_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_guests' => 'required|integer|min:1',
            'includes' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['status'] = 'live';

        if ($request->hasFile('image')) {
            if ($package->image && Storage::disk('public')->exists($package->image)) {
                Storage::disk('public')->delete($package->image);
            }
            $path = $request->file('image')->store('packages', 'public');
            $validated['image'] = $path;
        }

        $package->update($validated);

        return redirect()
            ->route('caterer.menu-pricing', ['tab' => 'packages'])
            ->with('success', 'Package updated successfully.');
    }

    public function destroyPackage(Package $package)
    {
        abort_if($package->caterer_id !== auth()->id(), 403);
        $package->delete();

        return redirect()->route('caterer.menu-pricing', ['tab' => 'packages'])->with('success', 'Package deleted successfully!');
    }

    public function editPackage(Package $package)
    {
        abort_if($package->caterer_id !== auth()->id(), 403);

        return view('caterer.package-edit', ['package' => $package]);
    }
}
