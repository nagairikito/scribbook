<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Const\TalkConst;
use App\Models\TalkRoom;

class TalkRoomRepository extends Repository
{
    public $talkRoom;

    public function __construct(TalkRoom $talkRoom) {
        // Modelのインスタンス化
        $this->talkRoom = $talkRoom;

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
     * @param int $useId
     * @param string $userIdColumn
     * @param string $deleteFlagColumn
     * @return array $talkRoomIds
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
     * user_id_1,2とトークルームIDでトーク相手を含むトークルーム情報を取得する
     * @param int $talkRoomIds
     * @param string $userIdColumn
     * @param string $deleteFlagColumn
     * @return array $talkRoomListWithOppositeUser
     */
    public function getTalkRoomWithOppositeUserByTargetUserId($talkRoomIds, $userIdColumn, $deleteFlagColumn) {
        $talkRoomListWithOppositeUser = $this->talkRoom
        ->join('m_users', 'm_users.id', '=', 't_talk_rooms.' . $userIdColumn)
        ->whereIn('t_talk_rooms.id', $talkRoomIds)
        ->where('t_talk_rooms.' . $deleteFlagColumn, TalkConst::FLAG_OFF)
        ->distinct('t_talk_rooms.id')
        ->select(
            't_talk_rooms.id as talk_room_id',
            't_talk_rooms.updated_at',
            'm_users.id as user_id',
            'm_users.icon_image',
            'm_users.name',
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
        ->where('user_id_2', $user_id)
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
        ->join('m_users', 'm_users.id', '=', 't_talk_rooms.user_id_1')
        ->where('t_talk_rooms.id', $talkRoomId)
        ->select(
            't_talk_rooms.updated_at',
            'm_users.id',
            'm_users.name',
        )
        ->get();
        
        return !empty($talkRoomListWithUserId1) ? $talkRoomListWithUserId1->toArray() : [];
    }

    /**
     * 2ユーザー間のトークルームの存在チェック
     * @param int $userId1
     * @param int $userId2
     * @return bool $result
     */
    public function checkExistsTargetTalkRoom($userId1, $userId2) {
        $result = $this->talkRoom
        ->where('user_id_1', $userId1)
        ->where('user_id_2', $userId2)
        ->exists();
        
        return $result;
    }

    /**
     * 2ユーザー間のトークルームIDを取得
     * @param int $userId1
     * @param int $userId2
     * @return int $talkRoomId
     */
    public function getTargetTalkRoomId($userId1, $userId2) {
        $talkRoomId = $this->talkRoom
        ->where('user_id_1', $userId1)
        ->where('user_id_2', $userId2)
        ->value('id');

        return $talkRoomId;
    }

    /**
     * 初回メッセージが送信された時点でトークルームを生成する
     * @param int $userId1
     * @param int $userId2
     * @return bool $result
     */
    public function createTalkRoom($userId1, $userId2) {
        $this->talkRoom->user_id_1 = $userId1;
        $this->talkRoom->user_id_2 = $userId2;
        $this->talkRoom->created_by = $userId1;
        $this->talkRoom->updated_by = $userId1;
        $result = $this->talkRoom->save();
        
        return $result;
    }

    /**
     * トークルームの更新日時を最新にする
     * @param int $inputData
     * @return bool $result
     */
    public function updateTalkRoom($talkRoomId) {
        $targetTalkRoom = $this->talkRoom->where('id', $talkRoomId)->first();
        $targetTalkRoom->updated_at = now();
        $result = $targetTalkRoom->save();
        
        return $result;
    }
}
