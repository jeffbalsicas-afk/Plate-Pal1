<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable()->after('caterer_id')->constrained('packages')->nullOnDelete();
            $table->string('package_name')->nullable()->after('package_id');
            $table->decimal('package_price', 10, 2)->nullable()->after('package_name');
            $table->text('special_requests')->nullable()->after('guests');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('package_id');
            $table->dropColumn(['package_name', 'package_price', 'special_requests']);
        });
    }
};
