<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogPostingRequest;
use App\Const\BlogConst;
use Illuminate\Support\Facades\Auth;


use App\Models\Article;
use App\Repositories\BlogRepository;
use App\Services\BlogService;

class BlogEditingController extends Controller
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
     * ブログ編集フォーム
     * @param $request
     * @return view
     */
    public function blogEditingForm(Request $request) {
        $inputData = [
            'id' => $request['blog_id'],
            'user_id' => $request['login_user_id'],
        ];

        $blog = $this->blogService->getBlogByUserId($inputData);

        if(empty($blog)) {
            return redirect(route('toppage'))->with('error_get_blog_detail', '予期せぬエラーが発生しました');
        }

        return view('blog_editing_form', ['blog' => $blog]);
    }

    /**
     * ブログ編集
     * @param $request
     * @return view
     */
    public function editBlog(Request $request) {
        $inputData = [
            'id' => $request['blog_id'],
            'title' => $request['title'],
            'contents' => $request['contents'],
            'created_by' => $request['login_user_id'],
        ];

        $result = $this->blogService->editBlog($inputData);

        if($result) {
            return redirect(route('profile_top', ['id' => Auth::id()]))->with('success_edit_blog', 'ブログを更新しました');
        }
        return back()->with('error_edit_blog', '予期せぬエラーが発生しました');
    }
}
