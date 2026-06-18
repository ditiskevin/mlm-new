<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('babysitters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('location');                        // stad / regio
            $table->decimal('hourly_rate', 6, 2)->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->string('availability');                    // bv. "Avonden & weekenden"
            $table->boolean('has_vog')->default(false);        // Verklaring Omtrent Gedrag
            $table->text('bio');
            $table->string('avatar_color')->default('#9AD3AC');
            $table->timestamps();

            $table->index('location');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('babysitters');
    }
};
