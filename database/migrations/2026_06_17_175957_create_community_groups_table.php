<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('members')->default(0);
            $table->string('color_from'); // gradient start
            $table->string('color_to');   // gradient end
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_groups');
    }
};
