<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verwacht dit lid een kindje?
            $table->boolean('is_expecting')->default(false)->after('parenting_role');
            // Uitgerekende datum (alleen relevant als is_expecting)
            $table->date('due_date')->nullable()->after('is_expecting');
            // Aantal kinderen dat het lid al heeft
            $table->unsignedTinyInteger('children_count')->nullable()->after('due_date');
            // Wanneer de onboarding-wizard is afgerond
            $table->timestamp('onboarded_at')->nullable()->after('children_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_expecting', 'due_date', 'children_count', 'onboarded_at']);
        });
    }
};
