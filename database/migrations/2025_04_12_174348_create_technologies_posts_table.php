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
        Schema::create('technologies_posts', function (Blueprint $table) {
            $table->foreignId("technology_id")->constrained("technologies")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("post_id")->constrained("posts")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technologies_posts');
    }
};
