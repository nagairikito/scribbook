<?php

namespace App\Repositories;

use App\Const\AccountConst;
use Illuminate\Http\Request;
use App\Models\FavoriteBlog;


use function Laravel\Prompts\select;

class FavoriteBlogRepository extends Repository
{
    public $favoriteBlog;
    
    public function __construct() {
        // Modelのインスタンス化
        $this->favoriteBlog = new FavoriteBlog;

    }

    /**
     * 対象のユーザーIDに紐づくお気に入り登録されたブログを全件取得
     * @param $id
     * @return $favoriteBlogs
     */
    public function getAllFavoriteBlogsByUserId($id) {
        $favoriteBlogs = $this->favoriteBlog
        ->join('articles', 'articles.id', '=', 'favorite_blogs.blog_id')
        ->join('users', 'users.id', '=', 'favorite_blogs.user_id')
        ->where('users.delete_flag', '=', AccountConst::USER_DELETE_FLAG_OFF)
        ->where('favorite_blogs.user_id', '=', $id)
        ->orderByDesc('favorite_blogs.created_at')
        ->select(
            'articles.*',
            'users.name'
        )
        ->get();

        return !empty($favoriteBlogs) ? $favoriteBlogs->toArray() : [];
    }
    
    /**
     * ユーザーIDとブログIDをもとに対象のブログがお気に入り登録されているかを判定
     * @param $inputData
     * @return $result
     */
    public function checkExsitsFavoriteBlogByBlogIdAndUserId($inputData) {
        $result = $this->favoriteBlog
        ->where('user_id', '=', $inputData['user_id'])
        ->where('blog_id', '=', $inputData['blog_id'])
        ->exists();

        return $result;
    }

    /**
     * ブログお気に入り登録
     * @param $inputData
     * @return boolean $result
     */
    public function registerFavoriteBlog($inputData) {
        $this->favoriteBlog->user_id = $inputData['user_id'];
        $this->favoriteBlog->blog_id = $inputData['blog_id'];
        $result = $this->favoriteBlog->save();

        return $result;
    }

    /**
     * ブログお気に入り登録解除
     * @param $inputData
     * @return boolean $result
     */
    public function deleteFavoriteBlog($inputData) {
        $result = $this->favoriteBlog
        ->where('user_id', '=', $inputData['user_id'])
        ->where('blog_id', '=', $inputData['blog_id'])
        ->delete();

        return $result;
    }

}
