<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogPostingRequest;
use App\Const\BlogConst;
use Illuminate\Support\Facades\Auth;


use App\Models\Article;
use App\Repositories\BlogRepository;
use App\Services\BlogService;

class BlogDetailController extends Controller
{
    public $blogService;

    public function __construct() {
        // Modelのインスタンス化
        $blog = new Article;
        
        // Repositoryのインスタンス化
        $blogRepository = new BlogRepository($blog);

        // Serviceのインスタンス化
        $this->blogService = new BlogService($blogRepository);

    }

    /**
     * ブログ詳細取得
     * @param $id
     * @return $blog
     */
    public function blogDetail($id) {
        $blog = $this->blogService->blogDetail($id);
        if(empty($blog)) {
            return back()->with('error_get_blog_detail', 'ブログが見つかりませんでした');
        }
        $comments = $this->blogService->getBlogComments($id);
        $advertisement = $this->blogService->getAdvertisementByBlogId($id);

        return view('blog_detail', ['blog' => $blog, 'comments' => $comments, 'advertisement' => $advertisement, 'blog_detail_flag' => true]);
    }

    /**
     * ブログ削除
     * @param $request
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
     * @param $request
     * @return $result
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
     * @param $request
     * @return $result
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
     * @param $request
     * @return $result
     */
    public function postComment(Request $request) {
        $inputData = [
            'target_article'     => $request['target_blog'],
            'comment'            => $request['comment'],
            'created_by'         => $request['login_user_id'],
        ];

        $result = $this->blogService->postComment($inputData);
        
        if($result == true) {
            return redirect(route('blog_detail', ['id' => $inputData['target_article']]));
        }
        return back()->with('error_post_comment', 'コメントを投稿できませんでした');
    }

    /**
     * 広告の登録
     */
    public function registerAdvertisement(Request $request) {
        dd($request);
        $inputData = [
            'advertisement_image_file' => $request['advertisement_image_file'],
            'url' => $request['url'],
            'blog_id' => $request['target_blog'],
            'created_by' => $request['created_by'],
        ];

        $result = $this->blogService->registerAdvertisement($inputData);

        if($result == false) {
            return back()->with('error_register_advertisement', '広告の登録時にエラーが発生しました');
        }
            return redirect(route('blog_detail', ['id' => $inputData['blog_id']]));
    }

    /**
     * 広告の削除
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
