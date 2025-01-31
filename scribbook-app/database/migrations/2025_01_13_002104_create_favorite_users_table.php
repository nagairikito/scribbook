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
        Schema::create('favorite_users', function (Blueprint $table) {
            $table->integer('user_id')->comment('users.id');
            $table->integer('favorite_user_id')->comment('users.id');
            $table->timestamps();
            $table->comment('お気に入りユーザー');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_users');
    }
};
