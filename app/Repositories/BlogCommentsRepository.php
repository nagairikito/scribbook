<?php

namespace App\Repositories;

use App\Const\AccountConst;
use Illuminate\Http\Request;
use App\Models\ArticleComment;

use function Laravel\Prompts\select;

class BlogCommentsRepository extends Repository
{
    public $blogComments;
    
    public function __construct() {
        // Modelのインスタンス化
        $this->blogComments = new ArticleComment;

    }
    
    /**
     * ブログコメント登録
     * @param $inputData
     * @return $result
     */
    public function postComment($inputData) {
        $this->blogComments->target_article = $inputData['target_article'];
        $this->blogComments->comment = $inputData['comment'];
        $this->blogComments->created_by = $inputData['created_by'];
        $result = $this->blogComments->save();

        return $result;
    }

    /**
     * ブログに紐づくコメント取得
     * @param $id
     * @return $comments
     */
    public function getBlogComments($id) {
        $comments = $this->blogComments
        ->join('users', 'users.id', '=', 'article_comments.created_by')
        ->where('article_comments.target_article', '=', $id)
        ->where('users.delete_flag', '=', AccountConst::USER_DELETE_FLAG_OFF)
        ->orderByDesc('article_comments.created_at')
        ->select([
            'article_comments.target_article',
            'article_comments.comment',
            'article_comments.created_at',
            'article_comments.created_by',
            'users.name',
            'users.icon_image',
        ])
        ->get();

        return $comments;
    }


}
