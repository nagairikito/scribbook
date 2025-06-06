<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AccountRegisterationRequest;
use App\Services\AccountService;
use App\Services\BlogService;
use App\Const\AccountConst;

class AccountRegistrationController extends Controller
{
    public $accountService;
    public $blogService;

    public function __construct(AccountService $accountService, BlogService $blogService) {
        $this->accountService = $accountService;
        $this->blogService = $blogService;    
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
