<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopPageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BlogController;


// トップページ
Route::get('/', [TopPageController::class, 'index'])->name('toppage');

// 検索
Route::get('/search', [SearchController::class, 'search'])->name('search');

// アカウント関連
Route::get('/accountRegisterationForm', [AccountController::class, 'accountRegisterationForm'])->name('account_registeration_form');
Route::post('/registerAccousnt', [AccountController::class, 'registerAccount'])->name('register_account');
Route::get('/loginForm', [AccountController::class, 'loginForm'])->name('login_form');
Route::post('/login', [AccountController::class, 'login'])->name('login');
Route::get('/logout', [AccountController::class, 'logout'])->name('logout');
Route::get('/profile/{id}', [AccountController::class, 'profileTop'])->name('profile_top');
Route::get('/registerFavoriteUser', [AccountController::class, 'registerFavoriteUser'])->name('create_favorite_user_registeration'); //profile_topがリクエストパラメータ付きURL（GETメソッド）であるためそのページでのPOSTメソッド記述はできない、そのためユーザーお気に入り登録処理を一度GETメソッドで取得する
Route::post('/registerFavoriteUser', [AccountController::class, 'registerFavoriteUser'])->name('register_favorite_user');
Route::get('/deleteFavoriteUser', [AccountController::class, 'deleteFavoriteUser'])->name('create_favorite_user_deleting'); //profile_topがリクエストパラメータ付きURL（GETメソッド）であるためそのページでのPOSTメソッド記述はできない、そのためユーザーお気に入り登録処理を一度GETメソッドで取得する
Route::post('/deleteFavoriteUser', [AccountController::class, 'deleteFavoriteUser'])->name('delete_favorite_user');
Route::post('/updateProfile', [AccountController::class, 'updateProfile'])->name('update_profile');
Route::get('/deleteAccount', [AccountController::class, 'deleteAccount'])->name('delete_account');
Route::post('deleteUserIcon', [AccountController::class, 'deleteIconImageFromStorage'])->name('delete_user_icon');

// ブログ関連
Route::get('/blogPostingForm', [BlogController::class, 'blogPostingForm'])->name('blog_posting_form');
Route::post('/postBlog', [BlogController::class, 'postBlog'])->name('post_blog');
Route::post('/blogEditingForm', [BlogController::class, 'blogEditingForm'])->name('blog_editing_form');
Route::post('/editBlog', [BlogController::class, 'editBlog'])->name('edit_blog');
Route::post('/deleteBlog', [BlogController::class, 'deleteBlog'])->name('delete_blog');
Route::get('/blogDedail/{id}', [BlogController::class, 'blogDetail'])->name('blog_detail');
Route::post('/postComment', [BlogController::class, 'postComment'])->name('post_comment');
Route::post('/reisterFavoriteBlog', [BlogController::class, 'registerFavoriteBlog'])->name('register_favorite_blog');
Route::post('/deleteFavoriteBlog', [BlogController::class, 'deleteFavoriteBlog'])->name('delete_favorite_blog');
Route::get('/favoriteBlogs/{id}', [BlogController::class, 'getFavoriteBlogs'])->name('favorite_blogs_page');