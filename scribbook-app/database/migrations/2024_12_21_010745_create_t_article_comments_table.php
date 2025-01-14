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
        if(!Schema::hasTable('article_comments')) {
            Schema::create('article_comments', function (Blueprint $table) {
                $table->integer('target_article')->comment('ブログID: articles.id');
                $table->string('comment')->comment('ブログコメント');
                $table->integer('created_by')->comment('コメント作成者: user.id');
                $table->timestamps();
                $table->comment('ブログコメント');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_comments');
    }
};
