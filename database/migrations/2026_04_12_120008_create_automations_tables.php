<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('trigger_type', 64);
            $table->string('trigger_value', 512)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index(['company_id', 'is_active']);
        });

        Schema::create('automation_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('automation_id')->constrained()->cascadeOnDelete();
            $table->string('action_type', 64);
            $table->json('action_value')->nullable();
            $table->unsignedInteger('execution_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['automation_id', 'execution_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_actions');
        Schema::dropIfExists('automations');
    }
};
