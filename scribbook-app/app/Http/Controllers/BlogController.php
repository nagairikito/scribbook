<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Article;
use App\Repositories\BlogRepository;
use App\Services\BlogService;

use App\Const\BlogConst;
use App\Http\Requests\BlogPostingRequest;

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
        return view('Blog/blog_editing_form', ['blog' => $blog]);
    }

    /**
     * ブログ編集
     * @param $request
     * @return view
     */
    public function blogEdit(BlogPostingRequest $request) {
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
                return view('TopPage/toppage')->with('success_edit_blog', 'ブログを更新しました');

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
                return view('TopPage/toppage')->with('success_delete_blog', 'ブログを削除しました');

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

        return view('Blog/blog_detail', ['blog' => $blog]);
    }



}
