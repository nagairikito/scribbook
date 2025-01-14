<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\TopPageController;


// トップページ
Route::get('/', [TopPageController::class, 'index'])->name('toppage');

// アカウント関連
Route::get('/accountRegisterationForm', [AccountController::class, 'accountRegisterationForm'])->name('account_registeration_form');
Route::post('/registerAccousnt', [AccountController::class, 'registerAccount'])->name('register_account');
Route::get('/loginForm', [AccountController::class, 'loginForm'])->name('login_form');
Route::post('/login', [AccountController::class, 'login'])->name('login');
Route::get('/logout', [AccountController::class, 'logout'])->name('logout');
Route::get('/profile/{id}', [AccountController::class, 'profileTop'])->name('profile_top');
Route::post('/updateProfile', [AccountController::class, 'updateProfile'])->name('update_profile');
Route::get('/deleteAccount', [AccountController::class, 'deleteAccount'])->name('delete_account');

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