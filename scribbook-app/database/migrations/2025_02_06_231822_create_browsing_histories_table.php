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
        Schema::create('browsing_histories', function (Blueprint $table) {
            $table->integer('user_id')->comment('users.id');
            $table->integer('blog_id')->comment('articles.id');
            $table->timestamps();
            $table->comment('閲覧履歴');
            $table->primary(['user_id', 'blog_id']); // 複合キー
            $table->unique(['user_id', 'blog_id']); // 複合キーを一意にする
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('browsing_histories');
    }
};
