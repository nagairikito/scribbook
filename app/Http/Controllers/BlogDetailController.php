<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdvertisementRequest;
use App\Http\Requests\BlogCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\BlogService;

class BlogDetailController extends Controller
{
    public $blogService;

    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService;
    }

    /**
     * ブログ詳細取得
     * @param string $id
     * @return $blogData
     * @return view
     */
    public function blogDetail($id) {
        $intId = intval($id);
        if($intId === 0) {
            return back()->with('error_get_blog_detail', 'パラメータが不正です');
        }
        $blog = $this->blogService->getBlogDetail($intId);
        if(empty($blog)) {
            return back()->with('error_get_blog_detail', 'ブログが見つかりませんでした');
        }
        $comments = $this->blogService->getBlogComments($blog['id']);
        $advertisement = $this->blogService->getAdvertisementByBlogId($blog['id']);

        $blogData = [
            'blog'              => $blog,
            'comments'          => $comments,
            'advertisement'     => $advertisement,
            'blog_detail_flag'  => true,
        ];

        return view('blog_detail', ['blogData' => $blogData]);
    }

    /**
     * ブログ削除
     * @param int $request
     * @return view
     */
    public function deleteBlog(Request $request) {
        $inputData = [
            'id' => $request['blog_id'],
            'created_by' => $request['login_user_id'],
        ];

        $result = $this->blogService->deleteBlog($inputData);

        if($result) {
            return redirect(route('profile_top', ['id' => Auth::id()]))->with('success_delete_blog', 'ブログを削除しました');
        } else {
            return back()->with('error_delete_blog', '予期せぬエラーが発生しました');
        }
    }

    /**
     * ブログお気に入り登録
     * @param array $request
     * @return view
     */
    public function registerFavoriteBlog(Request $request) {
        $inputData = [
            'user_id' => $request['login_user_id'],
            'blog_id' => $request['blog_id'],
        ];

        $result = $this->blogService->registerFavoriteBlog($inputData);

        if($result == true) {
            return redirect(route('blog_detail', ['id' => $inputData['blog_id']]))->with('success_register_favorite_blog', 'お気に入り登録しました');
        }
        return back()->with('error_register_favorite_blog', 'お気に入り登録できません、もしくは既にお気に入り登録されています');
    }

    /**
     * ブログお気に入り登録解除
     * @param array $request
     * @return view
     */
    public function deleteFavoriteBlog(Request $request) {
        $inputData = [
            'user_id' => $request['login_user_id'],
            'blog_id' => $request['blog_id'],
        ];

        $result = $this->blogService->deleteFavoriteBlog($inputData);

        if($result == true) {
            return redirect(route('blog_detail', ['id' => $inputData['blog_id']]))->with('success_register_favorite_blog', 'お気に入り登録を解除しました');
        }
        return back()->with('error_register_favorite_blog', 'お気に入り登録を解除できません、もしくは既にお気に入り登録が解除されています');
    }

    /**
     * コメント送信
     * @param BlogCommentRequest $request
     * @return view
     */
    public function postComment(BlogCommentRequest $request) {
        $inputData = [
            'blog_id'            => $request['target_blog'],
            'comment'            => $request['comment'],
            'created_by'         => $request['login_user_id'],
            'updated_by'         => $request['login_user_id'],
        ];

        $result = $this->blogService->postComment($inputData);
        
        if($result == true) {
            return redirect(route('blog_detail', ['id' => $inputData['blog_id']]));
        }
        return back()->with('error_post_comment', 'コメントを投稿できませんでした');
    }

    /**
     * 広告の登録
     * @param AdvertisementRequest $request
     * @return view
     */
    public function registerAdvertisement(AdvertisementRequest $request) {
        $inputData = [
            'advertisement_image_file' => $request['advertisement_image_file'],
            'url'                      => $request['url'],
            'blog_id'                  => $request['target_blog'],
            'created_by'               => $request['created_by'],
            'updated_by'               => $request['updated_by'],
        ];

        $result = $this->blogService->registerAdvertisement($inputData);

        if($result == false) {
            return back()->with('error_register_advertisement', '広告の登録時にエラーが発生しました');
        }
        return redirect(route('blog_detail', ['id' => $inputData['blog_id']]));
    }

    /**
     * 広告の削除
     * @param array $request
     * @param view
     */
    public function deleteAdvertisement(Request $request) {
        $inputData = [
            'id' => $request['advertisement_id'],
            'advertisement_image_name' => $request['advertisement_image_name'],
            'blog_id' => $request['blog_id'],
            'created_by' => $request['created_by'],
        ];

        $result = $this->blogService->deleteAdvertisement($inputData);

        if($result == false) {
            return back()->with('error_delete_advertisement', '広告の削除時にエラーが発生しました');
        }
        return redirect(route('blog_detail', ['id' => $inputData['blog_id']]));
    }
}
