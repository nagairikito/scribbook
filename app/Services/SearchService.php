<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\AccountRepository;
use App\Repositories\BlogRepository;
use App\Repositories\SearchRepository;

use App\Const\AccountConst;
use Illuminate\Support\Facades\Auth;

class SearchService extends Service
{
    public $accountRepository;
    public $blogRepository;
    public $searchRepository;

    public function __construct(AccountRepository $accountRepository, BlogRepository $blogRepository, SearchRepository $searchRepository) {
        $this->accountRepository = $accountRepository;
        $this->blogRepository = $blogRepository;
        $this->searchRepository = $searchRepository;
    }
    
    /**
     * 検索結果
     * @param array $inputData
     * @return array $result
     */
    public function search($inputData) {
        $result = $this->searchRepository->search($inputData);
        return $result;
    }

}
