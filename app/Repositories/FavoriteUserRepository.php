<?php

namespace App\Repositories;

use App\Const\AccountConst;
use Illuminate\Http\Request;
use App\Models\FavoriteUser;

class FavoriteUserRepository extends Repository
{
    public $favoriteUser;
    
    public function __construct(FavoriteUser $favoriteUser) {
        $this->favoriteUser = $favoriteUser;
    }

    /**
     * 対象のユーザーIDに紐づくお気に入り登録されたユーザーを全件取得
     * @param $id
     * @return $favoriteUsers
     */
    public function getFavoriteUsersByUserId($id) {
        $favoriteUsers = $this->favoriteUser
        ->join('users', 'users.id', '=', 'favorite_users.favorite_user_id')
        ->where('users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
        ->where('favorite_users.user_id', $id)
        ->orderByDesc('favorite_users.created_at')
        ->select(
            'users.*',
        )
        ->get();

        return !empty($favoriteUsers) ? $favoriteUsers->toArray() : [];
    }

    /**
     * 対象のユーザーIDに紐づくお気に入り登録されたユーザーを全件取得
     * @param $login_user_id $target_favorite_user_id
     * @return $checkFavorite
     */
    public function checkFavorite($login_user_id, $target_favorite_user_id) {
        $checkFavorite = $this->favoriteUser
        ->join('users', 'users.id', '=', 'favorite_users.favorite_user_id')
        ->where('users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
        ->where('favorite_users.user_id', $login_user_id)
        ->where('favorite_users.favorite_user_id', $target_favorite_user_id)
        ->exists();

        return $checkFavorite;
    }

    /**
     * ユーザーお気に入り登録
     * @param $inputData
     * @return $result
     */
    public function registerFavoriteUser($inputData) {
        $this->favoriteUser->user_id = $inputData['user_id'];
        $this->favoriteUser->favorite_user_id = $inputData['favorite_user_id'];
        $result = $this->favoriteUser->save();

        return $result;
    }
    
    /**
     * ユーザーお気に入り登録解除
     * @param $inputData
     * @return $result
     */
    public function deleteFavoriteUser($inputData) {
        $result = $this->favoriteUser
        ->where('user_id', $inputData['user_id'])
        ->where('favorite_user_id', $inputData['favorite_user_id'])
        ->delete();

        return $result;
    }
}
