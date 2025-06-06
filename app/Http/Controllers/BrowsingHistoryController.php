<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlogService;

class BrowsingHistoryController extends Controller
{
    public $blogService;

    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService;
    }

    /**
     * 閲覧履歴表示
     */
    public function showBrowsingHistory(Request $request) {
        $inputData = [
            'user_id' => $request['id'],
        ];

        $blogs = $this->blogService->showBrowsingHistory($inputData);

        return view('browsing_history', compact('blogs'));

    }
}
