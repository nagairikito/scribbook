<?php

namespace App\Services;

use App\Const\AccountConst;
use App\Repositories\AccountRepository;
use App\Repositories\FavoriteUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AccountService extends Service
{
    public $accountRepository;
    public $favoriteUserRepository;

    public function __construct(AccountRepository $accountRepository, FavoriteUserRepository $favoriteUserRepository) {
        $this->accountRepository = $accountRepository;
        $this->favoriteUserRepository = $favoriteUserRepository;
    }

    /**
     * アカウント新規登録
     * @param $inputrequest
     * @return bool $result
     */
    public function registerAccount($inputData) {
        try {
            DB::beginTransaction();

            $result = $this->accountRepository->registerAccount($inputData);
            
            DB::commit();
            return $result;
            
        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

    /**
     * ログイン
     * @param $inputData
     * @return $loginStatus
     */
    public function login($inputData) {
        $loginStatus = AccountConst::LOGIN_INITIAL_VALUE;

        try {
            DB::beginTransaction();
    
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
    
            DB::commit();
            return $loginStatus;
            
        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

    /**
     * ログアウト
     * @param $inputData
     * @return $inputData
     */
    public function logout($inputData) {
        try {
            DB::beginTransaction();
    
            if(!Auth::user() || Auth::id() != $inputData['id']) {
                return false;
            }
    
            Auth::logout();
            if(session() != null) {
                $inputData['session']->invalidate(); // セッションを削除
                session()->regenerateToken(); // セッションの再作成    
            }
    
            DB::commit();
            return true;

        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
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

        try {
            DB::beginTransaction();
    
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
    
            DB::commit();
            return $updateStatus;

        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

    ///////////////////////////////////////////////////////////////     エラー発生時画像の削除
    /**
     * ユーザーアイコン更新,Storageに保存
     * @param $iconImageFile
     * @return $result
     */
    public function upsertUserIconImageIntoStorage($iconImageFile, $oldImageName) {
        try {
            DB::beginTransaction();
    
            if($oldImageName && $oldImageName != null) {
                $this->deleteIconImageFromStorage($oldImageName);
            }
    
            $newImageName = 'noImage.png';
            if($iconImageFile && $iconImageFile!= null) {
                $originalName = $iconImageFile->file('icon_image_file')->getClientOriginalName();
                $newImageName = date('Ymd_His') . '_' . $originalName;
        
                Storage::disk('public')->putFileAs('user_icon_images', $iconImageFile->file('icon_image_file'), $newImageName);    
            }
    
            DB::commit();
            return $newImageName;

        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

    /////////////////////////////////////////////////////////////////////// エラー時画像の復元
    /**
     * ユーザーアイコン削除,Storageから削除
     * @param $iconImageFile
     * @return $result
     */
    public function deleteIconImageFromStorage($targetImageName) {
        try {
            DB::beginTransaction();
    
            if($targetImageName != null && $targetImageName != 'noImage.png') {
                $targetImageNameExists = Storage::disk('public')->exists('user_icon_images/'. $targetImageName);
                if($targetImageNameExists) {
                    Storage::disk('public')->delete('user_icon_images/'. $targetImageName);
                }
            }
    
            DB::commit();
            
        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

    /**
     * アカウント削除
     * @param $inputData
     * @return 
     */
    public function deleteAccount($inputData) {
        $deleteStatus = AccountConst::DELETE_INITIAL_VALUE;

        try {
            DB::beginTransaction();
    
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
        
            DB::commit();
            return $deleteStatus;

        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

    /**
     * ユーザーお気に入り登録
     * @param $inputData
     * @return $result
     */
    public function registerFavoriteUser($inputData) {
        $resultFlag = false;

        try {
            DB::beginTransaction();
    
            if(!Auth::user() || Auth::id() != $inputData['user_id']) {
                $resultFlag =  false;
            }
    
            $loginUser = $this->accountRepository->existsAccountById($inputData['user_id']);
            if(!$loginUser) {
                Auth::logout();
                $resultFlag = false;
            }
    
            $targetAccount = $this->accountRepository->existsAccountById($inputData['favorite_user_id']);
            if(!$targetAccount) {
                $resultFlag = false;
            }
    
            $result = $this->favoriteUserRepository->registerFavoriteUser($inputData);
            if($result == true) {
                $resultFlag = true;
            }
    
            DB::commit();
            return $resultFlag;

        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

    /**
     * ユーザーお気に入り登録解除
     * @param $inputData
     * @return $result
     */
    public function deleteFavoriteUser($inputData) {
        $resultFlag = false;
        try {
            DB::beginTransaction();
    
            if(!Auth::user() || Auth::id() != $inputData['user_id']) {
                $resultFlag = false;
            }
    
            $loginUser = $this->accountRepository->existsAccountById($inputData['user_id']);
            if(!$loginUser) {
                Auth::logout();
                $resultFlag = false;
            }
    
            $result = $this->favoriteUserRepository->deleteFavoriteUser($inputData);
            if($result == true) {
                $resultFlag = true;
            }
    
            DB::commit();
            return $resultFlag;

        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

}
