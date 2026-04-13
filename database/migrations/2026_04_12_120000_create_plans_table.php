<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);
            $table->unsignedBigInteger('messages_limit')->nullable()->comment('NULL = unlimited');
            $table->unsignedInteger('channels_limit')->nullable()->comment('NULL = unlimited');
            $table->boolean('ai_enabled')->default(false);
            $table->timestamps();

            $table->index('ai_enabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
