<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    private const CATERER_STATUS_RULE = 'required|string|in:draft,pending,live';

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
            'status' => self::CATERER_STATUS_RULE,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['status'] = $this->statusForCaterer($validated['status']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu-items', 'public');
            $validated['image_path'] = '/storage/' . $path;
        }

        MenuItem::create([
            'caterer_id' => auth()->id(),
            'type' => 'menu',
            ...$validated,
        ]);

        return redirect()
            ->route('caterer.menu-pricing', ['tab' => 'items'])
            ->with('success', $this->submissionMessage('Menu item', $validated['status'], 'created'));
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
            'status' => self::CATERER_STATUS_RULE,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['status'] = $this->statusForCaterer($validated['status']);

        if ($request->hasFile('image')) {
            if ($menuItem->image_path && str_starts_with($menuItem->image_path, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $menuItem->image_path));
            }
            $path = $request->file('image')->store('menu-items', 'public');
            $validated['image_path'] = '/storage/' . $path;
        }

        $menuItem->update($validated);

        return redirect()
            ->route('caterer.menu-pricing', ['tab' => 'items'])
            ->with('success', $this->submissionMessage('Menu item', $validated['status'], 'updated'));
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
            'status' => self::CATERER_STATUS_RULE,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['status'] = $this->statusForCaterer($validated['status']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('addons', 'public');
            $validated['image_path'] = '/storage/' . $path;
        }

        MenuItem::create([
            'caterer_id' => auth()->id(),
            'type' => 'addon',
            'category' => 'addon',
            ...$validated,
        ]);

        return redirect()
            ->route('caterer.menu-pricing', ['tab' => 'addons'])
            ->with('success', $this->submissionMessage('Add-on', $validated['status'], 'created'));
    }

    public function updateAddOn(Request $request, MenuItem $menuItem)
    {
        abort_if($menuItem->caterer_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|in:head,tray,bottle,box',
            'status' => self::CATERER_STATUS_RULE,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['status'] = $this->statusForCaterer($validated['status']);

        if ($request->hasFile('image')) {
            if ($menuItem->image_path && str_starts_with($menuItem->image_path, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $menuItem->image_path));
            }
            $path = $request->file('image')->store('addons', 'public');
            $validated['image_path'] = '/storage/' . $path;
        }

        $menuItem->update($validated);

        return redirect()
            ->route('caterer.menu-pricing', ['tab' => 'addons'])
            ->with('success', $this->submissionMessage('Add-on', $validated['status'], 'updated'));
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
            'status' => self::CATERER_STATUS_RULE,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['status'] = $this->statusForCaterer($validated['status']);

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
            ->with('success', $this->submissionMessage('Package', $validated['status'], 'created'));
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
            'status' => self::CATERER_STATUS_RULE,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['status'] = $this->statusForCaterer($validated['status']);

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
            ->with('success', $this->submissionMessage('Package', $validated['status'], 'updated'));
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

    private function statusForCaterer(string $status): string
    {
        return $status === 'draft' ? 'draft' : 'pending';
    }

    private function submissionMessage(string $label, string $status, string $action): string
    {
        if ($status === 'draft') {
            return "{$label} {$action} as draft.";
        }

        return "{$label} {$action} and submitted for admin approval.";
    }
}
