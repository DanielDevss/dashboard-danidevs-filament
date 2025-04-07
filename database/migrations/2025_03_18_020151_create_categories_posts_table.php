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
        Schema::create('categories_posts', function (Blueprint $table) {
            $table->foreignId('category_post_id')
                ->constrained('category_posts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('post_id')
                ->constrained('posts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_posts');
    }
};
