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
     * @param int $id
     * @return array $favoriteUsers
     */
    public function getFavoriteUsersByUserId($id) {
        $favoriteUsers = $this->favoriteUser
        ->join('m_users', 'm_users.id', '=', 't_favorite_users.favorite_user_id')
        ->where('m_users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
        ->where('t_favorite_users.user_id', $id)
        ->orderByDesc('t_favorite_users.created_at')
        ->select(
            'm_users.*',
        )
        ->get();

        return !empty($favoriteUsers) ? $favoriteUsers->toArray() : [];
    }

    /**
     * 対象のユーザーIDに紐づくお気に入り登録されたユーザーの存在チェック
     * @param int $loginUserId
     * @param int $targetFavoriteUserId
     * @return bool $checkFavorite
     */
    public function checkFavorite($loginUserId, $targetFavoriteUserId) {
        $checkFavorite = $this->favoriteUser
        ->join('m_users', 'm_users.id', '=', 't_favorite_users.favorite_user_id')
        ->where('m_users.delete_flag', AccountConst::USER_DELETE_FLAG_OFF)
        ->where('t_favorite_users.user_id', $loginUserId)
        ->where('t_favorite_users.favorite_user_id', $targetFavoriteUserId)
        ->exists();

        return $checkFavorite;
    }

    /**
     * ユーザーお気に入り登録
     * @param array $inputData
     * @return bool $result
     */
    public function registerFavoriteUser($inputData) {
        $this->favoriteUser->user_id = $inputData['user_id'];
        $this->favoriteUser->favorite_user_id = $inputData['favorite_user_id'];
        $result = $this->favoriteUser->save();

        return $result;
    }
    
    /**
     * ユーザーお気に入り登録解除
     * @param array $inputData
     * @return bool $result
     */
    public function deleteFavoriteUser($inputData) {
        $result = $this->favoriteUser
        ->where('user_id', $inputData['user_id'])
        ->where('favorite_user_id', $inputData['favorite_user_id'])
        ->delete();

        return $result;
    }
}
