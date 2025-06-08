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
     * @param array $inputData
     * @return bool $result
     */
    public function registerAccount($inputData) {
        DB::beginTransaction();
        try {
            $result = $this->accountRepository->registerAccount($inputData);
            
            DB::commit();
            return $result;
            
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    /**
     * ログイン
     * @param array $inputData
     * @return bool $loginFlag
     */
    public function login($inputData) {
        $loginFlag = false;

        $targetUser = $this->accountRepository->login($inputData['login_id']);
        if(empty($targetUser)) {
            return $loginFlag;
        }

        if(password_verify($inputData['password'], $targetUser['password']) == false) {
            return $loginFlag;
        }

        $collectInputData = collect($inputData);
        $credentials = $collectInputData->only('login_id', 'password');
        $newCredentials = $credentials->toArray();
        $loginUser = Auth::attempt($newCredentials);
        if($loginUser) {
            $loginFlag = true;
        }

        return $loginFlag;
    }

    /**
     * ログアウト
     * @param array|object $inputData
     * @return bool $inputData
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
     * @param int $id
     * @return array $targetAccount
     */
    public function getAccountById($id) {
        $checkExistsUser = $this->accountRepository->existsAccountById($id);
        if(!$checkExistsUser) {
            return [];
        }

        $targetAccount = $this->accountRepository->getAccountById($id);
        $targetAccount['favorite_users'] = [];
        $targetAccount['favorite_flag'] = false;
        if(Auth::user() && Auth::id() == $id) {
            $favoriteUsers = $this->favoriteUserRepository->getFavoriteUsersByUserId($id);
            $targetAccount['favorite_users'] = $favoriteUsers;    
        }
        if(Auth::user() && Auth::id() != $id) {
            $checkFavoriteFlag = $this->favoriteUserRepository->checkFavorite(Auth::id(), $id);
            if($checkFavoriteFlag == true) {
                $targetAccount['favorite_flag'] = true;
            }
        }

        return $targetAccount;
    }

    /**
     * プロフィール更新
     * @param array $inputData
     * @return bool $updateStatus
     */
    public function updateProfile($inputData) {
        $updateFlag = false;

        if(!Auth::user() || Auth::id() != $inputData['id']) {
            return $updateFlag;
        }

        $targetAccount = $this->accountRepository->getAccountById($inputData['id']);
        if(!$targetAccount) {
            return $updateFlag;
        }
    
        DB::beginTransaction();
        try {
            if($inputData['icon_image_file'] || $inputData['icon_image_file'] != null || $inputData['icon_image'] != $targetAccount['icon_image']) {
                $image_name = $this->upsertUserIconImageIntoStorage($inputData['icon_image_file'], $targetAccount['icon_image']);
                $inputData['icon_image'] = $image_name;
            }
            $checkUpdate = $this->accountRepository->updateProfile($inputData);
            if($checkUpdate) {
                $updateFlag = true;
            }
    
            DB::commit();
            return $updateFlag;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    /**
     * ユーザーアイコン更新,Storageに保存
     * @param object $iconImageFile
     * @param string $oldImageName
     * @return string $newImageName
     */
    public function upsertUserIconImageIntoStorage($iconImageFile, $oldImageName) {
        DB::beginTransaction();        
        try {    
            if($oldImageName && $oldImageName != null) {
                $this->deleteIconImageFromStorage($oldImageName);
            }
    
            $newImageName = 'noImage.png';
            if($iconImageFile && $iconImageFile!= null) {
                $originalName = $iconImageFile->file('icon_image_file')->getClientOriginalName();
                $newImageName = date('Ymd_His') . '_' . $originalName;
        
                $registerImageFlag = Storage::disk('public')->putFileAs('user_icon_images', $iconImageFile->file('icon_image_file'), $newImageName);
                if($registerImageFlag == false) {
                    throw new \Exception('画像の登録に失敗しました');
                }
            }
    
            DB::commit();
            return $newImageName;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    /**
     * ユーザーアイコン削除,Storageから削除
     * @param string $targetImageName
     * @return void
     */
    public function deleteIconImageFromStorage($targetImageName) {
        DB::beginTransaction();
        try {    
            if($targetImageName != null && $targetImageName != 'noImage.png') {
                $targetImageNameExists = Storage::disk('public')->exists('user_icon_images/'. $targetImageName);
                if($targetImageNameExists) {
                    $result = Storage::disk('public')->delete('user_icon_images/'. $targetImageName);
                    if($result == false) {
                        throw new \Exception('画像の削除に失敗しました');
                    }
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
        $deleteFlag = false;

        if(!Auth::user() || Auth::id() != $inputData['id']) {
            return $deleteFlag;
        }

        $targetAccount = $this->accountRepository->getAccountById($inputData['id']);
        if(!$targetAccount) {
            return $deleteFlag;
        }
    
        DB::beginTransaction();
        try {
            $this->deleteIconImageFromStorage($targetAccount['icon_image']);
            $checkDelete = $this->accountRepository->deleteAccount($inputData);
            if($checkDelete) {
                $this->logout($inputData);
                $deleteFlag = true;
            }
        
            DB::commit();
            return $deleteFlag;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    /**
     * ユーザーお気に入り登録
     * @param array $inputData
     * @return bool $result
     */
    public function registerFavoriteUser($inputData) {
        $resultFlag = false;
    
        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return $resultFlag;
        }

        $loginUser = $this->accountRepository->existsAccountById($inputData['user_id']);
        if(!$loginUser) {
            Auth::logout();
            return $resultFlag;
        }
    
        $targetAccount = $this->accountRepository->existsAccountById($inputData['favorite_user_id']);
        if(!$targetAccount) {
            return $resultFlag;
        }
    
        DB::beginTransaction();
        try {
            $result = $this->favoriteUserRepository->registerFavoriteUser($inputData);
            if($result == true) {
                $resultFlag = true;
            }
    
            DB::commit();
            return $resultFlag;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    /**
     * ユーザーお気に入り登録解除
     * @param array $inputData
     * @return bool $result
     */
    public function deleteFavoriteUser($inputData) {
        $resultFlag = false;
    
        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return $resultFlag;
        }

        $loginUser = $this->accountRepository->existsAccountById($inputData['user_id']);
        if(!$loginUser) {
            Auth::logout();
            return $resultFlag;
        }

        DB::beginTransaction();
        try {
            $result = $this->favoriteUserRepository->deleteFavoriteUser($inputData);
            if($result == true) {
                $resultFlag = true;
            }
    
            DB::commit();
            return $resultFlag;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }
}
