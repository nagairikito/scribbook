<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\TalkRoom;

use App\Const\AccountConst;

class TalkRoomRepository extends Repository
{
    public $talkRoom;

    public function __construct() {
        // Modelのインスタンス化
        $this->talkRoom = new TalkRoom;

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


}
