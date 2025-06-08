<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlogService;

class TopicsController extends Controller
{
    public $blogService;

    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService;
    }

    /**
     * トピックス表示
     * @return view
     */
    public function topics() {
        $topics = $this->blogService->getTopics();

        return view('topics', ['blogs' => $topics]);
    }
}
