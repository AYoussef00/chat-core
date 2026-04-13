<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('channel_id')->constrained()->restrictOnDelete();
            $table->string('external_id')->comment('PSID, wa_id, etc.');
            $table->string('name')->nullable();
            $table->string('phone', 64)->nullable();
            $table->dateTime('last_message_at', 3)->nullable();
            $table->timestamps(3);
            $table->softDeletes();

            $table->unique(['company_id', 'channel_id', 'external_id']);
            $table->index('company_id');
            $table->index('channel_id');
            $table->index(['company_id', 'last_message_at']);
            $table->index('deleted_at');
        });

        Schema::create('contact_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'name']);
            $table->index('company_id');
        });

        Schema::create('contact_tag_map', function (Blueprint $table) {
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('contact_tags')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['contact_id', 'tag_id']);
            $table->index('tag_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_tag_map');
        Schema::dropIfExists('contact_tags');
        Schema::dropIfExists('contacts');
    }
};
