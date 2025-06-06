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
     * @param $request
     * @return view|object
     */
    public function login(LoginRequest $request, Response $response) {
        if(Auth::user()) {
            return back()->with('error_login', '既にログイン情報が存在しています。一度ログアウトしてください');
        }

        $inputData = [
            'login_id' => $request->input('login_id'),
            'password' => $request->input('password'),
        ];

        $loginStatus = $this->accountService->login($inputData);

        switch($loginStatus) {
            case AccountConst::NOT_FOUND_LOGIN_ID:
                return back()->with('error_login', 'ログインIDが存在しないまたは入力された内容が正しくありません')->withInput($inputData);
            
            case AccountConst::NOT_MATCH_LOGIN_PASSWORD:
                return back()->with('error_login', '入力されたパスワードが正しくありません')->withInput($inputData);

            case AccountConst::SUCCESS_LOGIN:
                return redirect(route('toppage'))->with('success_login', 'ログインしました');
            
            default:
                return back()->with('error_login', '予期せぬエラーが発生しました')->withInput($inputData);
        }
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
