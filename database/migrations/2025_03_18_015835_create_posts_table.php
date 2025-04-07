<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 125)->unique();
            $table->string('title', 150)->unique();
            $table->string('slug', 150)->unique();
            $table->string('description', 255);
            $table->text('content');
            $table->boolean('public')->default(false);
            $table->boolean('favorite')->default(false);
            $table->string('thumb', 155);
            $table->string('banner')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
