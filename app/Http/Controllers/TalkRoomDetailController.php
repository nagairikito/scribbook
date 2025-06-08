<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountRepository;
use App\Services\TalkService;
use Illuminate\Support\Facades\Auth;

class TalkRoomDetailController extends Controller
{
    public $accountRepository;
    public $talkService;

    public function __construct(AccountRepository $accountRepository, TalkService $talkService) {
        $this->accountRepository = $accountRepository;
        $this->talkService = $talkService;
    }
    
    /**
     * トークルーム詳細
     * @param $request
     * @return array $talkRoom
     */
    public function displayTalkRoom(Request $request) {
        if($request['sender'] != Auth::id()) {
            return redirect('talk_room_list');
        }

        $inputData = [
            'sender' => $request['sender'],
            'recipient' => $request['recipient'],
        ];

        $talkRoomId = $this->talkService->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
        $inputData['talk_room_id'] = $talkRoomId;

        $recipient = $this->accountRepository->getAccountById($inputData['recipient']);
        $recipientName = $recipient['name'];

        $talkRoom = [
            'messages' => null,
            'recipient' => $inputData['recipient'],
            'recipient_name' => $recipientName,
        ];

        return view('display_talk_room', compact('talkRoom'));
    }

    /**
     * メッセージを送信
     * @param object request
     */
    public function sendMessage(Request $request) {
        if(request()->get('message') == null || request()->get('sender') != Auth::id()) {
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
     * @param object request
     */
    public function getMessages(Request $request) {
        $inputData = [
            // 'sender' => request()->get('sender'),
            'sender' => Auth::id(),
            'recipient' => request()->get('recipient'),
        ];

        $talkRoomId = $this->talkService->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
        $inputData['talk_room_id'] = $talkRoomId;
        $messages = $this->talkService->getAllMessageesByRoomId($inputData);

        $recipient = $this->accountRepository->getAccountById($inputData['recipient']);
        $talkRoomDatas = [
            'messages' => $messages,
            'sender' => Auth::id(),
            'recipient' => $recipient,
        ];

        // return response()->json(['talkRoom' => $talkRoom]);
        return response()->json([
            'html' => view('talk_room_parts', ['talkRoomDatas' => $talkRoomDatas])->render(),
        ]);
    }
}
