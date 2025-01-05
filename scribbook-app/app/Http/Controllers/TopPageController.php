<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Repositories\AccountRepository;
use App\Repositories\BlogRepository;
use App\Services\AccountService;
use App\Services\BlogService;

class TopPageController extends Controller
{
    public $accountService;
    public $blogService;
    public function __construct() {
        // Modelのインスタンス化
        $user = new User;
        $blog = new Article;

        // Repositoryのインスタンス化
        $accountRepository = new AccountRepository($user);
        $blogRepository = new BlogRepository($user);

        // Serviceのインスタンス化
        $this->accountService = new AccountService($user);
        $this->blogService = new BlogService($user);
    }
    
    /**
     * トップページ初期表示
     */
    public function index() {
        $allBlogs = $this->blogService->getAllBlogs();
        return view('TopPage/toppage', compact('allBlogs'));
    }
}
