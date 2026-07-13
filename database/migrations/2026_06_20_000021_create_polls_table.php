<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('community_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('question');
            $table->string('author_name');            // denormalised display name
            $table->string('avatar_color')->default('#F7A8B5');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};
