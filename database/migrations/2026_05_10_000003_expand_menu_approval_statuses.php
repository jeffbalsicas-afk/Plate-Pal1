<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE menu_items MODIFY status ENUM('draft', 'pending', 'live', 'rejected') NOT NULL DEFAULT 'draft'");
        DB::statement("ALTER TABLE packages MODIFY status ENUM('draft', 'pending', 'live', 'rejected') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::table('menu_items')->whereIn('status', ['pending', 'rejected'])->update(['status' => 'draft']);
        DB::table('packages')->whereIn('status', ['pending', 'rejected'])->update(['status' => 'draft']);

        DB::statement("ALTER TABLE menu_items MODIFY status ENUM('live', 'draft') NOT NULL DEFAULT 'draft'");
        DB::statement("ALTER TABLE packages MODIFY status ENUM('live', 'draft') NOT NULL DEFAULT 'draft'");
    }
};
