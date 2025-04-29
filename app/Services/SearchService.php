<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Repositories\AccountRepository;
use App\Repositories\BlogRepository;
use App\Repositories\SearchRepository;

use App\Const\AccountConst;
use Illuminate\Support\Facades\Auth;

class SearchService extends Service
{
    // public $accountRepository;
    // public $blogRepository;
    // public $searchRepository;

    public $accountRepository;
    public $blogRepository;
    public $searchRepository;

    public function __construct() {

        // Modelのインスタンス化
        $user = new User;
        $blog = new Article;

        // Repositryのインスタンス化
        $this->accountRepository = new AccountRepository($user);
        $this->blogRepository = new BlogRepository($blog);
        $this->searchRepository = new SearchRepository($user, $blog);
    }
    
    /**
     * 検索結果
     * @param $inputData
     * @return bool $result
     */
    public function search($inputData) {
        $result = $this->searchRepository->search($inputData);
        return $result;
    }

}
