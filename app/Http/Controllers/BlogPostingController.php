<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogPostingRequest;
use App\Const\BlogConst;
use Illuminate\Support\Facades\Auth;


use App\Models\Article;
use App\Repositories\BlogRepository;
use App\Services\BlogService;

class BlogPostingController extends Controller
{
    public $blogService;

    public function __construct() {
        // Modelのインスタンス化
        $blog = new Article;
        
        // Repositoryのインスタンス化
        $blogRepository = new BlogRepository($blog);

        // Serviceのインスタンス化
        $this->blogService = new BlogService($blogRepository);

    }
    
    /**
     * ブログ投稿フォーム
     */
    public function blogPostingForm() {
        return view('blog_posting_form');
    }

    /**
     * ブログ投稿
     * @param $request
     * @return view
     */
    public function postBlog(BlogPostingRequest $request) {
        $inputData = [
            'user_id' => $request['user_id'],
            'title' => $request['title'],
            'contents' => $request['contents'],
        ];

        $result = $this->blogService->postBlog($inputData);

        switch($result) {
            case BlogConst::FAIL_REGISTER_USER_AUTHENTICATION:
                return back()->with('error_post_blog', 'セッションが切れています');

            case BlogConst::NOT_FOUND_REGISTER_USER_ID:
                return back()->with('error_post_blog', '投稿できませんでした');

            case BlogConst::SUCCESS_BLOG_REGISTERATION:
                return redirect(route('toppage'))->with('success_post_blog', 'ブログを投稿しました');

            default:
                return back()->with('error_post_blog', '予期せぬエラーが発生しました');
        }

    }
}
