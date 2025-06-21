<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\BrowsingHistory;

use function Laravel\Prompts\select;

class BrowsingHistoryRepository extends Repository
{
    public $browsingHistory;
    
    public function __construct(BrowsingHistory $browsingHistory) {
        $this->browsingHistory = $browsingHistory;
    }
    
    /**
     * 閲覧履歴登録・更新
     * @param int $userId
     * @param int $blogId
     * @return void
     */
    public function upsertBrowsingHistory($userId, $blogId) {
        $result = $this->browsingHistory->upsert([
            'user_id' => $userId,
            'blog_id' => $blogId,
            'created_at' => now(),
            'updated_at' => now(),
        ], ['user_id', 'blog_id'], ['updated_at']);
    }

    /**
     * ユーザーIDに紐づく閲覧履歴を取得
     * @param int $userId
     * @return $browsingHistory
     */
    public function getBrowsingHisotryByUserId($userId) {
        $browsingHistory = $this->browsingHistory
        ->join('t_blogs', 't_blogs.id', '=', 't_browsing_histories.blog_id')
        ->join('m_users', 'm_users.id', '=', 't_blogs.created_by')
        ->where('t_browsing_histories.user_id', $userId)
        ->orderByDesc('t_browsing_histories.updated_at')
        ->select(
            't_blogs.*',
            'm_users.name as name',
            'm_users.icon_image'
        )
        ->get();

        return $browsingHistory;
    }


}
