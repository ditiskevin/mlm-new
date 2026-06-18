<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['community_groups', 'forum_categories', 'articles'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->foreignId('audience_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (['community_groups', 'forum_categories', 'articles'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropConstrainedForeignId('audience_id');
            });
        }
    }
};
