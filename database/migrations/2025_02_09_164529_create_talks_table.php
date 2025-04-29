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
        Schema::create('talks', function (Blueprint $table) {
            $table->string('message', 500)->comment('メッセージ');
            $table->string('attached_file_path')->nullable()->comment('添付ファイルのパス');
            $table->integer('created_by')->comment('送信者');
            $table->integer('talk_room_id')->comment('トークルームID');
            $table->integer('delete_flag_1')->default(0)->comment('トークルームテーブルのユーザー1の削除フラグ');
            $table->integer('delete_flag_2')->default(0)->comment('トークルームテーブルのユーザー2の削除フラグ');
            $table->timestamps();
            $table->comment('トークメッセージ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talks');
    }
};
