# Menu & Pricing System - Simplified Architecture

## Consolidation Summary

### Before (11 files)
- MenuItem.php (model)
- AddOn.php (model)
- Package.php (model)
- MenuItemPolicy.php
- AddOnPolicy.php
- PackagePolicy.php
- MenuController.php
- 3 migrations
- 1 view

### After (7 files)
- MenuItem.php (unified model for both menu items and add-ons)
- Package.php (model)
- MenuController.php (handles all CRUD)
- 2 migrations (menu_items + update_packages)
- 1 view

**Files reduced: 11 → 7 (-36%)**

## Key Changes

### 1. Unified MenuItem Model
```php
// Single model handles both types
MenuItem::create(['type' => 'menu', ...]);
MenuItem::create(['type' => 'addon', ...]);

// Query by type
MenuItem::menuItems()->get();  // type = 'menu'
MenuItem::addOns()->get();     // type = 'addon'
```

### 2. Single menu_items Table
```sql
CREATE TABLE menu_items (
    id, caterer_id, name, description, price, unit,
    type ENUM('menu', 'addon'),  -- Distinguishes items
    category, status, image_path, timestamps
)
```

### 3. Inline Authorization
```php
// Instead of policies
abort_if($menuItem->caterer_id !== auth()->id(), 403);
```

### 4. Query Scopes
```php
// Reusable scopes
MenuItem::forCaterer($id)->menuItems()->get();
MenuItem::forCaterer($id)->addOns()->get();
```

## Database Migration

Run migrations:
```bash
php artisan migrate
```

This creates:
- `menu_items` table (unified for both menu items and add-ons)
- Updates `packages` table with status and category

## Usage in Controller

```php
// Fetch all data
$packages = Package::forCaterer($catererId)->get();
$menuItems = MenuItem::forCaterer($catererId)->menuItems()->get();
$addOns = MenuItem::forCaterer($catererId)->addOns()->get();

// Create menu item
MenuItem::create(['type' => 'menu', 'caterer_id' => $id, ...]);

// Create add-on
MenuItem::create(['type' => 'addon', 'caterer_id' => $id, ...]);
```

## Benefits

✅ **Simpler** - One model instead of two
✅ **Fewer files** - Removed 4 files (policies + AddOn model)
✅ **Cleaner** - Inline authorization instead of policy classes
✅ **Maintainable** - Single source of truth for menu items
✅ **Flexible** - Easy to add more types in future

## Migration Path

If you already ran migrations:
```bash
php artisan migrate:rollback
php artisan migrate
```

Or manually:
1. Drop `add_ons` table
2. Add `type` column to `menu_items` table
3. Migrate existing add-ons to menu_items with type='addon'
