<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountRepository;
use App\Services\TalkService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TalkRoomController extends Controller
{
    public $accountRepository;
    public $talkService;

    public function __construct(AccountRepository $accountRepository, TalkService $talkService) {
        $this->accountRepository = $accountRepository;
        $this->talkService = $talkService;
    }
    
    /**
     * トークルームリストページ表示
     * @return view
     */
    public function showTalkRoomList(Request $request) {
        $talkRoomList = [];
        
        return view('talk_room_list', compact('talkRoomList'));
        // return view('talk_room_list_blade', compact('talkRoomList'));
    }

    /**
     * トークルームリスト表示
     * @return view
     */
    public function getTalkRoomList(Request $request) {
        $talkRoomList = $this->talkService->getTalkRoomListByUserId(Auth::id());

        return response()->json([
            'html' => view('talk_room_list_parts', ['talkRoomList' => $talkRoomList])->render(),
        ]);
    }
}
