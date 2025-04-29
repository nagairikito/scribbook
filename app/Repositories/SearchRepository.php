<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;

use App\Const\AccountConst;

class SearchRepository extends Repository
{
    public $user;
    public $blog;
    public function __construct() {
        // Modelのインスタンス化
        $this->user = new User;
        $this->blog = new Article;

    }
    
    /**
     * 検索結果取得
     * @param $inputData
     * @return boolean $result
     */
    public function search($inputData) {
        $blogs = $this->blog
        ->join('users', 'users.id', '=', 'articles.created_by')
        ->where('articles.title', 'LIKE', "%{$inputData['keyword']}%")
        ->where('articles.contents', 'LIKE', "%{$inputData['keyword']}%")
        ->where('users.name', 'LIKE', "%{$inputData['keyword']}%")
        ->where('users.delete_flag', '=', AccountConst::USER_DELETE_FLAG_OFF)
        ->select(
            'articles.id',
            'articles.title',
            'articles.contents',
            'articles.created_at',
            'articles.updated_at',
            'articles.created_by',
            'users.name',
        )
        ->get();

        $users = $this->user
        ->where('name', 'LIKE', "%{$inputData['keyword']}%")
        ->where('delete_flag', '=', AccountConst::USER_DELETE_FLAG_OFF)
        ->select(
            'id',
            'name',
            'icon_image',
            'updated_at',
        )
        ->get();

        $result = [
            'blogs' => !empty($blogs) ? $blogs->toArray() : [], 
            'users' => !empty($users) ? $users->toArray() : [], 
        ];
        return $result;
    }

}
