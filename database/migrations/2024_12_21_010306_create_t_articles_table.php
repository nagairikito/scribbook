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
            Schema::create('articles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title', 255)->comment('ブログタイトル');
                $table->text('contents')->comment('ブログコンテンツ');
                $table->integer('created_by')->comment('ユーザーID: users.id');
                $table->integer('view_count')->comment('閲覧数');
                $table->timestamps();
                $table->comment('ブログ');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
