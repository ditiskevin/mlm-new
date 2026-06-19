<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('babysitters', function (Blueprint $table) {
            // aanbod = ik bied oppas aan | gezocht = ik zoek een oppas
            $table->string('type')->default('aanbod')->after('user_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::table('babysitters', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
