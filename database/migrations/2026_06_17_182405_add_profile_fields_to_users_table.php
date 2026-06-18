<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role_label')->nullable()->after('email');
            $table->text('bio')->nullable()->after('role_label');
            $table->string('avatar_color')->default('#F7A8B5')->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role_label', 'bio', 'avatar_color']);
        });
    }
};
