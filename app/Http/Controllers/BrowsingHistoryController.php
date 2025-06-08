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
     * @param array $request
     * @return view
     */
    public function showBrowsingHistory(Request $request) {
        $inputData = [
            'user_id' => $request['id'],
        ];

        $browsingHistory = $this->blogService->getBrowsingHistory($inputData);

        return view('browsing_history', ['blogs' => $browsingHistory]);
    }
}
