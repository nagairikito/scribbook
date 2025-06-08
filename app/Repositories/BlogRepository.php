<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Const\AccountConst;
use App\Models\Blog;

class BlogRepository extends Repository
{
    public $blog;
    
    public function __construct(Blog $blog) {
        $this->blog = $blog;
    }

    /**
     * ブログの存在確認
     */
    public function checkExistsTargetBlog($id) {
        $result = $this->blog->first($id);
        return $result;
    }

    
    /**
     * ブログ登録
     * @param array $inputData
     * @return boolean $result
     */
    public function postBlog($inputData) {
        $this->blog->blog_unique_id = $inputData['blog_unique_id'];
        $this->blog->title = $inputData['title'];
        $this->blog->contents = $inputData['contents'];
        $this->blog->created_by = $inputData['user_id'];
        $this->blog->updated_by = $inputData['user_id'];
        $result = $this->blog->save();
        return $result;
    }

    /**
     * ブログ編集
     * @param array $inputData
     * @return bool $result
     */
    public function editBlog($inputData) {
        $result = $this->blog->where('id', $inputData['id'])->update([
            'blog_unique_id' => $inputData['blog_unique_id'],
            'title'          => $inputData['title'],
            'contents'       => $inputData['contents'],
            'updated_by'     => $inputData['created_by'],
            'updated_at'     => now(),
        ]);
        return $result;
    }

    /**
     * ブログ削除
     * @param $id
     * @return $result
     */
    public function deleteBlog($id) {
        $targetBlog = $this->blog->where('id', $id)->first();
        $result = $targetBlog->delete();
        return $result;
    }

    /**
     * ブログ全件取得
     * @return $allBlogs
     */
    public function getAllBlogs() {
        $allblogs = $this->blog->select(
            't_blogs.id',
            't_blogs.title',
            't_blogs.contents',
            't_blogs.created_by',
            'm_users.name',
            't_blogs.created_at',
        )
        ->join('m_users', 'm_users.id', '=', 't_blogs.created_by')
        ->orderByDesc('t_blogs.created_at')
        ->get();

        return !empty($allblogs) ? $allblogs->toArray() : [];
    }

    /**
     * トピックス取得
     * @return array $topics
     */
    public function getTopics() {
        $topics = $this->blog->select(
            't_blogs.id',
            't_blogs.title',
            't_blogs.contents',
            't_blogs.created_by',
            'm_users.name',
            't_blogs.created_at',
        )
        ->join('m_users', 'm_users.id', '=', 't_blogs.created_by')
        ->orderByDesc('t_blogs.view_count')
        ->limit(10)
        ->get();

        return !empty($topics) ? $topics->toArray() : [];
    }

    /**
     * ブログIDをもとにユーザーIDに紐づくブログを単一取得
     * @param int $id
     * @return array $blog
     */
    public function getBlogWithUserById($id) {
        $blog = $this->blog
        ->join('m_users', 'm_users.id', '=', 't_blogs.created_by')
        ->where('t_blogs.id', $id)
        ->select(
            't_blogs.*',
            'm_users.name',
        )
        ->first();

        return !empty($blog) ? $blog->toArray() : [];
    }

    /**
     * ユーザーIDをもとにお気に入り登録したユーザーのブログ全件取得
     * @param int $id
     * @return array $blogs
     */
    public function getBlogPostedByFavoriteUserByUserId($id) {
        $blogs = $this->blog
        ->join('t_favorite_users', 't_favorite_users.favorite_user_id', '=', 't_blogs.created_by')
        ->join('m_users', 'm_users.id', '=', 't_favorite_users.favorite_user_id')
        ->where('t_favorite_users.user_id', $id)
        ->where('m_users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
        ->select(
            't_blogs.*',
            'm_users.name',
            'm_users.icon_image',
        )
        ->get();

        $blogs = $blogs->sortByDesc('t_blogs.updated_at');

        return !empty($blogs) ? $blogs->toArray() : [];
    }

    /**
     * ユーザーIDに紐づくブログを全件取得
     * @param int $userId
     * @return array $blogs
     */
    public function getBlogsByUserId($userId) {
        $blogs = $this->blog
        ->join('m_users', 'm_users.id', '=', 't_blogs.created_by')
        ->where('m_users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
        ->where('t_blogs.created_by', $userId)
        ->select([
            't_blogs.id',
            't_blogs.title',
            't_blogs.contents',
            't_blogs.created_by',
            't_blogs.updated_at',
            'm_users.name',
        ])
        ->orderByDesc('t_blogs.id')
        ->get();

        return !empty($blogs) ? $blogs->toArray() : [];
    }

    /**
     * ブログ詳細取得
     * @param int $id
     * @return array $blog
     */
    public function getBlogDetail($id) {
        $blog = $this->blog->select(
            't_blogs.id',
            't_blogs.blog_unique_id',
            't_blogs.title',
            't_blogs.contents',
            't_blogs.created_by',
            't_blogs.view_count',
            't_blogs.updated_at',
            'm_users.name',
        )
        ->join('m_users', 'm_users.id', '=', 't_blogs.created_by')
        ->where('m_users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
        ->where('t_blogs.id', $id)
        ->first();

        return !empty($blog) ? $blog->toArray() : [];
    }

    /**
     * ブログ閲覧数増加
     * @param int $id
     * @return void
     */
    public function increaseViewCount($id) {
        $targetBlog = $this->blog->where('id', $id)->first();
        $targetBlog->view_count += 1;
        $targetBlog->timestamps = false;
        $result = $targetBlog->save();
    }

}
