<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Talk;
use App\Models\TalkRoom;
use App\Repositories\TalkRepository;
use App\Repositories\TalkRoomRepository;
use App\Services\TalkService;

class TalkController extends Controller
{
    public $talkService;
    public $talkRoomService;
    public function __construct() {
        // Modelのインスタンス化
        $talk = new Talk;
        $talkRoom = new TalkRoom;

        // Repositoryのインスタンス化
        $talkRepository = new TalkRepository($talk);
        $talkRoomRepository = new TalkRoomRepository($talkRoom);

        // Serviceのインスタンス化
        $this->talkService = new TalkService($talkRepository, $talkRoomRepository);
    }
    
    /**
     * トップページ初期表示
     */
    public function showTalkRoomList() {
        $talkRoomList = $this->talkRoomService->getTalkRoomList();

        return view('Talk/talk_room_list', compact('talkRoomList'));
    }

    /**
     * トークルーム詳細
     */
    public function displayTalkRoom(Request $request) {
        $inputData = [
            'sender' => $request['sender'],
            'recipient' => $request['recipient'],
        ];

        $talkRoomId = $this->talkService->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
        $inputData['talk_room_id'] = $talkRoomId;
        $messages = $this->talkService->getAllMessageesByRoomId($inputData);
        $talkRoom = [
            'messages' => $messages,
            'recipient' => $inputData['recipient'],
        ];

        return view('Talk/display_talk_room', compact('talkRoom'));
    }

    /**
     * メッセージを送信
     */
    public function sendMessage(Request $request) {
        $inputData = [
            'message' => $request['message'],
            'sender' => $request['sender'],
            'recipient' => $request['recipient'],
        ];

        $messages = $this->talkService->sendMessage($inputData);
        $talkRoom = [
            'messages' => $messages,
            'recipient' => $inputData['recipient'],
        ];

        return view('Talk/display_talk_room', compact('talkRoom'));
    }

}
