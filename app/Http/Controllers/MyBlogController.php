<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlogService;

class MyBlogController extends Controller
{
    public $blogService;

    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService;
    }

    /**
     * マイブログ表示
     * @param array $request
     * @return view
     */
    public function showMYBlogs(Request $request) {
        $inputData = [
            'user_id' => $request['id']
        ];

        $myBlogs = $this->blogService->getBlogsByUserId($inputData['user_id']);

        return view('my_blogs', ['blogs' => $myBlogs]);

    }
}
