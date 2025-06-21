<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Const\AccountConst;
use App\Models\Blog;
use App\Models\User;

class SearchRepository extends Repository
{
    public $user;
    public $blog;

    public function __construct(User $user, Blog $blog) {
        // Modelのインスタンス化
        $this->user = $user;
        $this->blog = $blog;

    }
    
    /**
     * 検索結果取得
     * @param array $inputData
     * @return array $result
     */
    public function search($inputData) {
        $blogs = $this->blog
        ->join('m_users', 'm_users.id', '=', 't_blogs.created_by')
        ->orWhere('t_blogs.title', 'LIKE', "%{$inputData['keyword']}%")
        ->orWhere('t_blogs.contents', 'LIKE', "%{$inputData['keyword']}%")
        ->orWhere('m_users.name', 'LIKE', "%{$inputData['keyword']}%")
        ->where('m_users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
        ->select(
            't_blogs.id as id',
            't_blogs.thumbnail as thumbnail',
            't_blogs.blog_unique_id as blog_unique_id',
            't_blogs.title as title',
            't_blogs.contents as contents',
            't_blogs.created_at as created_at',
            't_blogs.updated_at as updated_at',
            't_blogs.created_by as created_by',
            'm_users.name as name',
            'm_users.icon_image as icon_image',
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
