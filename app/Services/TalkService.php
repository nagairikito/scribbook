<?php

namespace App\Services;

use App\Models\Talk;
use App\Models\TalkRoom;
use App\Repositories\TalkRepository;
use App\Repositories\TalkRoomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TalkService extends Service
{
    public $talkRepository;
    public $talkRoomRepository;

    public function __construct(TalkRepository $talkRepository, TalkRoomRepository $talkRoomRepository) {
        $this->talkRepository = $talkRepository;
        $this->talkRoomRepository = $talkRoomRepository;
    }

    /**
     * ユーザーIDに紐づくトークルームを全件取得
     * @param int $userId
     * @return array $talkRoomList
     */
    public function getTalkRoomListByUserId($userId) {
        // talk_roomsのuser_id_1に登録されているかチェック
        $talkRoomIdList1 = $this->talkRoomRepository->checkExistsUserId1AndGetTalkRoomIdByTargetUserId($userId, 'user_id_1', 'delete_flag_1');

        $talkRoomList1 = [];
        if($talkRoomIdList1) {

            $talkRoomIds1 = [];
            foreach($talkRoomIdList1 as $talkRoomId1) {
                $talkRoomIds1[] = $talkRoomId1['id'];
            }
            $preTalkRoomList1 = $this->talkRoomRepository->getTalkRoomWithOppositeUserByTargetUserId($talkRoomIds1, 'user_id_2', 'delete_flag_1');
            
            foreach($preTalkRoomList1 as $preTalkRoom1) {
                $latestMessage = $this->talkRepository->getLatestMessageByTalkRoomId($preTalkRoom1['talk_room_id'], 'delete_flag_1');
                if($latestMessage == null) {
                    $latestMessage = [
                        'message' => null,
                        'attached_file_path' => null,    
                    ];
                }

                $preTalkRoom1['latest_message'] = $latestMessage;
                $talkRoomList1[] = $preTalkRoom1;
            }
        }

        // talk_roomsのuser_id_2に登録されているかチェック
        $talkRoomIdList2 = $this->talkRoomRepository->checkExistsUserId1AndGetTalkRoomIdByTargetUserId($userId, 'user_id_2', 'delete_flag_2');

        $talkRoomList2 = [];
        if($talkRoomIdList2) {
            $talkRoomIds2 = [];
            foreach($talkRoomIdList2 as $talkRoomId2) {
                $talkRoomIds2[] = $talkRoomId2['id'];
            }
            $preTalkRoomList2 = $this->talkRoomRepository->getTalkRoomWithOppositeUserByTargetUserId($talkRoomIds2, 'user_id_1', 'delete_flag_2');
        
            foreach($preTalkRoomList2 as $preTalkRoom2) {
                $latestMessage = $this->talkRepository->getLatestMessageByTalkRoomId($preTalkRoom2['talk_room_id'], 'delete_flag_2');
                if($latestMessage == null) {
                    $latestMessage = [
                        'message' => null,
                        'attached_file_path' => null,    
                    ];
                }

                $preTalkRoom2['latest_message'] = $latestMessage;
                $talkRoomList2[] = $preTalkRoom2;
            }
        }

        $margedTalkRoomList = array_merge($talkRoomList1, $talkRoomList2);
        $talkRoomList = collect($margedTalkRoomList)->sortByDesc('updated_at')->toArray();

        return $talkRoomList;
    }

    /**
     * 2ユーザー間のトークルームIDの取得
     * @param int $userId1
     * @param int $userId2
     * @param int $talkRoomId
     */
    public function getTargetTalkRoomId($userId1, $userId2) {
        $talkRoomId = null;

        $checkExistsTalkRoom1 = $this->talkRoomRepository->checkExistsTargetTalkRoom($userId1, $userId2);
        if($checkExistsTalkRoom1) {
            $talkRoomId = $this->talkRoomRepository->getTargetTalkRoomId($userId1, $userId2);
        }
        $checkExistsTalkRoom2 = $this->talkRoomRepository->checkExistsTargetTalkRoom($userId2, $userId1);
        if($checkExistsTalkRoom2) {
            $talkRoomId = $this->talkRoomRepository->getTargetTalkRoomId($userId2, $userId1);
        }

        return $talkRoomId;
    }
    
    /**
     * 2ユーザー間のトークルームのメッセージを全件の取得
     * @param array $talkRoom
     * @return array $messages
     */
    public function getAllMessageesByRoomId($talkRoom) {
        $messages = $this->talkRepository->getAllMessageesByRoomId($talkRoom);
        return $messages;
    }
        
    /**
     * メッセージ送信処理
     * @param array $inputData
     * @return bool $result
     */
    public function sendMessage($inputData) {
        DB::beginTransaction();
        try {    
            $talkRoomId = $this->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
            if($talkRoomId) {
                $this->talkRoomRepository->updateTalkRoom($talkRoomId);
            }
            if($talkRoomId == null) {
                $this->talkRoomRepository->createTalkRoom($inputData['sender'], $inputData['recipient']);
                $talkRoomId = $this->talkRoomRepository->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
            }
            $inputData['talk_room_id'] = $talkRoomId;
    
            $this->talkRepository->saveMessage($inputData);
            $messages = $this->getAllMessageesByRoomId($inputData);
    
            $talkRoomId = $this->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
            if($talkRoomId) {
                $this->talkRoomRepository->updateTalkRoom($talkRoomId);
            }
            if($talkRoomId == null) {
                $this->talkRoomRepository->createTalkRoom($inputData['sender'], $inputData['recipient']);
                $talkRoomId = $this->talkRoomRepository->getTargetTalkRoomId($inputData['sender'], $inputData['recipient']);
            }
            $inputData['talk_room_id'] = $talkRoomId;
    
            $this->talkRepository->saveMessage($inputData);
            $messages = $this->getAllMessageesByRoomId($inputData);
            
            DB::commit();
            return $messages;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }    
    }

}
