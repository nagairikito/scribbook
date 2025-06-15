<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AccountUpdatingRequest;
use App\Services\AccountService;
use App\Services\BlogService;
use App\Const\AccountConst;

class ProfileController extends Controller
{
    public $accountService;
    public $blogService;

    public function __construct(AccountService $accountService, BlogService $blogService) {
        $this->accountService = $accountService;
        $this->blogService = $blogService;      
    }

    /**
     * プロフィール初期表示
     * @param array $request
     * @return view
     */
    public function profileTop(Request $request) {
        $inputData = [
            'user_id' => $request['id']
        ];

        $targetAccount = $this->accountService->getAccountById($inputData['user_id']);
        if(!$targetAccount) {
            return [];
        }

        $blogs = $this->blogService->getBlogsByUserId($targetAccount['id']);

        return view('profile_top', ['user' => $targetAccount, 'blogs' => $blogs]);
    }

    /**
     * ログアウト
     * 
     * @param array|object $request
     * @return view
     */
    public function logout(Request $request) {
        $inputData = [
            'id' => $request['id'],
            'session' => $request->session(),
        ];

        $result = $this->accountService->logout($inputData);

        if($result == true) {
            return redirect(route('toppage'))->with('success_logout', 'ログアウトしました');
        }
        return back()->with('error_logout', 'セッションが切れています');
    }


    /**
     * プロフィール更新
     * @param AccountUpdatingRequest $request
     * @return view
     */
    public function updateProfile(AccountUpdatingRequest $request) {
        $inputData = [
            'id'                => $request['login_user_id'],
            'name'              => $request['name'],
            'login_id'          => $request['login_id'],
            'password'          => $request['password'],
            'icon_image'        => $request['icon_image'],
            'icon_image_file'   => null,
            'discription'       => $request['discription'],
        ];
        if($request->file('icon_image_file') && 
        $request->file('icon_image_file') != null && 
        $request->file('icon_image_file') != '' && 
        $request->file('icon_image_file') != 'noImage.png') {
            $inputData['icon_image_file'] = $request;
        }

        $result = $this->accountService->updateProfile($inputData);

        if($result) {
            return redirect(route('profile_top', ['id' => $inputData['id']]))->with('success_update', 'プロフィールを更新しました');
        }
        return back()->with('error_update', '予期せぬエラーが発生しました')->withInput($inputData);
    }

    /**
     * 
     */
    public function deleteIconImageFromStorage(Request $request) {

    }

    /**
     * アカウント削除
     * @param object $request
     * @return view
     */
    public function deleteAccount(Request $request) {
        $inputData = [
            'id' => $request['id'],
            'session' => $request->session(),
        ];

        $result = $this->accountService->deleteAccount($inputData);

        if($result) {
            return redirect(route('toppage'))->with('success_delete', 'アカウントを削除しました');
        }
        return back()->with('error_delete', '予期せぬエラーが発生しました')->withInput($inputData);
    }

    /**
     * ユーザーお気に入り登録
     * @param array $request
     * @return view
     */
    public function registerFavoriteUser(Request $request) {
        $inputData = [
            'user_id' => $request['login_user_id'],
            'favorite_user_id' => $request['target_favorite_user_id'],
        ];

        $result = $this->accountService->registerFavoriteUser($inputData);

        if($result == true) {
            return redirect(route('profile_top', ['id' => $inputData['favorite_user_id']]))->with('success_register_favorite_user', 'お気に入り登録しました');
        }
        return back()->with('error_register_favorite_user', 'お気に入り登録できません、もしくは既にお気に入り登録されています');
    }

    /**
     * ユーザーお気に入り登録解除
     * @param array $request
     * @return view
     */
    public function deleteFavoriteUser(Request $request) {
        $inputData = [
            'user_id' => $request['login_user_id'],
            'favorite_user_id' => $request['target_favorite_user_id'],
        ];
        $pageType = ['page_type' => $request['page_type']];
        // page_type: profile_top => プロフィールトップからのリクエスト、 my_favorite_users => 自分のプロフィールのお気に入りユーザー一覧からのリクエスト

        $result = $this->accountService->deleteFavoriteUser($inputData);

        if($result == true) {
            if($pageType['page_type'] == 'profile_top') {
                return redirect(route('profile_top', ['id' => $inputData['favorite_user_id']]))->with('success_delete_favorite_user', 'お気に入り登録を解除しました');
            } elseif($pageType['page_type'] == 'my_favorite_users') {
                return redirect(route('profile_top', ['id' => $inputData['user_id']]))->with('success_delete_favorite_user', 'お気に入り登録を解除しました');
            }
        }
        return back()->with('error_delete_favorite_user', 'お気に入り登録を解除できません、もしくは既にお気に入り登録が解除されています');
    }
}
