<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Talk;
use App\Const\TalkConst;

class TalkRepository extends Repository
{
    public $talk;

    public function __construct(Talk $talk) {
        $this->talk = $talk;
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
        ->join('users', 'users.id', '=', 'talks.created_by')
        ->where('talk_room_id', $inputData['talk_room_id'])
        ->orderBy('talks.updated_at')
        ->select(
            'talks.message',
            'talks.attached_file_path',
            'talks.updated_at',
            'talks.created_by',
            'users.name',
            'users.icon_image',
        )
        ->get();

        return !empty($messages) ? $messages->toArray() : [];
    }

    /**
     * 送信者による最新の送信メッセージを取得
     * @param $inputData
     * @return boolean $result
     */
    public function getLatestMessageBySender($inputData) {
        $messages = $this->talk
        ->join('users', 'users.id', '=', 'talks.created_by')
        ->where('talk_room_id', $inputData['talk_room_id'])
        ->where('created_by', $inputData['sender'])
        ->orderByDesc('talks.updated_at')
        ->select(
            'talks.message',
            'talks.attached_file_path',
            'talks.updated_at',
            'talks.created_by',
            'users.name',
            'users.icon_image',
        )
        ->first();

        return !empty($messages) ? $messages->toArray() : [];
    }

    /**
     * トークルームの最新のメッセージを取得
     * @param $inputData
     * @return boolean $result
     */
    public function getLatestMessageByTalkRoomId($talkRoomId, $deleteFlagColumn) {
        $latestMessages = $this->talk
        ->join('talk_rooms', 'talk_rooms.id', 'talks.talk_room_id')
        ->where('talks.talk_room_id', $talkRoomId)
        ->where('talks.' . $deleteFlagColumn, TalkConst::FLAG_OFF)
        ->where('talk_rooms.' . $deleteFlagColumn, TalkConst::FLAG_OFF)
        ->orderByDesc('talks.updated_at')
        ->select(
            'message',
            'attached_file_path',
        )
        ->first();

        return !empty($latestMessages) ? $latestMessages->toArray() : [];
    }

}
