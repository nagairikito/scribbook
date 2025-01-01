<?php
namespace App\Const;

class AccountConst
{
    // アカウント新規登録
    const SUCCESS_ACCOUNT_REGISTERATION   = 1;
    const FAIL_ACCOUNT_REGISTERATION      = 0;
    
    // ログイン
    const LOGIN_INITIAL_VALUE             = 0;
    const NOT_FOUND_LOGIN_ID              = 1;
    const NOT_MATCH_PASSWORD              = 2;
    const SUCCESS_LOGIN                   = 3;

    // ユーザーステータス
    const USER_DELETE_FLAG_OFF            = 0;
    const USER_DELETE_FLAG_ON             = 1;

    // プロフィール更新ステータス
    const UPDATE_INITIAL_VALUE            = 0;
    const FAIL_USER_AUTHENTICATION        = 1;
    const NOT_FOUND_USER_ID               = 2;
    const SUCCESS_ACCOUNT_UPDATING        = 3;

}