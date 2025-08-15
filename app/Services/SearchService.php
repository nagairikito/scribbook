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
    public const LOCAL_STORAGE_PATH = 'storage/user_icon_images/%s';
    public const NOIMAGE_PATH = 'commonImages/user_icon_images/noImage.png';

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

        $processedUsers = array_map(function($user) {
            if($user['icon_image'] !== 'noImage.png') {
                if(!app()->environment('production')) {
                    $user['icon_image'] = asset(sprintf($this::LOCAL_STORAGE_PATH, $user['icon_image']));
                }
            } else {
                $user['icon_image'] = asset($this::NOIMAGE_PATH);
            }
            return $user;
        }, $result['users']);
        $result['users'] = $processedUsers;

        return $result;
    }

}
