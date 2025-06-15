<?php

namespace App\Services;

use App\Models\Talk;
use App\Models\TalkRoom;
use App\Repositories\TalkRepository;
use App\Repositories\TalkRoomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $talkRoomIds1 = $this->talkRoomRepository->checkExistsUserId1AndGetTalkRoomIdByTargetUserId($userId, 'user_id_1', 'delete_flag_1');

        $talkRoomList1 = [];
        if($talkRoomIds1) {
            $talkRoomList1 = $this->talkRoomRepository->getTalkRoomWithOppositeUserByTargetUserId($talkRoomIds1, 'user_id_2', 'delete_flag_1');
        }
        
        // talk_roomsのuser_id_2に登録されているかチェック
        $talkRoomIds2 = $this->talkRoomRepository->checkExistsUserId1AndGetTalkRoomIdByTargetUserId($userId, 'user_id_2', 'delete_flag_2');

        $talkRoomList2 = [];
        if($talkRoomIds2) {
            $talkRoomList2 = $this->talkRoomRepository->getTalkRoomWithOppositeUserByTargetUserId($talkRoomIds2, 'user_id_1', 'delete_flag_2');
        }

        $margedTalkRoomList = array_merge($talkRoomList1, $talkRoomList2);
        $talkRoomList = collect($margedTalkRoomList)->sortByDesc('updated_at')->toArray();

        $margedTalkRoomIds = array_column($talkRoomList,'talk_room_id');
        $unReadMsgCounts = $this->talkRepository->getUnReadMessageCounts($margedTalkRoomIds, Auth::id());;
        for($i=0; $i<count($talkRoomList); $i++) {
            $talkRoomList[$i]['unReadMsgCount'] = $unReadMsgCounts[$i];
        }

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
     * 既読処理
     * @param int $talkRoom
     * @param int $userId
     * @return bool $result
     */
    public function alreadyReadFlagOn($talkRoomId, $userId) {
        $result = true;
        $unReadMessageCount = $this->talkRepository->getUnReadMessageCount($talkRoomId, $userId);

        // DB::transaction();
        // try {
            if($unReadMessageCount > 0) {
                $result = $this->talkRepository->updateUnReadMessages($talkRoomId, $userId);
            }

            // DB::commit();
            return $result;

        // } catch(\Exception $e) {
        //     DB::rollBack();
        //     report($e);
        // }
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

    /**
     * 全未読メッセージ取得
     * @param int $loginUserId
     * @return int $unReadMsgCount
     */
    public function getAllUnReadMessageCount($loginUserId) {
        $talkRoomIds1 = $this->talkRoomRepository->checkExistsUserId1AndGetTalkRoomIdByTargetUserId($loginUserId, 'user_id_1', 'delete_flag_1');
        $talkRoomIds2 = $this->talkRoomRepository->checkExistsUserId1AndGetTalkRoomIdByTargetUserId($loginUserId, 'user_id_2', 'delete_flag_2');
        $mergedTalkRoomIds = array_merge($talkRoomIds1, $talkRoomIds2);
        $unReadMsgCount = $this->talkRepository->getAllUnReadMessageCount($mergedTalkRoomIds, Auth::id());

        return $unReadMsgCount;
    }
}
