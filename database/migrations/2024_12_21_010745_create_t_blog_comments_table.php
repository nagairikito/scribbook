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
        if(!Schema::hasTable('t_blog_comments')) {
            Schema::create('t_blog_comments', function (Blueprint $table) {
                $table->integer('blog_id')->comment('ブログID: t_blogs.id');
                $table->string('comment')->comment('ブログコメント');
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('t_blog_comments');
    }
};
