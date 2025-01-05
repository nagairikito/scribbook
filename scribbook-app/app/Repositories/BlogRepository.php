<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Article;

use App\Const\AccountConst;

use function Laravel\Prompts\select;

class BlogRepository extends Repository
{
    public $blog;
    public function __construct() {
        // Modelのインスタンス化
        $this->blog = new Article;

    }
    
    /**
     * ブログ登録
     * @param $inputData
     * @return boolean $result
     */
    public function postBlog($inputData) {
        $this->blog->title = $inputData['title'];
        $this->blog->contents = $inputData['contents'];
        $this->blog->created_by = $inputData['user_id'];
        $result = $this->blog->save();
        return $result;
    }

    /**
     * ブログ編集
     * @param $inputData
     * @return $$result
     */
    public function editBlog($inputData) {
        $targetBlog = $this->blog->where('id', '=', $inputData['id'])->first();
        $targetBlog->title = $inputData['title'];
        $targetBlog->contents = $inputData['contents'];
        $targetBlog->created_by = $inputData['user_id'];
        $result = $this->blog->save();
        return $result;
    }

    /**
     * ブログ削除
     * @param $id
     * @return $result
     */
    public function deleteBlog($id) {
        $targetBlog = $this->blog->where('id', '=', $id)->first();
        $result = $targetBlog->delete();
        return $result;
    }

    /**
     * ブログ全件取得
     * @return $allBlogs
     */
    public function getAllBlogs() {
        $allblogs = $this->blog->select(
            'articles.id',
            'articles.title',
            'articles.contents',
            'users.name',
            'articles.created_at',
        )
        ->join('users', 'users.id', '=', 'articles.created_by')
        ->orderByDesc('articles.created_at')
        ->get();

        return $allblogs;
        // return !empty($allblogs) ? $allblogs->toArray() : [];
    }

    /**
     * ユーザーIDに紐づくブログからブログIDをもとに１件取得
     * @param $id
     * @return $blog
     */
    public function getBlogByUserId($id) {
        $blog = $this->blog
        ->join('users', 'users.id', '=', 'articles.created_by')
        ->where('articles.created_by', '=', $id)
        ->get();

        return $blog;
    }

    /**
     * ユーザーIDに紐づくブログを全件取得
     * @param $userId
     * @return $blogs
     */
    public function getBlogsByUserId($userId) {
        $blogs = $this->blog
        ->join('users', 'users.id', '=', 'articles.created_by')
        ->where('created_by', '=', $userId)
        ->get();

        return $blogs;
    }

    /**
     * ブログ詳細取得
     * @param $id
     * @return $blog
     */
    public function blogDetail($id) {
        $blog = $this->blog->select(
            'articles.id',
            'articles.title',
            'articles.contents',
            'users.name',
            'articles.created_by',
            'articles.created_at',
        )
        ->join('users', 'users.id', '=', 'articles.created_by')
        ->where('articles.id', '=', $id)
        ->get();

        return $blog;
    }

}
