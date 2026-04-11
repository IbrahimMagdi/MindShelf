<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();

            $table->morphs('tokenable');

            $table->text('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();

            // 🔹 Device info
            $table->string('device_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('device')->nullable();

            // 🔹 Lifecycle
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('access_expires_at')->nullable()->index();
            $table->timestamp('refresh_expires_at')->nullable()->index();

            // 🔥 NEW: token type instead of boolean
            $table->string('token_type')->nullable()->index();            // values: access | refresh

            $table->timestamps();

            // indexes
            $table->index(['tokenable_id', 'tokenable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
