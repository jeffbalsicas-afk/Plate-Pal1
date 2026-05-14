<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('role', 30)->nullable();
            $table->string('type', 30);
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('message');
            $table->string('page_url', 500)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status', 30)->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_feedback');
    }
};
