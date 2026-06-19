<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // vrouw | man | anders | geen
            $table->string('gender')->nullable()->after('parent_type');
            // moeder | vader | meeouder | aanstaande_ouder | verzorger | anders
            $table->string('parenting_role')->nullable()->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender', 'parenting_role']);
        });
    }
};
