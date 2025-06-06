<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlogService;

class FavoriteBlogController extends Controller
{
    public $blogService;

    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService;
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
