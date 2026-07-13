<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reply_helpful_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_reply_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // A user can only mark a given reply helpful once.
            $table->unique(['forum_reply_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reply_helpful_votes');
    }
};
