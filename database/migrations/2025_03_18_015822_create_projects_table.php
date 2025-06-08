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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->unique();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->string('description', 255);
            $table->string('thumb', 155);
            $table->string('banner');
            $table->text('content');
            $table->boolean('public')->default(false);
            $table->boolean('favorite')->default(false);
            $table->integer('position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
