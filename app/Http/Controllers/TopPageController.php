<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;
use App\Services\BlogService;

class TopPageController extends Controller
{
    public $accountService;
    public $blogService;

    public function __construct(AccountService $accountService, BlogService $blogService) {
        $this->accountService = $accountService;
        $this->blogService = $blogService;      
    }
    
    /**
     * トップページ初期表示
     * @return view
     */
    public function index() {
        $allBlogs = $this->blogService->getAllBlogs();
        return view('toppage', ['blogs' => $allBlogs]);
    }
}
