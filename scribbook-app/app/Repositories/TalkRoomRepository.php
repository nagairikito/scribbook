<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\TalkRoom;

use App\Const\TalkConst;

class TalkRoomRepository extends Repository
{
    public $talkRoom;

    public function __construct() {
        // Modelのインスタンス化
        $this->talkRoom = new TalkRoom;

    }
    
    /**
     * ユーザーIDに紐づくトークルームを全件取得
     * @param $inputData
     * @return boolean $result
     */
    // public function getTalkRoomListByUserId($user_id) {
    //     $talkRoomList = $this->talkRoom
    //     ->where('user_id_1', '=', $user_id)
    //     ->orWhere('user_id_2', '=', $user_id)
    //     ->get();
        
    //     return $talkRoomList;
    // }


    /**
     * ユーザーIDに紐づくトークルーム情報を更新日時降順で取得
     */
    // public function getTalkRoomListByTargetUserId($user_id) {
        // $talkRoomIdList = $this->talkRoom
        // ->join('talks', 'talks.talk_room_id', '=', 'talk_rooms.id')
        // ->orwhere('talk_rooms.user_id_1', '=', $user_id)
        // ->orWhere('talk_rooms.user_id_2', '=', $user_id)
        // ->orderByDesc('talk_rooms.updated_at')
        // ->limit(1)
        // ->select('id')
        // ->get();
        // dd($talkRoomIdList->toArray());

        // $talkRoomIdList = $this->talkRoom
        // ->orwhere('talk_rooms.user_id_1', '=', $user_id)
        // ->orWhere('talk_rooms.user_id_2', '=', $user_id)
        // ->orderByDesc('talk_rooms.updated_at')
        // ->select('id')
        // ->get();

        // return !empty($talkRoomIdList) ? $talkRoomIdList->toArray() : [];
        // user_id_1か2のどちらで登録されているか判定->登録されているuser_idと逆のjoinsql(talk_rooms.user_idとusers.id)
    // }



    /**
     * user_id_1,2に指定のユーザーIDがヒットするか、一致していればトークルームIDを返す
     * @param $inputData
     * @return boolean $result
     */
    public function checkExistsUserId1AndGetTalkRoomIdByTargetUserId($userId, $userIdColumn, $deleteFlagColumn) {
        $talkRoomIds = $this->talkRoom
        ->where($userIdColumn, $userId)
        ->where($deleteFlagColumn, TalkConst::FLAG_OFF)
        ->select('id')
        ->get();
        
        return !empty($talkRoomIds) ? $talkRoomIds->toArray() : [];
    }

    /**
     * user_id_1,2でusersテーブルとリレーションを持たせ、トークルームIDでトークルーム情報を取得する
     * @param $inputData
     * @return boolean $result
     */
    public function getTalkRoomWithOppositeUserByTargetUserId($talkRoomIds, $userIdColumn, $deleteFlagColumn) {
        $talkRoomListWithOppositeUser = $this->talkRoom
        ->join('users', 'users.id', 'talk_rooms.' . $userIdColumn)
        ->whereIn('talk_rooms.id', $talkRoomIds)
        ->where('talk_rooms.' . $deleteFlagColumn, TalkConst::FLAG_OFF)
        ->distinct('talk_rooms.id')
        ->select(
            'talk_rooms.id as talk_room_id',
            'talk_rooms.updated_at',
            'users.id as user_id',
            'users.icon_image',
            'users.name',
        )
        ->get();
        
        return !empty($talkRoomListWithOppositeUser) ? $talkRoomListWithOppositeUser->toArray() : [];
    }

    /**
     * user_id_2に指定のユーザーIDがヒットするか、一致していればトークルームIDを返す
     * @param $inputData
     * @return boolean $result
     */
    public function checkExistsAndGetUserId2AndTalkRoomIds($user_id) {
        $talkRoomIds = $this->talkRoom
        ->where('user_id_2', '=', $user_id)
        ->select('id')
        ->get();
        
        return !empty($talkRoomIds) ? $talkRoomIds->toArray() : [];
    }

    /**
     * user_id_1でusersテーブルとリレーションを持たせ、トークルーム情報を取得する
     * @param $inputData
     * @return boolean $result
     */
    public function getTalkRoomListWithUserId1ByTartgetUserId($talkRoomId) {
        $talkRoomListWithUserId1 = $this->talkRoom
        ->join('users', 'users.id', '=', 'talk_rooms.user_id_1')
        ->where('talk_rooms.id', '=', $talkRoomId)
        ->select(
            'talk_rooms.updated_at',
            'users.id',
            'users.name',
        )
        ->get();
        
        return !empty($talkRoomListWithUserId1) ? $talkRoomListWithUserId1->toArray() : [];
    }

    /**
     * 2ユーザー間のトークルームの存在チェック
     * @param $inputData
     * @return boolean $result
     */
    public function checkExistsTargetTalkRoom($user_id_1, $user_id_2) {
        $result = $this->talkRoom
        ->where('user_id_1', '=', $user_id_1)
        ->where('user_id_2', '=', $user_id_2)
        ->exists();
        
        return $result;
    }

    /**
     * 2ユーザー間のトークルームIDを取得
     * @param $inputData
     * @return boolean $result
     */
    public function getTargetTalkRoomId($user_id_1, $user_id_2) {
        $talkRoomId = $this->talkRoom
        ->where('user_id_1', '=', $user_id_1)
        ->where('user_id_2', '=', $user_id_2)
        ->value('id');

        return $talkRoomId;
    }

    /**
     * 初回メッセージが送信された時点でトークルームを生成する
     * @param $inputData
     * @return boolean $result
     */
    public function createTalkRoom($user_id_1, $user_id_2) {
        $this->talkRoom->user_id_1 = $user_id_1;
        $this->talkRoom->user_id_2 = $user_id_2;
        $result = $this->talkRoom->save();
        
        return $result;
    }

    /**
     * トークルームの更新日時を最新にする
     * @param $inputData
     * @return boolean $result
     */
    public function updateTalkRoom($talkRoomId) {
        $targetTalkRoom = $this->talkRoom->where('id', '=', $talkRoomId)->first();
        $targetTalkRoom->updated_at = now();
        $result = $targetTalkRoom->save();
        
        return $result;
    }


}
