<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Const\AccountConst;
use App\Models\User;

class AccountRepository extends Repository
{
    public $user;

    public function __construct(User $user) {
        $this->user = $user;
    }
    
    /**
     * アカウント新規登録
     * @param array $inputData
     * @return boolean $result
     */
    public function registerAccount($inputData) {
        $this->user->name = $inputData['name'];
        $this->user->login_id = $inputData['login_id'];
        $this->user->password = $inputData['password'];
        $result = $this->user->save();
        return $result;
    }

    /**
     * idをもとに特定のアカウント情報を単体取得
     * @param int $id
     * @return array $result
     */
    public function getAccountById($id) {
        $result = $this->user->where('id', '=', $id)->where('delete_flag', AccountConst::USER_DELETE_FLAG_OFF)->first();
        return !empty($result) ? $result->toArray() : [];
    }

    /**
     * idをもとに特定の単一アカウント情報の存在確認
     * @param int $id
     * @return bool $result
     */
    public function existsAccountById($id) {
        $result = $this->user->where('id', '=', $id)->where('delete_flag', AccountConst::USER_DELETE_FLAG_OFF)->exists();
        return $result;
    }

    /**
     * ログインユーザーの取得
     * @param int $login_id
     * @return array $loginUser
     */
    public function login($loginId) {
        $loginUser = $this->user->where('login_id', '=', $loginId)->where('delete_flag', AccountConst::USER_DELETE_FLAG_OFF)->first();
        return $loginUser;
    }

    /**
     * ユーザーのプロフィール情報を更新
     * @param $inputData
     * @return $result
     */
    public function updateProfile($inputData) {
        $targetAccount = $this->user->where('id', $inputData['id'])->first();
        $targetAccount->name = $inputData['name'];
        $targetAccount->login_id = $inputData['login_id'];
        $targetAccount->password = $inputData['password'];
        $targetAccount->icon_image = $inputData['icon_image'];
        $targetAccount->discription = $inputData['discription'];
        $result = $targetAccount->save();
        return $result;
    }

    /**
     * アカウント削除
     * @param array $inputData
     * @return bool $result
     */
    public function deleteAccount($inputData) {
        $targetAccount = $this->user->where('id', $inputData['id'])->first();
        $targetAccount->delete_flag = AccountConst::USER_DELETE_FLAG_ON;
        $result = $targetAccount->save();
        return $result;
    }

}
