<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use App\Repositories\BlogRepository;
use App\Repositories\AccountRepository;

use App\Const\BlogConst;
use Illuminate\Support\Facades\Auth;

class BlogService extends Service
{
    public $blogRepository;
    public $accountRepository;
    public function __construct() {

        // Modelのインスタンス化
        $blog = new Article;
        $user = new User;

        // Repositryのインスタンス化
        $this->blogRepository = new BlogRepository($blog);
        $this->accountRepository = new AccountRepository($user);

    }
    
    /**
     * ブログ登録
     * @param $inputData
     * @return bool $blogPostStatus
     */
    public function postBlog($inputData) {
        $postBlogStatus = BlogConst::REGISTER_INITIAL_VALUE;

        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            $postBlogStatus = BlogConst::FAIL_REGISTER_USER_AUTHENTICATION;
            return $postBlogStatus;
        }

        $created_by = $this->accountRepository->getAccountById($inputData['user_id']);
        if(!$created_by) {
            $postBlogStatus = BlogConst::NOT_FOUND_REGISTER_USER_ID;
            return $postBlogStatus;
        }

        $checkPostBlog = $this->blogRepository->postBlog($inputData);
        if($checkPostBlog) {
            $postBlogStatus = BlogConst::SUCCESS_BLOG_REGISTERATION;
            return $postBlogStatus;
        }

        return $postBlogStatus;

    }

    /**
     * ブログ編集
     * @param $inputData
     * @return $result
     */
    public function editBlog($inputData) {
        $editBlogStatus = BlogConst::EDIT_INITIAL_VALUE;

        if(!Auth::user() || Auth::id() != $inputData['created_by']) {
            $editBlogStatus = BlogConst::FAIL_EDIT_USER_AUTHENTICATION;
            return $editBlogStatus;
        }

        $created_by = $this->accountRepository->getAccountById($inputData['created_by']);
        if(!$created_by) {
            $editBlogStatus = BlogConst::NOT_FOUND_EDIT_USER_ID;
            return $editBlogStatus;
        }

        $checkEditBlog = $this->blogRepository->editBlog($inputData);
        if($checkEditBlog) {
            $editBlogStatus = BlogConst::SUCCESS_BLOG_EDITING;
            return $editBlogStatus;
        }

        return $editBlogStatus;

    }
    
    /**
     * ブログ削除
     * @param $inputData
     * @return $result
     */
    public function deleteBlog($inputData) {
        $deleteBlogStatus = BlogConst::DELETE_INITIAL_VALUE;

        if(!Auth::user() || Auth::id() != $inputData['created_by']) {
            $deleteBlogStatus = BlogConst::FAIL_DELETE_USER_AUTHENTICATION;
            return $deleteBlogStatus;
        }

        $created_by = $this->accountRepository->getAccountById($inputData['created_by']);
        if(!$created_by) {
            $deleteBlogStatus = BlogConst::NOT_FOUND_DELETE_USER_ID;
            return $deleteBlogStatus;
        }

        $checkDeleteBlog = $this->blogRepository->editBlog($inputData['id']);
        if($checkDeleteBlog) {
            $deleteBlogStatus = BlogConst::SUCCESS_BLOG_DELETING;
            return $deleteBlogStatus;
        }

        return $deleteBlogStatus;

    }

    /**
     * ブログ全件取得
     * @return $allBlogs
     */
    public function getAllBlogs() {
        $allBlogs = $this->blogRepository->getAllBlogs();
        return $allBlogs;
    }

    /**
     * ユーザーIDに紐づくブログからブログIDをもとに1件取得
     * @param $userId
     * @return $blogs
     */
    public function getBlogByUserId($inputData) {
        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return;
        }

        $loginUser = $this->accountRepository->getAccountById($inputData['user_id']);
        if(!$loginUser) {
            return;
        }

        $blogs = $this->blogRepository->getBlogByUserId($inputData['id']);
        return $blogs;
    }

    /**
     * ユーザーIDに紐づくブログを全件取得
     * @param $userId
     * @return $blogs
     */
    public function getBlogsByUserId($userId) {
        if(!Auth::user() || Auth::id() != $userId) {
            return;
        }

        $loginUser = $this->accountRepository->getAccountById($userId);
        if(!$loginUser) {
            return;
        }

        $blogs = $this->blogRepository->getBlogsByUserId($userId);
        return $blogs;
    }

    /**
     * ブログ詳細取得
     * @param $id
     * @return $blog
     */
    public function blogDetail($id) {
        $blog = $this->blogRepository->blogDetail($id);
        return $blog;
    }

}