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
        Schema::create('t_browsing_histories', function (Blueprint $table) {
            $table->integer('user_id')->comment('users.id');
            $table->integer('blog_id')->comment('articles.id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('t_browsing_histories');
    }
};
