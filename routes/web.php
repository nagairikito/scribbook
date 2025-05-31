<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopPageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AccountRegistrationController;
use App\Http\Controllers\MyBlogController;
use App\Http\Controllers\BlogDetailController;
use App\Http\Controllers\BlogEditingController;
use App\Http\Controllers\BlogPostingController;
use App\Http\Controllers\BrowsingHistoryController;
use App\Http\Controllers\FavoriteBlogController;
use App\Http\Controllers\FavoriteUserBlogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TalkRoomController;
use App\Http\Controllers\TalkRoomDetailController;
use App\Http\Controllers\TopicsController;
use App\Http\Middleware\AuthMiddleware;


// トップページ
Route::get('/', [TopPageController::class, 'index'])->name('toppage');

// 検索
Route::get('/search', [SearchController::class, 'search'])->name('search');

// アカウント関連
Route::get('/accountRegisterationForm', [AccountRegistrationController::class, 'accountRegisterationForm'])->name('account_registeration_form');
Route::post('/registerAccousnt', [AccountRegistrationController::class, 'registerAccount'])->name('register_account');
Route::get('/loginForm', [LoginController::class, 'loginForm'])->name('login_form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// ブログ関連
Route::get('/topics', [TopicsController::class, 'topics'])->name('topics');
Route::get('/blogDedail/{id}', [BlogDetailController::class, 'blogDetail'])->name('blog_detail');


// ログイン認証していない場合ログインフォームに遷移
Route::middleware([AuthMiddleware::class])->group(function () {
    // アカウント関連
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile/{id}', [ProfileController::class, 'profileTop'])->name('profile_top');
    Route::get('/registerFavoriteUser', [ProfileController::class, 'registerFavoriteUser'])->name('create_favorite_user_registeration'); //profile_topがリクエストパラメータ付きURL（GETメソッド）であるためそのページでのPOSTメソッド記述はできない、そのためユーザーお気に入り登録処理を一度GETメソッドで取得する
    Route::post('/registerFavoriteUser', [ProfileController::class, 'registerFavoriteUser'])->name('register_favorite_user');
    Route::get('/deleteFavoriteUser', [ProfileController::class, 'deleteFavoriteUser'])->name('create_favorite_user_deleting'); //profile_topがリクエストパラメータ付きURL（GETメソッド）であるためそのページでのPOSTメソッド記述はできない、そのためユーザーお気に入り登録処理を一度GETメソッドで取得する
    Route::post('/deleteFavoriteUser', [ProfileController::class, 'deleteFavoriteUser'])->name('delete_favorite_user');
    Route::post('/updateProfile', [ProfileController::class, 'updateProfile'])->name('update_profile');
    Route::get('/deleteAccount', [ProfileController::class, 'deleteAccount'])->name('delete_account');
    Route::post('/deleteUserIcon', [ProfileController::class, 'deleteIconImageFromStorage'])->name('delete_user_icon');
    
    // トーク
    Route::get('/talkRoomList/{id}', [TalkRoomController::class, 'showTalkRoomList'])->name('talk_room_list');
    Route::get('/talkRoom', [TalkRoomDetailController::class, 'displayTalkRoom'])->name('display_talk_room');
    // Route::get('/talkRoom/{sender}/{recipient}', [TalkRoomDetailController::class, 'displayTalkRoom'])->name('display_talk_room_has_url_param');
    // Route::get('/sendMessage', [TalkRoomDetailController::class, 'sendMessage'])->name('create_send_message');

    // ブログ関連
    Route::get('/blogPostingForm', [BlogPostingController::class, 'blogPostingForm'])->name('blog_posting_form');
    Route::post('/postBlog', [BlogPostingController::class, 'postBlog'])->name('post_blog');
    Route::get('/blogEditingForm', [BlogEditingController::class, 'blogEditingForm'])->name('blog_editing_form');
    Route::post('/editBlog', [BlogEditingController::class, 'editBlog'])->name('edit_blog');
    Route::post('/deleteBlog', [BlogDetailController::class, 'deleteBlog'])->name('delete_blog');
    Route::post('/postComment', [BlogDetailController::class, 'postComment'])->name('post_comment');
    Route::post('/reisterFavoriteBlog', [BlogDetailController::class, 'registerFavoriteBlog'])->name('register_favorite_blog');
    Route::post('/deleteFavoriteBlog', [BlogDetailController::class, 'deleteFavoriteBlog'])->name('delete_favorite_blog');
    Route::get('/getBlogPostedByFavoriteUserByUserId/{id}', [FavoriteUserBlogController::class, 'getBlogPostedByFavoriteUserByUserId'])->name('create_favorite_user_blogs'); 
    Route::post('/getBlogPostedByFavoriteUserByUserId/{id}', [FavoriteUserBlogController::class, 'getBlogPostedByFavoriteUserByUserId'])->name('favorite_user_blogs');
    Route::get('/favoriteBlogs/{id}', [FavoriteBlogController::class, 'getFavoriteBlogs'])->name('favorite_blogs');
    Route::get('/myBlogs/{id}', [MyBlogController::class, 'showMYBlogs'])->name('my_blogs');
    Route::get('/BrowsingHistory/{id}', [BrowsingHistoryController::class, 'showBrowsingHistory'])->name('show_browsing_history');

    //広告
    Route::post('/registerAdvertisement', [BlogDetailController::class, 'registerAdvertisement'])->name('register_advertisement');
    Route::post('/deleteAdvertisement', [BlogDetailController::class, 'deleteAdvertisement'])->name('delete_advertisement');

});

// API
Route::post('/sendMessage', [TalkRoomDetailController::class, 'sendMessage'])->name('send_message');
// Route::get('/getLatestMessageBySender', [TalkRoomDetailController::class, 'getLatestMessageBySender'])->name('get_message');
Route::get('/getMessages', [TalkRoomDetailController::class, 'getMessages'])->name('get_messages');
Route::get('/getTalkRoomList', [TalkRoomController::class, 'getTalkRoomList'])->name('get_talk_room_list');

