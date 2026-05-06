<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('auto_feature_reviews')->default(true)->after('reviews_count');
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('caterer_id')->constrained('users')->cascadeOnDelete();
            $table->string('reviewer_name')->nullable();
            $table->string('package_name')->nullable();
            $table->string('title');
            $table->text('body');
            $table->unsignedTinyInteger('rating');
            $table->enum('status', ['public', 'hidden'])->default('public');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_auto_featured')->default(false);
            $table->text('caterer_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamp('reported_at')->nullable();
            $table->text('report_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['caterer_id', 'status']);
            $table->index(['caterer_id', 'is_featured']);
            $table->unique('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('auto_feature_reviews');
        });
    }
};
