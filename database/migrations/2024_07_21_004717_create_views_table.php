<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('views', function (Blueprint $table) {
            $table->boolean('like')->default(0);
            $table->integer('rate');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('experience_id')->constrained('experiences')->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['user_id', 'experience_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
