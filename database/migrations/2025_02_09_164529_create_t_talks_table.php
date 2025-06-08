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
        Schema::create('t_talks', function (Blueprint $table) {
            $table->longText('message')->comment('メッセージ');
            $table->longText('attached_file_path')->nullable()->comment('添付ファイルのパス');
            $table->integer('talk_room_id')->comment('トークルームID');
            $table->integer('read_flag')->default(0)->comment('0:未読、1:既読');
            $table->integer('delete_flag_1')->default(0)->comment('トークルームテーブルのユーザー1の削除フラグ');
            $table->integer('delete_flag_2')->default(0)->comment('トークルームテーブルのユーザー2の削除フラグ');
            $table->integer('created_by')->comment('メッセージ送信者');
            $table->integer('updated_by')->comment('メッセージ更新者');
            $table->timestamps();
            $table->comment('トークメッセージ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_talks');
    }
};
