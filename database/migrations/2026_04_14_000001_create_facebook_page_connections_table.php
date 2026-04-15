<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facebook_page_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('page_id');
            $table->string('page_name');
            $table->text('page_access_token');
            $table->enum('status', ['active', 'inactive', 'error'])->default('active');
            $table->json('bot_context')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'page_id']);
            $table->index('page_id');
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_page_connections');
    }
};
