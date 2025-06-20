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
        if(!Schema::hasTable('m_users')) {
            Schema::create('m_users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 255)->comment('ユーザー名');
                $table->string('login_id', 255)->unique()->comment('ログインID');
                $table->string('password', 255)->comment('ログインパスワード');
                $table->string('icon_image')->default('noImage.png')->comment('プロフィールアイコン');
                $table->string('discription', 1000)->nullable()->comment('プロフィール概要欄');
                $table->integer('delete_flag')->default(0)->comment('削除フラグ');
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->timestamps();
                $table->comment('ユーザー');
            });
        }

        if(!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if(!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

    }

    // public function up(): void
    // {
    //     Schema::create('users', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('name');
    //         $table->string('email')->unique();
    //         $table->timestamp('email_verified_at')->nullable();
    //         $table->string('password');
    //         $table->rememberToken();
    //         $table->timestamps();
    //     });

    //     Schema::create('password_reset_tokens', function (Blueprint $table) {
    //         $table->string('email')->primary();
    //         $table->string('token');
    //         $table->timestamp('created_at')->nullable();
    //     });

    //     Schema::create('sessions', function (Blueprint $table) {
    //         $table->string('id')->primary();
    //         $table->foreignId('user_id')->nullable()->index();
    //         $table->string('ip_address', 45)->nullable();
    //         $table->text('user_agent')->nullable();
    //         $table->longText('payload');
    //         $table->integer('last_activity')->index();
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

