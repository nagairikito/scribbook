<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Services\AccountService;
use App\Services\BlogService;
use App\Const\AccountConst;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public $accountService;
    public $blogService;

    public function __construct(AccountService $accountService, BlogService $blogService) {
        $this->accountService = $accountService;
        $this->blogService = $blogService;      
    }

    /**
     * ログインフォーム
     * @return view
     */
    public function loginForm() {
        return view('login_form');
    }

    /**
     * ログイン
     * @param LoginRequest $request
     * @return view|object
     */
    public function login(LoginRequest $request) {
        if(Auth::user()) {
            return back()->with('error_login', '既にログイン情報が存在しています。一度ログアウトしてください');
        }

        $inputData = [
            'login_id' => $request->input('login_id'),
            'password' => $request->input('password'),
        ];

        $result = $this->accountService->login($inputData);
        if($result) {
            return redirect(route('toppage'))->with('success_login', 'ログインしました');
        }
        return back()->with('error_login', 'エラーが発生しました')->withInput();
    }

    /**
     * ログアウト
     * 
     * @param $request
     * @return view
     */
    public function logout(Request $request) {
        $inputData = [
            'id' => $request['id'],
            'session' => $request->session(),
        ];

        $result = $this->accountService->logout($inputData);

        if($result == true) {
            return redirect(route('toppage'))->with('success_logout', 'ログアウトしました');
        }
        return back()->with('error_logout', 'セッションが切れています');
    }
}
