<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\FavoriteBlog;
use App\Models\BrowsingHistory;
use App\Repositories\BlogRepository;
use App\Repositories\BlogCommentsRepository;
use App\Repositories\BrowsingHistoryRepository;
use App\Repositories\AccountRepository;
use App\Repositories\FavoriteBlogRepository;

use App\Const\BlogConst;
use Illuminate\Support\Facades\Auth;

class BlogService extends Service
{
    public $accountRepository;
    public $blogRepository;
    public $blogCommentsRepository;
    public $favoriteBlogRepository;
    public $BrowsingHistoryRepository;

    public function __construct() {

        // Modelのインスタンス化
        $user = new User;
        $blog = new Article;
        $blogComments = new ArticleComment;
        $favoriteBlog = new FavoriteBlog;
        $browsingHistory = new BrowsingHistory;

        // Repositryのインスタンス化
        $this->accountRepository = new AccountRepository($user);
        $this->blogRepository = new BlogRepository($blog);
        $this->blogCommentsRepository = new BlogCommentsRepository($blogComments);
        $this->favoriteBlogRepository = new FavoriteBlogRepository($favoriteBlog);
        $this->BrowsingHistoryRepository = new BrowsingHistoryRepository($browsingHistory);

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

        $checkDeleteBlog = $this->blogRepository->deleteBlog($inputData['id']);
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
     * トピックス取得
     */
    public function getTopics() {
        $topics = $this->blogRepository->getTopics();
        return $topics;
    }

    /**
     * ユーザーIDに紐づくブログからブログIDをもとに1件取得
     * @param $userId
     * @return $blogs
     */
    public function getBlogByUserId($inputData) {

        $loginUser = $this->accountRepository->getAccountById($inputData['user_id']);
        if(!$loginUser) {
            return [];
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

        $targetUser = $this->accountRepository->getAccountById($userId);
        if(!$targetUser) {
            return [];
        }

        $blogs = $this->blogRepository->getBlogsByUserId($userId);
        return $blogs;
    }

    /**
     * ユーザーIDに紐づくお気に入り登録したユーザーのブログ全件取得
     * @param $userId
     * @return $blogs
     */
    public function getBlogPostedByFavoriteUserByUserId($userId) {

        $targetUser = $this->accountRepository->getAccountById($userId);
        if(!$targetUser) {
            return [];
        }

        $getBlogPostedByFavoriteUser = $this->blogRepository->getBlogPostedByFavoriteUserByUserId($userId);
        return $getBlogPostedByFavoriteUser;
    }

    /**
     * ブログ詳細取得
     * @param $id
     * @return $blog
     */
    public function blogDetail($id) {
        $blog = $this->blogRepository->blogDetail($id);

        // 閲覧処理（閲覧数増加、閲覧履歴の登録）
        $this->blogRepository->increaseViewCount($id);
        if(Auth::id()) {
            $this->BrowsingHistoryRepository->upsertBrowsingHistory(Auth::id() ,$id);
        }

        $blog[0]['favorite_flag'] = false;
        if(Auth::user()) {
            // $checkFavoriteBlog = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId(Auth::id(), $id);
            $checkFavoriteBlog = $this->checkExsitsFavoriteBlogByBlogIdAndUserId(Auth::id(), $id);
            if($checkFavoriteBlog) {
                $blog[0]['favorite_flag'] = true;
            }
        }


        return $blog;
    }

    /**
     * ブログに紐づくコメントを取得
     * @param $id
     * @return $comments
     */
    public function getBlogComments($id) {
        $comments = $this->blogCommentsRepository->getBlogComments($id);

        return $comments;
    }

    /**
     * 対象ユーザーIDに紐づくお気に入り登録されたブログを全件取得
     * @param $id
     * @return $favoriteBlogs
     */
    public function getAllFavoriteBlogsByUserId($id) {
        if(!Auth::user() || Auth::id() != $id) {
            return [];
        }
        
        $checkExistsUser = $this->accountRepository->getAccountById($id);
        if(!$checkExistsUser) {
            return [];
        }

        $favoriteBlogs = $this->favoriteBlogRepository->getAllFavoriteBlogsByUserId($id);
        return $favoriteBlogs;
    }

    /**
     * ユーザーIDとブログIDをもとに対象のブログがお気に入り登録されているかを判定
     * @param $user_id, $blog_id
     * @return $result
     */
    public function checkExsitsFavoriteBlogByBlogIdAndUserId($user_id, $blog_id) {

        $checkExistsUser = $this->accountRepository->getAccountById($user_id);
        if(!$checkExistsUser) {
            return false;
        }

        $inputData = [
            'user_id' => $user_id,
            'blog_id' => $blog_id,
        ];

        $result = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId($inputData);
        
        return $result;
    }
    
    /**
     * ブログコメント登録
     * @param $inputData
     * @return $result
     */
    public function postComment($inputData) {
        $postCommentStauts = false;

        if(!Auth::user() || Auth::id() != $inputData['created_by']) {
            return $postCommentStauts;
        }

        $loginUser = $this->accountRepository->getAccountById($inputData['created_by']);
        if(!$loginUser) {
            return $postCommentStauts;
        }

        $result = $this->blogCommentsRepository->postComment($inputData);
        if($result) {
            $postCommentStauts = true;
        }

        return $postCommentStauts;
    }

    /**
     * ブログお気に入り登録
     * @param $inputData
     * @return $result
     */
    public function registerFavoriteBlog($inputData) {
        $registerFavoriteBlogStatus = false;

        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return $registerFavoriteBlogStatus;
        }

        $targetUser = $this->accountRepository->getAccountById($inputData['user_id']);
        if(!$targetUser) {
            return $registerFavoriteBlogStatus;
        }

        $targetBlog = $this->blogRepository->blogDetail($inputData['blog_id']);
        if(!$targetBlog) {
            return $registerFavoriteBlogStatus;
        }

        $checkExsitsFavoriteBlog = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId($inputData);
        if($checkExsitsFavoriteBlog) {
            return $registerFavoriteBlogStatus;
        }

        $result = $this->favoriteBlogRepository->registerFavoriteBlog($inputData);
        if($result == true) {
            $registerFavoriteBlogStatus = true;
        }

        return $registerFavoriteBlogStatus;
    }

    /**
     * ブログお気に入り登録解除
     * @param $inputData
     * @return $result
     */
    public function deleteFavoriteBlog($inputData) {
        $deleteFavoriteBlogStatus = false;

        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return $deleteFavoriteBlogStatus;
        }

        $targetUser = $this->accountRepository->getAccountById($inputData['user_id']);
        if(!$targetUser) {
            return $deleteFavoriteBlogStatus;
        }

        $targetBlog = $this->blogRepository->blogDetail($inputData['blog_id']);
        if(!$targetBlog) {
            return $deleteFavoriteBlogStatus;
        }

        $checkExsitsFavoriteBlog = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId($inputData);
        if(!$checkExsitsFavoriteBlog) {
            return $deleteFavoriteBlogStatus;
        }

        $result = $this->favoriteBlogRepository->deleteFavoriteBlog($inputData);
        if($result == true) {
            $deleteFavoriteBlogStatus = true;
        }

        return $deleteFavoriteBlogStatus;
    }

    /**
     * 閲覧履歴
     */
    public function showBrowsingHistory($inputData) {
        $targetUser = $this->accountRepository->getAccountById($inputData['user_id']);
        if(!$targetUser) {
            return false;
        }

        $browsingHistory = $this->BrowsingHistoryRepository->getBrowsingHisotryByUserId($inputData['user_id']);
        return $browsingHistory;

    }


}