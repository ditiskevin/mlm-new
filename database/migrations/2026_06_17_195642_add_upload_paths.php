<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('avatar_color');
        });
        Schema::table('listings', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('emoji');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar_path');
        });
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
