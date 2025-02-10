<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Talk;

use App\Const\AccountConst;

class TalkRepository extends Repository
{
    public $talk;

    public function __construct() {
        // Modelのインスタンス化
        $this->talk = new Talk;

    }
    
    /**
     * 送信されたメッセージ登録
     * @param $inputData
     * @return boolean $result
     */
    public function saveMessage($inputData) {
        $this->talk->message = $inputData['message'];
        $this->talk->created_by = $inputData['sender'];
        $this->talk->talk_room_id = $inputData['talk_room_id'];
        $result = $this->talk->save();
        return $result;
    }

    /**
     * トークルームIDによるメッセージ全件を取得
     * @param $inputData
     * @return boolean $result
     */
    public function getAllMessageesByRoomId($inputData) {
        $messages = $this->talk
        ->select(
            'message',
            'attached_file_path',
            'created_by',
            'message',
        )
        ->where('talk_room_id', '=', $inputData['talk_room_id'])
        ->orderByDesc('updated_at')
        ->get();

        return !empty($messages) ? $messages->toArray() : [];
    }

}
