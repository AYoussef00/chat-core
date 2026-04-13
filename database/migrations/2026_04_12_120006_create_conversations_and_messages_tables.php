<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['open', 'pending', 'resolved', 'spam'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('last_message_at', 3)->nullable();
            $table->timestamps(3);
            $table->softDeletes();

            $table->index('company_id');
            $table->index('contact_id');
            $table->index(['company_id', 'assigned_to']);
            $table->index(['company_id', 'last_message_at']);
            $table->index('deleted_at');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->enum('sender_type', ['user', 'contact', 'bot']);
            $table->unsignedBigInteger('sender_id')->comment('Polymorphic: users.id / contacts.id / bots.id');
            $table->string('message_type', 64);
            $table->mediumText('content')->nullable();
            $table->json('payload')->nullable();
            $table->enum('status', ['pending', 'sent', 'delivered', 'read', 'failed'])->default('sent');
            $table->timestamp('created_at', 3)->useCurrent();

            $table->index(['conversation_id', 'created_at']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
