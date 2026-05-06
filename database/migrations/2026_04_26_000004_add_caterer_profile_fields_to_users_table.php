<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cuisine')->nullable()->after('barangay');
            $table->string('price_range')->nullable()->after('cuisine');
            $table->string('profile_image')->nullable()->after('price_range');
            $table->decimal('rating', 3, 2)->default(0.00)->after('profile_image');
            $table->integer('reviews_count')->default(0)->after('rating');
            $table->boolean('is_verified')->default(false)->after('reviews_count');
            $table->boolean('is_active')->default(true)->after('is_verified');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cuisine', 'price_range', 'profile_image', 'rating', 'reviews_count', 'is_verified', 'is_active', 'approval_status']);
        });
    }
};
