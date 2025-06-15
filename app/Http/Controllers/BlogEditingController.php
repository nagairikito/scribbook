<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\BlogService;

class BlogEditingController extends Controller
{
    public $blogService;

    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService;
    }

    /**
     * ブログ編集フォーム
     * @param array $request
     * @return view
     */
    public function blogEditingForm(Request $request) {
        $inputData = [
            'id' => $request['blog_id'],
            'user_id' => $request['login_user_id'],
        ];

        $blog = $this->blogService->getBlogByBlogIdAndUserId($inputData);

        if(empty($blog)) {
            return redirect(route('toppage'))->with('error_get_blog_detail', '予期せぬエラーが発生しました');
        }

        return view('blog_editing_form', ['blog' => $blog]);
    }

    /**
     * ブログ編集
     * @param BlogRequest $request
     * @return view
     */
    public function editBlog(BlogRequest $request) {
        $inputData = [
            'id' => $request['blog_id'],
            'blog_unique_id' => $request['blog_unique_id'],
            'thumbnail_name' => $request['thumbnail_name'] != null ? $request['thumbnail_name'] : 'noImage.png',
            'thumbnail_img' => $request['thumbnail_img'],
            'title' => $request['title'],
            'contents' => $request['contents'],
            'created_by' => $request['login_user_id'],
            'image_file_names' => array_key_exists('image_file_name', $request->toArray()) ? $request['image_file_name'] : null ,
            'base64_texts' => array_key_exists('base64_text', $request->toArray()) ? $request['base64_text'] : null ,
        ];

        $result = $this->blogService->editBlog($inputData);

        if($result) {
            return redirect(route('profile_top', ['id' => Auth::id()]))->with('success_edit_blog', 'ブログを更新しました');
        }
        return back()->with('error_edit_blog', '予期せぬエラーが発生しました');
    }
}
