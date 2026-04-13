<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $companyId = DB::table('companies')->insertGetId([
            'name' => 'Default company',
            'email' => 'admin@localhost',
            'plan_id' => null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        Schema::table('users', function (Blueprint $table) use ($companyId) {
            $table->dropUnique(['email']);

            $table->foreignId('company_id')->after('id')->default($companyId)->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->nullable()->after('company_id')->constrained()->nullOnDelete();
            $table->string('status', 32)->default('active')->after('password');
            $table->softDeletes();

            $table->unique(['company_id', 'email']);
            $table->index('company_id');
            $table->index('role_id');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'email']);
            $table->dropForeign(['company_id']);
            $table->dropForeign(['role_id']);
            $table->dropColumn(['company_id', 'role_id', 'status', 'deleted_at']);
            $table->unique('email');
        });

        DB::table('companies')->where('email', 'admin@localhost')->delete();
    }
};
