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


class LoginController extends Controller
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
