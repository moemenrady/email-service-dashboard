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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable()->index();
            $table->string('user_role')->nullable();
            $table->string('assigned_domain')->nullable()->index();
            $table->string('action_type', 50)->index();
            $table->string('action_title', 255);
            $table->json('details')->nullable();
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('device_type', 50)->nullable()->index();
            $table->string('browser', 80)->nullable();
            $table->string('platform', 80)->nullable();
            $table->timestamps();

            $table->index(['created_at', 'action_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
