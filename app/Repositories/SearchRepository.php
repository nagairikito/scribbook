<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Const\AccountConst;
use App\Models\Article;
use App\Models\User;

class SearchRepository extends Repository
{
    public $user;
    public $blog;

    public function __construct(User $user, Article $article) {
        // Modelのインスタンス化
        $this->user = $user;
        $this->blog = $article;

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
        ->where('users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
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
        ->where('delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
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
