<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index(['company_id', 'is_active']);
        });

        Schema::create('bot_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('trigger_type', 64);
            $table->string('trigger_value', 512)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('bot_id');
            $table->index(['bot_id', 'trigger_type']);
        });

        Schema::create('bot_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flow_id')->constrained('bot_flows')->cascadeOnDelete();
            $table->string('type', 64);
            $table->json('content');
            $table->unsignedBigInteger('next_node_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('flow_id');
            $table->index('next_node_id');
        });

        Schema::table('bot_nodes', function (Blueprint $table) {
            $table->foreign('next_node_id')
                ->references('id')
                ->on('bot_nodes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bot_nodes', function (Blueprint $table) {
            $table->dropForeign(['next_node_id']);
        });

        Schema::dropIfExists('bot_nodes');
        Schema::dropIfExists('bot_flows');
        Schema::dropIfExists('bots');
    }
};
