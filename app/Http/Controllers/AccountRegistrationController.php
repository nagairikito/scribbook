<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AccountRegisterationRequest;
use App\Http\Requests\AccountUpdatingRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\FavoriteUser;
use App\Models\Article;
use App\Repositories\AccountRepository;
use App\Repositories\FavoriteUserRepository;
use App\Repositories\BlogRepository;
use App\Services\AccountService;
use App\Services\BlogService;

use App\Const\AccountConst;
use Illuminate\Support\Facades\Auth;


class AccountRegistrationController extends Controller
{
    public $accountService;
    public $blogService;
    public function __construct() {
        // Modelのインスタンス化
        $user = new User;
        $favoriteUser = new FavoriteUser;
        $blog = new Article;

        // Repositryのインスタンス化
        $userRepository = new AccountRepository($user);
        $favoriteUserRepository = new FavoriteUserRepository($favoriteUser);
        $blogRepository = new BlogRepository($blog);

        // Serviceのインスタンス化
        $this->accountService = new AccountService($userRepository, $favoriteUserRepository);
        $this->blogService = new BlogService($blogRepository);
        
    }

    /**
     * アカウント新規登録フォーム
     * @return view
     */
    public function accountRegisterationForm() {
        return view('account_registeration_form');
    }

    /**
     * アカウント新規登録
     * @param $request
     * @return view
     */
    public function registerAccount(AccountRegisterationRequest $request) {
        $inputData = [
            'name'      => $request->input('name'),
            'login_id'  => $request->input('login_id'),
            'password'  => $request->input('password'),
        ];
        $result = $this->accountService->registerAccount($inputData);

        if($result == AccountConst::FAIL_ACCOUNT_REGISTERATION) {
            return back()->with('error_account_registeraion','アカウント作成に失敗しました');
        }
        return view('success_account_registeration');
    }
}
