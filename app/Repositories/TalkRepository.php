<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Talk;
use App\Const\TalkConst;

use function Laravel\Prompts\select;

class TalkRepository extends Repository
{
    public $talk;

    public function __construct(Talk $talk) {
        $this->talk = $talk;
    }
    
    /**
     * 送信されたメッセージ登録
     * @param array $inputData
     * @return bool $result
     */
    public function saveMessage($inputData) {
        $this->talk->message = $inputData['message'];
        $this->talk->talk_room_id = $inputData['talk_room_id'];
        $this->talk->created_by = $inputData['sender'];
        $this->talk->updated_by = $inputData['sender'];
        $result = $this->talk->save();
        return $result;
    }

    /**
     * トークルーム単体の未読メッセージ数
     * @param int $talkRoomId
     * @param int $createdBy
     * @return int $count
     */
    public function getUnReadMessageCount($talkRoomId, $createdBy) {
        $count = $this->talk
        ->where('t_talks.talk_room_id', $talkRoomId)
        ->where('t_talks.created_by', '!=', $createdBy)
        ->where('t_talks.read_flag', TalkConst::ALREADY_READ_OFF)
        ->count();

        return $count;
    }

    /**
     * トークルームごとの未読メッセージ数
     * @param int $talkRoomId
     * @param int $createdBy
     * @return array $result
     */
    public function getUnReadMessageCounts($talkRoomIds, $createdBy) {
        // 未読件数があるルームだけ取得
        $unreadCounts = $this->talk
            ->select('talk_room_id', DB::raw('COUNT(*) as unread_count'))
            ->whereIn('talk_room_id', $talkRoomIds)
            ->where('created_by', '!=', $createdBy)
            ->where('read_flag', TalkConst::ALREADY_READ_OFF)
            ->groupBy('talk_room_id')
            ->pluck('unread_count', 'talk_room_id')
            ->toArray();

        // 結果を $talkRoomIds に合わせて、未読がない場合は0にする
        $result = [];
        foreach ($talkRoomIds as $id) {
            $result[] = $unreadCounts[$id] ?? 0;
        }

        return $result;    
    }

    /**
     * トークルームごとの未読メッセージ数
     * @param array $talkRoomIds
     * @return int $total
     */
    public function getAllUnReadMessageCount($talkRoomIds, $loginUserId) {
        $counts = $this->talk
            ->select(
                'talk_room_id',
                DB::raw('count(*) as count')
            )
            ->whereIn('talk_room_id', $talkRoomIds)
            ->where('updated_by', '!=', $loginUserId)
            ->where('read_flag', TalkConst::ALREADY_READ_OFF)
            ->groupBy('talk_room_id')
            // ->first();
            ->get();

        $countList = !empty($counts) ? $counts->toArray() : [];
        $total = 0;
        foreach($countList as $count) {
            $total += $count['count'];
        }
        return $total;
    }

    /**
     * 既読処理
     * @param int $talkRoomId
     * @param int $createdBy
     * @return bool $result
     */
    public function updateUnReadMessages($talkRoomId, $createdBy) {
        $result = $this->talk
        ->where('t_talks.talk_room_id', $talkRoomId)
        ->where('t_talks.created_by', '!=', $createdBy)
        ->where('t_talks.read_flag', TalkConst::ALREADY_READ_OFF)
        ->update(['t_talks.read_flag' => TalkConst::ALREADY_READ_ON]);

        return $result;
    }

    /**
     * トークルームIDによるメッセージ全件を取得
     * @param array $inputData
     * @return array $messages
     */
    public function getAllMessageesByRoomId($inputData) {
        $messages = $this->talk
        ->join('m_users', 'm_users.id', '=', 't_talks.created_by')
        ->where('t_talks.talk_room_id', $inputData['talk_room_id'])
        ->orderBy('t_talks.updated_at')
        ->select(
            't_talks.message',
            't_talks.attached_file_path',
            't_talks.read_flag',
            't_talks.updated_at',
            't_talks.created_by',
            'm_users.name',
            'm_users.icon_image',
        )
        ->get();

        return !empty($messages) ? $messages->toArray() : [];
    }

    /**
     * 送信者による最新の送信メッセージを取得
     * @param $inputData
     * @return boolean $result
     */
    public function getLatestMessageBySender($inputData) {
        $messages = $this->talk
        ->join('m_users', 'm_users.id', '=', 't_talks.created_by')
        ->where('t_talk_room_id', $inputData['talk_room_id'])
        ->where('t_talks.created_by', $inputData['sender'])
        ->orderByDesc('t_talks.updated_at')
        ->select(
            't_talks.message',
            't_talks.attached_file_path',
            't_talks.updated_at',
            't_talks.created_by',
            'm_users.name',
            'm_users.icon_image',
        )
        ->first();

        return !empty($messages) ? $messages->toArray() : [];
    }

    /**
     * トークルームの最新のメッセージを取得
     * @param int $talkRoomId
     * @param string $deleteFlagColumn
     * @return array $latestMessages
     */
    // public function getLatestMessageByTalkRoomId($talkRoomIds, $deleteFlagColumn) {
    //     $latestMessages = $this->talk
    //     ->rightJoin('t_talk_rooms', 't_talk_rooms.id', 't_talks.talk_room_id')
    //     ->whereIn('t_talks.talk_room_id', $talkRoomIds)
    //     ->where('t_talks.' . $deleteFlagColumn, TalkConst::FLAG_OFF)
    //     ->where('t_talk_rooms.' . $deleteFlagColumn, TalkConst::FLAG_OFF)
    //     ->orderByDesc('t_talks.updated_at')
    //     ->select(
    //         't_talks.message',
    //         't_talks.attached_file_path',
    //     )
    //     ->first();

    //     return !empty($latestMessages) ? $latestMessages->toArray() : [];
    // }
    public function getLatestMessageByTalkRoomId($talkRoomId, $deleteFlagColumn) {
        $latestMessages = $this->talk
        ->join('t_talk_rooms', 't_talk_rooms.id', 't_talks.talk_room_id')
        ->where('t_talks.talk_room_id', $talkRoomId)
        ->where('t_talks.' . $deleteFlagColumn, TalkConst::FLAG_OFF)
        ->where('t_talk_rooms.' . $deleteFlagColumn, TalkConst::FLAG_OFF)
        ->orderByDesc('t_talks.updated_at')
        ->select(
            't_talks.message',
            't_talks.attached_file_path',
        )
        ->first();

        return !empty($latestMessages) ? $latestMessages->toArray() : [];
    }

}
