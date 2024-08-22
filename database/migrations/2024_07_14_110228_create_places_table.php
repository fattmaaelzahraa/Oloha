<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('about');
            $table->string('opening_hours');
            $table->string('waiting_time');
            $table->string('type');
            $table->integer('Capacity');
            $table->string('good_for');
            $table->string('privileges');
            $table->point('location')->nullable;
            $table->string('vibes');
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
