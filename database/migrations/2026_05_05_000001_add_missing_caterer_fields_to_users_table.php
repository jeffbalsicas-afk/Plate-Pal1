<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('description')->nullable()->after('barangay');
            $table->decimal('price_min', 10, 2)->nullable()->after('price_range');
            $table->decimal('price_max', 10, 2)->nullable()->after('price_min');
            $table->integer('min_guest')->nullable()->after('price_max');
            $table->integer('max_guest')->nullable()->after('min_guest');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['description', 'price_min', 'price_max', 'min_guest', 'max_guest']);
        });
    }
};
