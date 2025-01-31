<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Repositories\AccountRepository;
use App\Repositories\BlogRepository;
use App\Repositories\SearchRepository;
use App\Services\AccountService;
use App\Services\BlogService;
use App\Services\SearchService;

class SearchController extends Controller
{
    public $accountService;
    public $blogService;
    public $searchService;
    public function __construct(SearchRepository $searchRepository) {
        // Modelのインスタンス化
        $user = new User;
        $blog = new Article;

        // Repositoryのインスタンス化
        $accountRepository = new AccountRepository($user);
        $blogRepository = new BlogRepository($blog);
        $searchRepository = new SearchRepository($user, $blog);

        // Serviceのインスタンス化
        $accountService = new AccountService($accountRepository);
        $blogService = new BlogService($blogRepository);
        
        $this->searchService = new SearchService($searchRepository);
    }
    
    /**
     * 検索結果初期表示
     * @param $request
     * @return $result
     */
    public function search(Request $request) {
        $inputData = [
            'keyword' => $request['keyword']
        ];

        $result = $this->searchService->search($inputData);
        return view('Search/search', ['result' => $result]);
    }
}
