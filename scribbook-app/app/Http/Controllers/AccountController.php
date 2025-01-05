<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AccountRegisterationRequest;
use App\Http\Requests\AccountUpdatingRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Article;
use App\Repositories\AccountRepository;
use App\Repositories\BlogRepository;
use App\Services\AccountService;
use App\Services\BlogService;

use App\Const\AccountConst;
use Illuminate\Support\Facades\Auth;


class AccountController extends Controller
{
    public $accountService;
    public $blogService;
    public function __construct() {
        // Modelのインスタンス化
        $user = new User;
        $blog = new Article();

        // Repositryのインスタンス化
        $userRepository = new AccountRepository($user);
        $blogRepository = new BlogRepository($blog);

        // Serviceのインスタンス化
        $this->accountService = new AccountService($userRepository);
        $this->blogService = new BlogService($blogRepository);
        
    }

    /**
     * アカウント新規登録フォーム
     * @return view
     */
    public function accountRegisterationForm() {
        return view('Account/account_registeration_form');
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
        return view('Account/success_account_registeration');
    }

    /**
     * ログインフォーム
     * @return view
     */
    public function loginForm() {
        return view('Account/login_form');
    }

    /**
     * ログイン
     * @param $request
     * @return view|object
     */
    public function login(LoginRequest $request, Response $response) {
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
        $result = $this->accountService->logout($request);

        if($result == true) {
            return redirect(route('toppage'))->with('success_logout', 'ログアウトしました');
        }
        return back()->with('error_logout', 'セッションが切れています');
    }

    /**
     * プロフィール初期表示
     * @return view
     */
    public function profileTop() {
        $loginUserId = Auth::id();
        if(!Auth::user() || Auth::id() != $loginUserId) {
            return back()->with('error', 'セッションの期限が切れています');
        }

        $targetAccount = $this->accountService->accountRepository->getAccountById($loginUserId);
        if(!$targetAccount) {
            return back()->with('error','対象のアカウントが見つかりません');
        }

        $blogs = $this->blogService->getBlogsByUserId($targetAccount[0]['id']);
        return view('Account/profile_top', ['blogs' => $blogs]);
    }

    /**
     * プロフィール更新
     * @param object $request
     * @return view
     */
    public function updateProfile(AccountUpdatingRequest $request) {

        $inputData = [
            'id'             => $request['login_user_id'],
            'name'           => $request['name'],
            'login_id'       => $request['login_id'],
            'password'       => $request['password'],
            'icon_image'     => $request['icon_image'],
            'discription'    => $request['discription'],
        ];
        $result = $this->accountService->updateProfile($inputData);

        switch($result) {
            case AccountConst::FAIL_UPDATE_USER_AUTHENTICATION:
                return back()->with('error_update', 'セッションが切れています。再度ログインしなおしてください')->withInput($inputData);
            
            case AccountConst::NOT_FOUND_UPDATE_USER_ID:
                return back()->with('error_update', 'アカウント情報が見つかりません')->withInput($inputData);

            case AccountConst::SUCCESS_ACCOUNT_UPDATING:
                return redirect(route('profile_top'))->with('success_update', 'プロフィールを更新しました');
            
            default:
                return back()->with('error_update', '予期せぬエラーが発生しました')->withInput($inputData);
        }
    }

    /**
     * アカウント削除
     * @param $request
     * @return view
     */
    public function deleteAccount(Request $request) {
        $inputData = [
            'id' => $request['id']
        ];

        $result = $this->accountService->deleteAccount($inputData, $request);

        switch($result) {
            case AccountConst::FAIL_DELETE_USER_AUTHENTICATION:
                return back()->with('error_delete', 'セッションが切れています。再度ログインしなおしてください')->withInput($inputData);
            
            case AccountConst::NOT_FOUND_DELETE_USER_ID:
                return back()->with('error_delete', 'アカウント情報が見つかりません')->withInput($inputData);

            case AccountConst::SUCCESS_ACCOUNT_DELETING:
                $this->logout($request);
                return redirect(route('toppage'))->with('success_delete', 'アカウントを削除しました');
            
            default:
                return back()->with('error_delete', '予期せぬエラーが発生しました')->withInput($inputData);
        }

    }


}
