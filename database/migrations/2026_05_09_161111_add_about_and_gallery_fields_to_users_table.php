<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('our_story')->nullable()->after('description');
            $table->text('what_makes_special')->nullable()->after('our_story');
            $table->json('services_offered')->nullable()->after('what_makes_special');
            $table->json('gallery_images')->nullable()->after('services_offered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['our_story', 'what_makes_special', 'services_offered', 'gallery_images']);
        });
    }
};
