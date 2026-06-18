<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reveal_ideas', function (Blueprint $table) {
            // 'vertellen' | 'gender' | 'kaartje'
            $table->string('group')->default('vertellen')->after('id');
            $table->index('group');
        });
    }

    public function down(): void
    {
        Schema::table('reveal_ideas', function (Blueprint $table) {
            $table->dropIndex(['group']);
            $table->dropColumn('group');
        });
    }
};
