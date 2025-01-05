<?php
namespace App\Const;

class BlogConst
{
    // ブログ登録
    const REGISTER_INITIAL_VALUE                = 0;
    const FAIL_REGISTER_USER_AUTHENTICATION     = 1;
    const NOT_FOUND_REGISTER_USER_ID            = 2;
    const SUCCESS_BLOG_REGISTERATION            = 3;

    
    // ブログ編集ステータス
    const EDIT_INITIAL_VALUE              = 0;
    const FAIL_EDIT_USER_AUTHENTICATION   = 1;
    const NOT_FOUND_EDIT_USER_ID          = 2;
    const SUCCESS_BLOG_EDITING            = 3;

    // ブログ削除ステータス
    const DELETE_INITIAL_VALUE            = 0;
    const FAIL_DELETE_USER_AUTHENTICATION = 1;
    const NOT_FOUND_DELETE_USER_ID        = 2;
    const SUCCESS_BLOG_DELETING           = 3;

}