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
        $result = $this->blog->where('id', '=', $inputData['id'])->update([
            'title'         => $inputData['title'],
            'contents'      => $inputData['contents'],
            'created_by'    => $inputData['created_by'],
        ]);
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
            'articles.created_by',
            'users.name',
            'articles.created_at',
        )
        ->join('users', 'users.id', '=', 'articles.created_by')
        ->orderByDesc('articles.created_at')
        ->get();

        return !empty($allblogs) ? $allblogs->toArray() : [];
        // return !empty($allblogs) ? $allblogs->toArray() : [];
    }

    /**
     * トピックス取得
     * @return $allBlogs
     */
    public function getTopics() {
        $allblogs = $this->blog->select(
            'articles.id',
            'articles.title',
            'articles.contents',
            'articles.created_by',
            'users.name',
            'articles.created_at',
        )
        ->join('users', 'users.id', '=', 'articles.created_by')
        ->orderByDesc('articles.view_count')
        ->limit(10)
        ->get();

        return !empty($allblogs) ? $allblogs->toArray() : [];
        // return !empty($allblogs) ? $allblogs->toArray() : [];
    }

    /**
     * ユーザーIDに紐づくブログIDをもとに１件取得
     * @param $id
     * @return $blog
     */
    public function getBlogByUserId($id) {
        $blog = $this->blog
        ->join('users', 'users.id', '=', 'articles.created_by')
        ->where('articles.id', '=', $id)
        ->select(
            'articles.*',
            'users.name',
        )
        ->get();

        return !empty($blog) ? $blog->toArray() : [];
    }

    /**
     * ユーザーIDに紐づくお気に入り登録したユーザーのブログ全件取得
     * @param $id
     * @return $blogs
     */
    public function getBlogPostedByFavoriteUserByUserId($id) {
        $blogs = $this->blog
        ->join('favorite_users', 'favorite_user_id', '=', 'articles.created_by')
        ->join('users', 'users.id', '=', 'favorite_users.favorite_user_id')
        ->where('favorite_users.user_id', '=', $id)
        ->where('users.delete_flag', '=', AccountConst::USER_DELETE_FLAG_OFF)
        ->select(
            'articles.*',
            'users.name',
            'users.icon_image',
        )
        ->get();

        $blogs = $blogs->sortByDesc('articles.updated_at');

        return !empty($blogs) ? $blogs->toArray() : [];
    }

    /**
     * ユーザーIDに紐づくブログを全件取得
     * @param $userId
     * @return $blogs
     */
    public function getBlogsByUserId($userId) {
        $blogs = $this->blog
        ->join('users', 'users.id', '=', 'articles.created_by')
        ->where('users.delete_flag', '=', AccountConst::USER_DELETE_FLAG_OFF)
        ->where('created_by', '=', $userId)
        ->select([
            'articles.id',
            'articles.title',
            'articles.contents',
            'articles.created_by',
            'articles.updated_at',
            'users.name',
        ])
        ->get();

        return !empty($blogs) ? $blogs->toArray() : [];
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
            'articles.created_by',
            'articles.view_count',
            'articles.updated_at',
            'users.name',
        )
        ->join('users', 'users.id', '=', 'articles.created_by')
        ->where('users.delete_flag', '=', AccountConst::USER_DELETE_FLAG_OFF)
        ->where('articles.id', '=', $id)
        ->get();

        return !empty($blog) ? $blog->toArray() : [];
    }

    /**
     * ブログ閲覧数増加
     */
    public function increaseViewCount($id) {
        $targetBlog = $this->blog->where('id', '=', $id)->first();
        $targetBlog->view_count += 1;
        $targetBlog->timestamps = false;
        $result = $targetBlog->save();

        return $result;
    }

}
