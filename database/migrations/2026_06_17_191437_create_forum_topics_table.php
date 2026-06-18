<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name');
            $table->string('avatar_color')->default('#F7A8B5');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('body');
            $table->boolean('pinned')->default(false);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            $table->index('forum_category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_topics');
    }
};
