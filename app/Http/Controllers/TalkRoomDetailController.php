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
use Illuminate\Support\Facades\Auth;

class TalkRoomDetailController extends Controller
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
     * トークルーム詳細
     */
    public function displayTalkRoom(Request $request) {
        $inputData = [
            'sender' => $request['sender'],
            'recipient' => $request['recipient'],
        ];

        $talkRoomId = $this->talkService->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
        $inputData['talk_room_id'] = $talkRoomId;

        $recipient = $this->accountRepository->getAccountById($inputData['recipient']);
        $recipientName = $recipient[0]['name'];

        $talkRoom = [
            'messages' => null,
            'recipient' => $inputData['recipient'],
            'recipient_name' => $recipientName,
        ];

        return view('display_talk_room', compact('talkRoom'));
    }

    /**
     * メッセージを送信
     */
    public function sendMessage(Request $request) {
        if(request()->get('message') == null) {
            return back();
        }

        $inputData = [
            'message' => request()->get('message'),
            'sender' => request()->get('sender'),
            'recipient' => request()->get('recipient'),
        ];

        $messages = $this->talkService->sendMessage($inputData);
        
        return response()->json(['data' => $inputData]);
    }

    /**
     * メッセージ全件取得
     */
    public function getMessages(Request $request) {
        $inputData = [
            'sender' => request()->get('sender'),
            'recipient' => request()->get('recipient'),
        ];

        $talkRoomId = $this->talkService->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
        $inputData['talk_room_id'] = $talkRoomId;
        $messages = $this->talkService->getAllMessageesByRoomId($inputData);

        $recipient = $this->accountRepository->getAccountById($inputData['recipient']);
        $recipientName = $recipient[0]['name'];

        $talkRoom = [
            'messages' => $messages,
            'sender' => Auth::id(),
            'recipient' => $inputData['recipient'],
            'recipient_name' => $recipientName,
        ];

        return response()->json(['talkRoom' => $talkRoom]);
    }
}
