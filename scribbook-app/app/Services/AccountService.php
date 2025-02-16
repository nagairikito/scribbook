<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\FavoriteUser;
use App\Repositories\AccountRepository;
use App\Repositories\FavoriteUserRepository;

use App\Const\AccountConst;
use Illuminate\Support\Facades\Auth;

class AccountService extends Service
{
    public $accountRepository;
    public $favoriteUserRepository;

    public function __construct() {

        // Modelのインスタンス化
        $user = new User;
        $favoriteUser = new FavoriteUser;

        // Repositryのインスタンス化
        $this->accountRepository = new AccountRepository($user);
        $this->favoriteUserRepository = new FavoriteUserRepository($favoriteUser);

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
     * @return $inputData
     */
    public function logout($inputData) {
        if(!Auth::user() || Auth::id() != $inputData['id']) {
            return false;
        }


        Auth::logout();
        if(session() != null) {
            $inputData['session']->invalidate(); // セッションを削除
            session()->regenerateToken(); // セッションの再作成    
        }

        return true;

    }

    /**
     * idをもとにアカウント情報を1件取得
     * @param $id
     * @return $targetAccount
     */
    public function getAccountById($id) {
        $checkExistsUser = $this->accountRepository->existsAccountById($id);
        if(!$checkExistsUser) {
            // Auth::logout();
            return [];
        }

        $targetAccount = $this->accountRepository->getAccountById($id);
        $targetAccount[0]['favorite_users'] = [];
        $targetAccount[0]['favorite_flag'] = false;
        if(Auth::user() && Auth::id() == $id) {
            $favoriteUsers = $this->favoriteUserRepository->getFavoriteUsersByUserId($id);
            $targetAccount[0]['favorite_users'] = $favoriteUsers;    
        }
        if(Auth::user() && Auth::id() != $id) {
            $checkFavoriteFlag = $this->favoriteUserRepository->checkFavorite(Auth::id(), $id);
            if($checkFavoriteFlag == true) {
                $targetAccount[0]['favorite_flag'] = true;
            }
        }

        return $targetAccount;
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

        if($inputData['icon_image_file'] || $inputData['icon_image_file'] != null || $inputData['icon_image'] != $targetAccount[0]['icon_image']) {
            $image_name = $this->upsertUserIconImageIntoStorage($inputData['icon_image_file'], $targetAccount[0]['icon_image']);
            $inputData['icon_image'] = $image_name;
        }
        $checkUpdate = $this->accountRepository->updateProfile($inputData);
        if($checkUpdate) {
            $updateStatus = AccountConst::SUCCESS_ACCOUNT_UPDATING;
        }

        return $updateStatus;

    }

    /**
     * ユーザーアイコン更新,Storageに保存
     * @param $iconImageFile
     * @return $result
     */
    public function upsertUserIconImageIntoStorage($iconImageFile, $oldImageName) {
        if($oldImageName && $oldImageName != null) {
            $this->deleteIconImageFromStorage($oldImageName);
        }

        $newImageName = 'noImage.png';
        if($iconImageFile && $iconImageFile!= null) {
            $originalName = $iconImageFile->file('icon_image_file')->getClientOriginalName();
            $newImageName = date('Ymd_His') . '_' . $originalName;
    
            Storage::disk('public')->putFileAs('user_icon_images', $iconImageFile->file('icon_image_file'), $newImageName);    
        }
        return $newImageName;
    }

    /**
     * ユーザーアイコン削除,Storageから削除
     * @param $iconImageFile
     * @return $result
     */
    public function deleteIconImageFromStorage($targetImageName) {
        if($targetImageName != null && $targetImageName != 'noImage.png') {
            $targetImageNameExists = Storage::disk('public')->exists('user_icon_images/'. $targetImageName);
            if($targetImageNameExists) {
                Storage::disk('public')->delete('user_icon_images/'. $targetImageName);
            }
        }

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

        $this->deleteIconImageFromStorage($targetAccount[0]['icon_image']);
        $checkDelete = $this->accountRepository->deleteAccount($inputData);
        if($checkDelete) {
            $this->logout($inputData);
            $deleteStatus = AccountConst::SUCCESS_ACCOUNT_DELETING;
        }

        return $deleteStatus;
    }

    /**
     * ユーザーお気に入り登録
     * @param $inputData
     * @return $result
     */
    public function registerFavoriteUser($inputData) {
        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return false;
        }

        $loginUser = $this->accountRepository->existsAccountById($inputData['user_id']);
        if(!$loginUser) {
            Auth::logout();
            return false;
        }

        $targetAccount = $this->accountRepository->existsAccountById($inputData['favorite_user_id']);
        if(!$targetAccount) {
            return false;
        }

        $result = $this->favoriteUserRepository->registerFavoriteUser($inputData);
        if($result == true) {
            return true;
        }
        return false;
    }

    /**
     * ユーザーお気に入り登録解除
     * @param $inputData
     * @return $result
     */
    public function deleteFavoriteUser($inputData) {
        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return false;
        }

        $loginUser = $this->accountRepository->existsAccountById($inputData['user_id']);
        if(!$loginUser) {
            Auth::logout();
            return false;
        }

        $result = $this->favoriteUserRepository->deleteFavoriteUser($inputData);
        if($result == true) {
            return true;
        }
        return false;
    }

}
