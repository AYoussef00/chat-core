<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['whatsapp', 'messenger']);
            $table->string('name');
            $table->enum('status', ['active', 'inactive', 'error'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index(['company_id', 'type']);
            $table->index('deleted_at');
        });

        Schema::create('channel_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->cascadeOnDelete();
            $table->string('external_id');
            $table->text('access_token');
            $table->text('refresh_token')->nullable();
            $table->dateTime('token_expiry')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['channel_id', 'external_id']);
            $table->index('channel_id');
            $table->index('deleted_at');
        });

        Schema::create('channel_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_account_id')->constrained('channel_accounts')->cascadeOnDelete();
            $table->string('page_id');
            $table->string('page_name')->nullable();
            $table->text('page_token');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['channel_account_id', 'page_id']);
            $table->index('channel_account_id');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_pages');
        Schema::dropIfExists('channel_accounts');
        Schema::dropIfExists('channels');
    }
};
