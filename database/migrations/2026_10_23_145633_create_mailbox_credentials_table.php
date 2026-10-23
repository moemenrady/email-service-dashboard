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
        Schema::create('mailbox_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->index();
            $table->string('username');
            $table->string('email')->index();
            $table->text('password_encrypted')->nullable();
            $table->unsignedInteger('quota')->nullable()->default(1024);
            $table->unsignedInteger('limit')->nullable()->default(9600);
            $table->timestamps();

            $table->unique(['domain', 'username']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailbox_credentials');
    }
};
