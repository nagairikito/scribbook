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
        Schema::create('t_talk_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id_1')->comment('ユーザー1');
            $table->integer('user_id_2')->comment('ユーザー2');
            $table->integer('delete_flag_1')->default(0)->comment('ユーザー1の削除フラグ');
            $table->integer('delete_flag_2')->default(0)->comment('ユーザー2の削除フラグ');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->comment('トークルーム');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_talk_rooms');
    }
};
