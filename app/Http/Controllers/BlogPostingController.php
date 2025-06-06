<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogPostingRequest;
use App\Services\BlogService;

class BlogPostingController extends Controller
{
    public $blogService;

    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService;
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
            'blog_unique_id' => $request['blog_unique_id'],
            'title' => $request['title'],
            'contents' => $request['contents'],
            'image_file_names' => $request['image_file_name'],
            'base64_texts' => $request['base64_text'],
        ];

        $result = $this->blogService->postBlog($inputData);

        if($result) {
            return redirect(route('toppage'))->with('success_post_blog', 'ブログを投稿しました');
        }
        return back()->with('error_post_blog', '予期せぬエラーが発生しました');

    }
}
