<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 64);
            $table->string('api_key', 512);
            $table->string('model', 128);
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();

            $table->unique(['company_id', 'provider']);
            $table->index('company_id');
        });

        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->text('question');
            $table->mediumText('answer');
            $table->unsignedInteger('tokens_used')->default(0);
            $table->timestamp('created_at', 3)->useCurrent();

            $table->index(['company_id', 'created_at']);
            $table->index('contact_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
        Schema::dropIfExists('ai_settings');
    }
};
