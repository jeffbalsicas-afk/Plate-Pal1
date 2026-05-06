<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->enum('status', ['live', 'draft'])->default('draft')->after('min_guests');
            $table->string('category')->default('bundled')->after('status'); // bundled, ala-carte, addon
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['status', 'category']);
        });
    }
};
