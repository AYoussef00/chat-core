<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('channel_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 64);
            $table->unsignedBigInteger('count')->default(0);
            $table->date('log_date');
            $table->timestamps();

            $table->unique(['company_id', 'channel_id', 'type', 'log_date'], 'message_logs_company_channel_type_date_unique');
            $table->index(['company_id', 'log_date']);
            $table->index('channel_id');
        });

        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('channel', ['whatsapp', 'messenger', 'system']);
            $table->string('event_type', 128);
            $table->json('payload');
            $table->enum('status', ['received', 'processed', 'failed', 'ignored'])->default('received');
            $table->timestamp('created_at', 3)->useCurrent();

            $table->index(['company_id', 'created_at']);
            $table->index(['channel', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
        Schema::dropIfExists('message_logs');
    }
};
