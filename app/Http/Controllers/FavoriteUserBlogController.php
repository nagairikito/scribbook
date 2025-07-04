<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlogService;

class FavoriteUserBlogController extends Controller
{
    public $blogService;

    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService;
    }

    /**
     * ユーザーIDをもとにお気に入り登録したユーザーのブログ全件取得
     * @param array $request
     * @return view
     */
    public function getBlogPostedByFavoriteUserByUserId(Request $request) {
        $inputData = [
            'user_id' => $request['id'],
        ];
        $blogsPostedByFavoriteUser = $this->blogService->getBlogPostedByFavoriteUserByUserId($inputData['user_id']);

        return view('favorite_user_blogs', ['blogs' => $blogsPostedByFavoriteUser]);
    }
}
