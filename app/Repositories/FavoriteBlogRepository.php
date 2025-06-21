<?php

namespace App\Repositories;

use App\Const\AccountConst;
use Illuminate\Http\Request;
use App\Models\FavoriteBlog;


use function Laravel\Prompts\select;

class FavoriteBlogRepository extends Repository
{
    public $favoriteBlog;
    
    public function __construct(FavoriteBlog $favoriteBlog) {
        $this->favoriteBlog = $favoriteBlog;
    }

    /**
     * 対象のユーザーIDに紐づくお気に入り登録されたブログを全件取得
     * @param int $id
     * @return array $favoriteBlogs
     */
    public function getAllFavoriteBlogsByUserId($id) {
        $favoriteBlogs = $this->favoriteBlog
        ->join('t_blogs', 't_blogs.id', '=', 't_favorite_blogs.blog_id')
        ->join('m_users', 'm_users.id', '=', 't_blogs.created_by')
        ->where('m_users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
        ->where('t_favorite_blogs.user_id', $id)
        ->orderByDesc('t_favorite_blogs.created_at')
        ->select(
            't_blogs.*',
            'm_users.name',
            'm_users.icon_image'
        )
        ->get();

        return !empty($favoriteBlogs) ? $favoriteBlogs->toArray() : [];
    }
    
    /**
     * ユーザーIDとブログIDをもとに対象のブログがお気に入り登録されているかを判定
     * @param array $inputData
     * @return bool $result
     */
    public function checkExsitsFavoriteBlogByBlogIdAndUserId($inputData) {
        $result = $this->favoriteBlog
        ->where('user_id', $inputData['user_id'])
        ->where('blog_id', $inputData['blog_id'])
        ->exists();

        return $result;
    }

    /**
     * ブログお気に入り登録
     * @param array $inputData
     * @return bool $result
     */
    public function registerFavoriteBlog($inputData) {
        $this->favoriteBlog->user_id = $inputData['user_id'];
        $this->favoriteBlog->blog_id = $inputData['blog_id'];
        $result = $this->favoriteBlog->save();

        return $result;
    }

    /**
     * ブログお気に入り登録解除
     * @param array $inputData
     * @return bool $result
     */
    public function deleteFavoriteBlog($inputData) {
        $result = $this->favoriteBlog
        ->where('user_id', $inputData['user_id'])
        ->where('blog_id', $inputData['blog_id'])
        ->delete();

        return $result;
    }

}
