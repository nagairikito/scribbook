<?php

namespace App\Repositories;

use App\Const\AccountConst;
use Illuminate\Http\Request;
use App\Models\BlogComment;

use function Laravel\Prompts\select;

class blogCommentRepository extends Repository
{
    public $blogComment;
    
    public function __construct(BlogComment $blogComment) {
        // Modelのインスタンス化
        $this->blogComment = $blogComment;
    }
    
    /**
     * ブログコメント登録
     * @param array $inputData
     * @return bool $result
     */
    public function postComment($inputData) {
        $this->blogComment->blog_id = $inputData['blog_id'];
        $this->blogComment->comment = $inputData['comment'];
        $this->blogComment->created_by = $inputData['created_by'];
        $this->blogComment->updated_by = $inputData['updated_by'];
        $result = $this->blogComment->save();

        return $result;
    }

    /**
     * ブログに紐づくコメント取得
     * @param int $id
     * @return array $comments
     */
    public function getblogComments($id) {
        $comments = $this->blogComment
        ->join('m_users', 'm_users.id', '=', 't_blog_comments.created_by')
        ->where('t_blog_comments.blog_id', $id)
        ->where('m_users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
        ->orderByDesc('t_blog_comments.created_at')
        ->select([
            't_blog_comments.blog_id',
            't_blog_comments.comment',
            't_blog_comments.created_at',
            't_blog_comments.created_by',
            'm_users.name',
            'm_users.icon_image',
        ])
        ->get();

        return $comments;
    }
}
