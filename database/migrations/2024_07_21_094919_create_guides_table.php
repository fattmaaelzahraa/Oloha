<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('about');
            $table->string('guide_photo')->nullable();
            $table->string('guide_city');
            $table->string('guiding_type');
            $table->string('price');
            $table->json('interests');
            $table->json('languages');
            $table->json('activities');
            $table->string('guide_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
