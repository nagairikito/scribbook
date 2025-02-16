<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Talk;
use App\Models\TalkRoom;
use App\Repositories\AccountRepository;
use App\Repositories\TalkRepository;
use App\Repositories\TalkRoomRepository;
use App\Services\TalkService;

class TalkController extends Controller
{
    public $accountRepository;
    public $talkService;
    public $talkRoomService;
    public function __construct() {
        // Modelのインスタンス化
        $user = new User;
        $talk = new Talk;
        $talkRoom = new TalkRoom;

        // Repositoryのインスタンス化
        $this->accountRepository = new AccountRepository($user);
        $talkRepository = new TalkRepository($talk);
        $talkRoomRepository = new TalkRoomRepository($talkRoom);

        // Serviceのインスタンス化
        $this->talkService = new TalkService($talkRepository, $talkRoomRepository);
    }
    
    /**
     * トークルームリスト表示
     */
    public function showTalkRoomList(Request $request) {
        $inputData = [
            'user_id' => $request['id'],
        ];
        $talkRoomList = $this->talkService->getTalkRoomListByUserId($inputData['user_id']);

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

        $recipient = $this->accountRepository->getAccountById($inputData['recipient']);
        $recipientName = $recipient[0]['name'];

        $talkRoom = [
            'messages' => $messages,
            'recipient' => $inputData['recipient'],
            'recipient_name' => $recipientName,
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
        
        $recipient = $this->accountRepository->getAccountById($inputData['recipient']);
        $recipientName = $recipient[0]['name'];

        $talkRoom = [
            'messages' => $messages,
            'recipient' => $inputData['recipient'],
            'recipient_name' => $recipientName,
        ];

        return view('Talk/display_talk_room', compact('talkRoom'));
    }

}
