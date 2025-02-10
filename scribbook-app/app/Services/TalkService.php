<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Talk;
use App\Models\TalkRoom;
use App\Repositories\TalkRepository;
use App\Repositories\TalkRoomRepository;

class TalkService extends Service
{
    public $talkRepository;
    public $talkRoomRepository;

    public function __construct() {
        // Modelのインスタンス化
        $talk = new Talk;
        $talkRoom = new TalkRoom;

        // Repositoryのインスタンス化
        $this->talkRepository = new TalkRepository($talk);
        $this->talkRoomRepository = new TalkRoomRepository($talkRoom);

    }

    /**
     * 2ユーザー間のトークルームIDの取得
     */
    public function getTargetTalkRoomId($user_id_1, $user_id_2) {
        $talkRoomId = null;

        $checkExistsTalkRoom1 = $this->talkRoomRepository->checkExistsTargetTalkRoom($user_id_1, $user_id_2);
        if($checkExistsTalkRoom1) {
            $talkRoomId = $this->talkRoomRepository->getTargetTalkRoomId($user_id_1, $user_id_2);
        }
        $checkExistsTalkRoom2 = $this->talkRoomRepository->checkExistsTargetTalkRoom($user_id_2, $user_id_1);
        if($checkExistsTalkRoom2) {
            $talkRoomId = $this->talkRoomRepository->getTargetTalkRoomId($user_id_2, $user_id_1);
        }

        return $talkRoomId;
    }
    
    /**
     * 2ユーザー間のトークルームのメッセージを全件の取得
     */
    public function getAllMessageesByRoomId($talkRoom) {
        $messages = $this->talkRepository->getAllMessageesByRoomId($talkRoom);
        return $messages;
    }
    
    /**
     * メッセージ送信処理
     * @param $inputData
     * @return bool $result
     */
    public function sendMessage($inputData) {
        $talkRoomId = $this->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
        if($talkRoomId == null) {
            $this->talkRoomRepository->createTalkRoom($inputData['sender'], $inputData['recipient']);
            $talkRoomId = $this->talkRoomRepository->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
        }
        $inputData['talk_room_id'] = $talkRoomId;

        $this->talkRepository->saveMessage($inputData);
        $messages = $this->getAllMessageesByRoomId($inputData);

        return $messages;

    }

}
