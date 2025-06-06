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
        Schema::create('t_advertisements', function (Blueprint $table) {
                $table->increments('id');
                $table->text('advertisement_image_name')->comment('広告の画像名');
                $table->text('url')->comment('url');
                $table->integer('blog_id')->comment('紐づくブログのID');
                $table->integer('access_count')->default(0)->comment('アクセス数');
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->timestamps();
                $table->comment('広告');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_advertisements');
    }
};
