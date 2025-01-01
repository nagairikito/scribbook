<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\User;

use App\Const\AccountConst;

class AccountRepository extends Repository
{
    public $user;
    public function __construct() {
        // Modelのインスタンス化
        $this->user = new User;
    }
    
    
    /**
     * ログイン
     * @param $login_id
     * @return array $loginUser
     */
    public function login($login_id) {
        $loginUser = $this->user->where('login_id', '=', $login_id)->where('delete_flag', '=', AccountConst::USER_DELETE_FLAG_OFF)->first();
        return $loginUser;
    }

    /**
     * アカウント新規登録
     * @param $inputData
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
     * @param $id
     * @return object $result
     */
    public function getAccountById($id) {
        $result = $this->user->where('id', '=', $id)->where('delete_flag', '=', AccountConst::USER_DELETE_FLAG_OFF)->get();
        return $result;
    }

    /**
     * ユーザーのプロフィール情報を更新
     * @param $inputData
     * @return $result
     */
    public function updateProfile($inputData) {
        $targetAccount = $this->user->where('id', '=', $inputData['id'])->first();
        $targetAccount->name = $inputData['name'];
        $targetAccount->login_id = $inputData['login_id'];
        $targetAccount->password = $inputData['password'];
        $targetAccount->icon_image = $inputData['icon_image'];
        $targetAccount->discription = $inputData['discription'];
        $result = $targetAccount->save();
        return $result;
    }

}
