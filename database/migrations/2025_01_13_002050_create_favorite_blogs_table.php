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
        Schema::create('favorite_blogs', function (Blueprint $table) {
            $table->integer('user_id')->comment('users.id');
            $table->integer('blog_id')->comment('articles.id');
            $table->timestamps();
            $table->comment('お気に入りブログ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_blogs');
    }
};
