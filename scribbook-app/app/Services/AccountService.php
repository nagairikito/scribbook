<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\AccountRepository;

use App\Const\AccountConst;
use Illuminate\Support\Facades\Auth;
use stdClass;

class AccountService extends Service
{
    public $accountRepository;
    public function __construct() {

        // Modelのインスタンス化
        $user = new User;

        // Repositryのインスタンス化
        $this->accountRepository = new AccountRepository($user);

    }
    
    /**
     * アカウント新規登録
     * @param $inputrequest
     * @return bool $result
     */
    public function registerAccount($inputData) {
        $result = $this->accountRepository->registerAccount($inputData);
        return $result;
    }

    /**
     * ログイン
     * @param $inputData
     * @return $loginStatus
     */
    public function login($inputData) {
        $loginStatus = AccountConst::LOGIN_INITIAL_VALUE;

        $loginUserInfo = $this->accountRepository->login($inputData['login_id']);
        if(empty($loginUserInfo)) {
            $loginStatus = AccountConst::NOT_FOUND_LOGIN_ID;
            return $loginStatus;
        }

        if(password_verify($inputData['password'], $loginUserInfo['password']) == false) {
            $loginStatus = AccountConst::NOT_MATCH_LOGIN_PASSWORD;
            return $loginStatus;
        }

        $inputData = collect($inputData);
        $credentials = $inputData->only('login_id', 'password');
        $credentials = $credentials->toArray();
        $loginUser = Auth::attempt($credentials);
        if($loginUser) {
            $loginStatus = AccountConst::SUCCESS_LOGIN;
        }

        return $loginStatus;
    }

    /**
     * ログアウト
     * @param $inputData
     * @return $result
     */
    public function logout($inputData) {
        if(!Auth::user() || Auth::id() != $inputData['id']) {
            return false;
        }
        // $objInputData = $this->toObject($inputData);
        Auth::logout();
        $inputData->session()->invalidate(); // セッションを削除
        $inputData->session()->regenerateToken(); // セッションの再作成

        return true;

    }

    /**
     * プロフィール更新
     * @param $inputData
     * @return $updateStatus
     */
    public function updateProfile($inputData) {
        $updateStatus = AccountConst::UPDATE_INITIAL_VALUE;

        if(!Auth::user() || Auth::id() != $inputData['id']) {
            $updateStatus = AccountConst::FAIL_UPDATE_USER_AUTHENTICATION;
            return $updateStatus;
        }

        $targetAccount = $this->accountRepository->getAccountById($inputData['id']);
        if(!$targetAccount) {
            $updateStatus = AccountConst::NOT_FOUND_UPDATE_USER_ID;
            return $updateStatus;
        }

        $checkUpdate = $this->accountRepository->updateProfile($inputData);
        if($checkUpdate) {
            $updateStatus = AccountConst::SUCCESS_ACCOUNT_UPDATING;
        }

        return $updateStatus;

    }

    /**
     * アカウント削除
     * @param $inputData
     * @return 
     */
    public function deleteAccount($inputData) {
        $deleteStatus = AccountConst::DELETE_INITIAL_VALUE;

        if(!Auth::user() || Auth::id() != $inputData['id']) {
            $deleteStatus = AccountConst::FAIL_DELETE_USER_AUTHENTICATION;
            return $deleteStatus;
        }

        $targetAccount = $this->accountRepository->getAccountById($inputData['id']);
        if(!$targetAccount) {
            $deleteStatus = AccountConst::NOT_FOUND_DELETE_USER_ID;
            return $deleteStatus;
        }

        $checkDelete = $this->accountRepository->deleteAccount($inputData);
        if($checkDelete) {
            // $this->logout($inputData);
            $deleteStatus = AccountConst::SUCCESS_ACCOUNT_DELETING;
        }

        return $deleteStatus;
    }


    function toObject($array) {
        $obj = new stdClass;
        foreach($array as $k => $v) {
            $obj->{$k} = $v;
        }
        return $obj;
    }
}
