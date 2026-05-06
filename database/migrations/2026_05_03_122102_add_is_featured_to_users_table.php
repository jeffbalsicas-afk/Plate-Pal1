<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('caterers', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('caterers', function (Blueprint $table) {
            if (Schema::hasColumn('caterers', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};
