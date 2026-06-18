<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('community_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name');          // denormalised display name
            $table->string('avatar_color')->default('#F7B3C0');
            $table->string('tag')->nullable();       // e.g. Mijlpaal, Steun
            $table->text('body');
            $table->unsignedInteger('base_likes')->default(0); // seed/baseline likes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
