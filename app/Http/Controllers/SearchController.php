<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Repositories\FavoriteUserRepository;
use App\Services\AccountService;
use App\Services\BlogService;
use App\Services\SearchService;

class SearchController extends Controller
{
    public $accountService;
    public $blogService;
    public $searchService;
    public $favoriteUserRepository;

    public function __construct(AccountService $accountService, BlogService $blogService, SearchService $searchService, FavoriteUserRepository $favoriteUserRepository) {
        $this->accountService = $accountService;
        $this->blogService = $blogService;
        $this->searchService = $searchService;
        $this->favoriteUserRepository = $favoriteUserRepository;
    }
    
    /**
     * 検索結果初期表示
     * @param SearchRequest $request
     * @return view
     */
    public function search(Request $request) {
        if(isset($request['keyword']) && empty($request['keyword'])) {
            return back();
        }
        $inputData = [
            'keyword' => $request['keyword']
        ];

        $result = $this->searchService->search($inputData);

        // $this->favoriteUserRepository->checkFavorite();
        $result['keyword'] = $inputData['keyword'];
        return view('search', compact(['result']));
    }
}
