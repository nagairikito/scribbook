<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
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
     * @param　BlogRequest $request
     * @return view
     */
    public function postBlog(BlogRequest $request) {
        $inputData = [
            'user_id' => $request['user_id'],
            'blog_unique_id' => $request['blog_unique_id'],
            'thumbnail_name' => $request['thumbnail_name'],
            'thumbnail_img' => $request['thumbnail_img'],
            'title' => $request['title'],
            'contents' => $request['contents'],
            'image_file_names' => $request['image_file_name'],
            'base64_texts' => $request['base64_text'],
        ];

        $result = $this->blogService->postBlog($inputData);

        if($result) {
            return redirect(route('toppage'))->with('success_post_blog', 'ブログを投稿しました');
        }
        return back()->with('error_post_blog', 'エラーが発生しました')->withInput();

    }
}
