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
        if(!Schema::hasTable('t_blogs')) {
            Schema::create('t_blogs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('blog_unique_id', 50)->comment('ブログユニークID');
                $table->string('title', 255)->comment('ブログタイトル');
                $table->longText('contents')->comment('ブログコンテンツ');
                $table->integer('view_count')->default(0)->comment('閲覧数');
                $table->integer('created_by');
                $table->integer('updated_by');
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
        Schema::dropIfExists('t_blogs');
    }
};
