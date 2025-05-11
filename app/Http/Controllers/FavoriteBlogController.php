<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogPostingRequest;
use App\Const\BlogConst;
use Illuminate\Support\Facades\Auth;


use App\Models\Article;
use App\Repositories\BlogRepository;
use App\Services\BlogService;

class FavoriteBlogController extends Controller
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
     * お気に入りブログ一覧を表示
     * @param $id
     * @return $favoriteBlogs
     */
    public function getFavoriteBlogs(Request $request) {
        $inputData = [
            'id' => $request['id'],
        ];

        $favoriteBlogs = $this->blogService->getAllFavoriteBlogsByUserId($inputData['id']);

        return view('favorite_blogs', ['favoriteBlogs' => $favoriteBlogs]);
    }
}
