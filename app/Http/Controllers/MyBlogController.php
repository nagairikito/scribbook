<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogPostingRequest;
use App\Const\BlogConst;
use Illuminate\Support\Facades\Auth;


use App\Models\Article;
use App\Repositories\BlogRepository;
use App\Services\BlogService;

class MyBlogController extends Controller
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
     * マイブログ表示
     */
    public function showMYBlogs(Request $request) {
        $inputData = [
            'user_id' => $request['id']
        ];

        $myBlogs = $this->blogService->getBlogsByUserId($inputData['user_id']);

        return view('my_blogs', compact('myBlogs'));

    }
}
