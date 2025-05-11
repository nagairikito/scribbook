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

class TalkRoomController extends Controller
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
        $talkRoomList = [];
        return view('talk_room_list', compact('talkRoomList'));
    }

    /**
     * トークルームリスト表示
     */
    public function getTalkRoomList(Request $request) {
        $talkRoomList = $this->talkService->getTalkRoomListByUserId(Auth::id());
        return response()->json([
            'html' => view('talk_room_list_parts', ['talkRoomList' => $talkRoomList])->render(),
        ]);
    }
}
