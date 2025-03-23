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
    // public function showTalkRoomList(Request $request) {
    //     $inputData = [
    //         'user_id' => $request['id'],
    //     ];
    //     $talkRoomList = $this->talkService->getTalkRoomListByUserId($inputData['user_id']);

    //     return view('Talk/talk_room_list', compact('talkRoomList'));
    // }

    /**
     * トークルームリスト表示
     */
    public function showTalkRoomList(Request $request) {
        $talkRoomList = [];
        return view('Talk/talk_room_list', compact('talkRoomList'));
    }

    /**
     * トークルームリスト表示
     */
    public function getTalkRoomList(Request $request) {
        $talkRoomList = $this->talkService->getTalkRoomListByUserId(Auth::id());
        return response()->json([
            'html' => view('Talk/talk_room_list_parts', ['talkRoomList' => $talkRoomList])->render(),
        ]);
    }


    /**
     * トークルーム詳細
     */
    // public function displayTalkRoom(Request $request) {
    //     $inputData = [
    //         'sender' => $request['sender'],
    //         'recipient' => $request['recipient'],
    //     ];

    //     $talkRoomId = $this->talkService->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
    //     $inputData['talk_room_id'] = $talkRoomId;
    //     $messages = $this->talkService->getAllMessageesByRoomId($inputData);

    //     $recipient = $this->accountRepository->getAccountById($inputData['recipient']);
    //     $recipientName = $recipient[0]['name'];

    //     $talkRoom = [
    //         'messages' => $messages,
    //         'recipient' => $inputData['recipient'],
    //         'recipient_name' => $recipientName,
    //     ];

    //     return view('Talk/display_talk_room', compact('talkRoom'));
    // }

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

        return view('Talk/display_talk_room', compact('talkRoom'));
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
        
        // $recipient = $this->accountRepository->getAccountById($inputData['recipient']);
        // $recipientName = $recipient[0]['name'];

        // $talkRoom = [
        //     'message' => $inputData['message'],
        //     'sent_at' => now(),
        //     // 'recipient' => $inputData['recipient'],
        //     // 'recipient_name' => $recipientName,

        // ];

        // return response()->json(['talkRoom' => $talkRoom]);
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

        // $talkRoom = [
        //     'messages' => $messages,
        //     'sender' => $inputData['sender'],
        //     'recipient' => $inputData['recipient'],
        //     'recipient_name' => $recipientName,
        // ];
        $talkRoom = [
            'messages' => $messages,
            'sender' => Auth::id(),
            'recipient' => $inputData['recipient'],
            'recipient_name' => $recipientName,
        ];

        // viewを返すパターン
        // return response()->json([
        //     'html' => view('Talks.display_talk_room', ['talkRoom' => $talkRoom])->render()
        // ]);
        return response()->json(['talkRoom' => $talkRoom]);
        
    }

    /**
     * 送信者による最新の送信メッセージを取得
     */
    // public function getLatestMessageBySender(Request $request) {
    //     $inputData = [
    //         'sender' => request()->get('sender'),
    //         'recipient' => request()->get('recipient'),
    //     ];

    //     $talkRoomId = $this->talkService->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
    //     $inputData['talk_room_id'] = $talkRoomId;
    //     $message = $this->talkService->getLatestMessageBySender($inputData);

    //     $recipient = $this->accountRepository->getAccountById($inputData['recipient']);
    //     $recipientName = $recipient[0]['name'];

    //     $talkRoom = [
    //         'message' => $message,
    //         'sender' => $inputData['sender'],
    //         'recipient' => $inputData['recipient'],
    //         'recipient_name' => $recipientName,
    //     ];

    //     return response()->json(['talkRoom' => $talkRoom]);
    // }

}
