<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogPostingRequest;
use App\Const\BlogConst;
use Illuminate\Support\Facades\Auth;


use App\Models\Article;
use App\Repositories\BlogRepository;
use App\Services\BlogService;

class FavoriteUserBlogController extends Controller
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
     * ユーザーIDに紐づくお気に入り登録したユーザーのブログ全件取得
     * @param $request
     * @return view
     */
    public function getBlogPostedByFavoriteUserByUserId(Request $request) {
        $inputData = [
            'user_id' => $request['id'],
        ];
        $blogsPostedByFavoriteUser = $this->blogService->getBlogPostedByFavoriteUserByUserId($inputData['user_id']);

        return view('favorite_user_blogs', compact('blogsPostedByFavoriteUser'));
    }
}
