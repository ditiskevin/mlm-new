<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Per-user favourite baby names
        Schema::create('baby_name_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('baby_name_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'baby_name_id']);
        });

        // Per-user checklist progress (only ticked items are stored)
        Schema::create('checklist_item_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('checklist_item_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'checklist_item_id']);
        });

        // Per-user group follows
        Schema::create('community_group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('community_group_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'community_group_id']);
        });

        // Per-user post likes
        Schema::create('post_user_like', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_user_like');
        Schema::dropIfExists('community_group_user');
        Schema::dropIfExists('checklist_item_user');
        Schema::dropIfExists('baby_name_user');
    }
};
