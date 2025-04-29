<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogPostingRequest;
use App\Const\BlogConst;
use Illuminate\Support\Facades\Auth;


use App\Models\Article;
use App\Repositories\BlogRepository;
use App\Services\BlogService;

class BlogController extends Controller
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
     * トピックス表示
     */
    public function topics() {
        $allBlogs = $this->blogService->getTopics();

        return view('Blog/topics', ['allBlogs' => $allBlogs]);
    }

    /**
     * マイブログ表示
     */
    public function showMYBlogs(Request $request) {
        $inputData = [
            'user_id' => $request['id']
        ];

        $myBlogs = $this->blogService->getBlogsByUserId($inputData['user_id']);

        return view('Blog/my_blogs', compact('myBlogs'));

    }

    /**
     * ユーザーIDに紐づくお気に入り登録したユーザーのブログ全件取得
     * @param $request
     * @return view
     */
    public function getBlogPostedByFavoriteUserByUserId(Request $request) {
        $inputData = [
            'user_id' => $request['id'],
        ];
        $blogsPostedByFavoriteUser = $this->blogService->getBlogPostedByFavoriteUserByUserId($inputData['user_id']);

        return view('Blog/favorite_user_blogs', compact('blogsPostedByFavoriteUser'));
    }

    
    /**
     * ブログ投稿フォーム
     */
    public function blogPostingForm() {
        return view('Blog/blog_posting_form');
    }

    /**
     * ブログ投稿
     * @param $request
     * @return view
     */
    public function postBlog(BlogPostingRequest $request) {
        $inputData = [
            'user_id' => $request['user_id'],
            'title' => $request['title'],
            'contents' => $request['contents'],
        ];

        $result = $this->blogService->postBlog($inputData);

        switch($result) {
            case BlogConst::FAIL_REGISTER_USER_AUTHENTICATION:
                return back()->with('error_post_blog', 'セッションが切れています');

            case BlogConst::NOT_FOUND_REGISTER_USER_ID:
                return back()->with('error_post_blog', '投稿できませんでした');

            case BlogConst::SUCCESS_BLOG_REGISTERATION:
                return redirect(route('toppage'))->with('success_post_blog', 'ブログを投稿しました');

            default:
                return back()->with('error_post_blog', '予期せぬエラーが発生しました');
        }

    }

    /**
     * ブログ編集フォーム
     * @param $request
     * @return view
     */
    public function blogEditingForm(Request $request) {
        $inputData = [
            'id' => $request['blog_id'],
            'user_id' => $request['login_user_id'],
        ];

        $blog = $this->blogService->getBlogByUserId($inputData);

        if(empty($blog)) {
            return redirect(route('toppage'))->with('error_get_blog_detail', '予期せぬエラーが発生しました');
        }

        return view('Blog/blog_editing_form', ['blog' => $blog]);
    }

    /**
     * ブログ編集
     * @param $request
     * @return view
     */
    public function editBlog(BlogPostingRequest $request) {
        $inputData = [
            'id' => $request['blog_id'],
            'title' => $request['title'],
            'contents' => $request['contents'],
            'created_by' => $request['login_user_id'],
        ];

        $result = $this->blogService->editBlog($inputData);

        switch($result) {
            case BlogConst::FAIL_EDIT_USER_AUTHENTICATION;
                return back()->with('error_edit_blog', 'セッションが切れています');

            case BlogConst::NOT_FOUND_EDIT_USER_ID;
                return back()->with('error_edit_blog', 'ブログを更新できません');

            case BlogConst::SUCCESS_BLOG_EDITING;
                return redirect(route('profile_top', ['id' => Auth::id()]))->with('success_edit_blog', 'ブログを更新しました');

            default;
                return back()->with('error_edit_blog', '予期せぬエラーが発生しました');
        }
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

        switch($result) {
            case BlogConst::FAIL_DELETE_USER_AUTHENTICATION;
                return back()->with('error_delete_blog', 'セッションが切れています');

            case BlogConst::NOT_FOUND_DELETE_USER_ID;
                return back()->with('error_delete_blog', 'ブログを削除できません');

            case BlogConst::SUCCESS_BLOG_DELETING;
                return redirect(route('profile_top', ['id' => Auth::id()]))->with('success_delete_blog', 'ブログを削除しました');

            default;
                return back()->with('error_delete_blog', '予期せぬエラーが発生しました');
        }

    }

    /**
     * ブログ詳細取得
     * @param $id
     * @return $blog
     */
    public function blogDetail($id) {
        $blog = $this->blogService->blogDetail($id);
        $comments = $this->blogService->getBlogComments($id);

        if(empty($blog)) {
            return back()->with('error_get_blog_detail', 'ブログが見つかりませんでした');
        }

        return view('Blog/blog_detail', ['blog' => $blog, 'comments' => $comments]);
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
     * お気に入りブログ一覧を表示
     * @param $id
     * @return $favoriteBlogs
     */
    public function getFavoriteBlogs(Request $request) {
        $inputData = [
            'id' => $request['id'],
        ];

        $favoriteBlogs = $this->blogService->getAllFavoriteBlogsByUserId($inputData['id']);

        return view('Blog/favorite_blogs', ['favoriteBlogs' => $favoriteBlogs]);
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
     * 閲覧履歴表示
     */
    public function showBrowsingHistory(Request $request) {
        $inputData = [
            'user_id' => $request['id'],
        ];

        $blogs = $this->blogService->showBrowsingHistory($inputData);

        return view('Blog/browsing_history', compact('blogs'));

    }
}
