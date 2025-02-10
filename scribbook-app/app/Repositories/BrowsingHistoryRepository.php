<?php

namespace App\Repositories;

use App\Const\AccountConst;
use Illuminate\Http\Request;
use App\Models\BrowsingHistory;

use function Laravel\Prompts\select;

class BrowsingHistoryRepository extends Repository
{
    public $browsingHistory;
    
    public function __construct() {
        // Modelのインスタンス化
        $this->browsingHistory = new BrowsingHistory;

    }
    
    /**
     * 閲覧履歴登録・更新
     */
    public function upsertBrowsingHistory($user_id, $blog_id) {
        $result = $this->browsingHistory->upsert([
            'user_id' => $user_id,
            'blog_id' => $blog_id,
            'updated_at' => now(),
        ], ['user_id', 'blog_id'], ['updated_at']);

        return $result;
    }

    /**
     * ユーザーIDに紐づく閲覧履歴を取得
     */
    public function getBrowsingHisotryByUserId($user_id) {
        $browsingHistory = $this->browsingHistory
        ->join('articles', 'articles.id', '=', 'browsing_histories.blog_id')
        ->where('browsing_histories.user_id', '=', $user_id)
        ->orderByDesc('browsing_histories.updated_at')
        ->select(
            'articles.*',
        )
        ->get();

        return $browsingHistory;
    }


}
