<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\TopPageController;


// トップページ
Route::get('/', [TopPageController::class, 'index'])->name('toppage');

// アカウント関連
Route::get('/accountRegisterationForm', [AccountController::class, 'accountRegisterationForm'])->name('account_registeration_form');
Route::post('/registerAccount', [AccountController::class, 'registerAccount'])->name('register_account');
Route::get('/loginForm', [AccountController::class, 'loginForm'])->name('login_form');
Route::post('/login', [AccountController::class, 'login'])->name('login');
Route::get('/logout', [AccountController::class, 'logout'])->name('logout');
Route::get('/profile', [AccountController::class, 'profileTop'])->name('profile_top');
Route::post('/updateProfile', [AccountController::class, 'updateProfile'])->name('update_profile');
Route::get('/deleteAccount', [AccountController::class, 'deleteAccount'])->name('delete_account');

// ブログ関連
Route::get('/blogPostingForm', [BlogController::class, 'blogPostingForm'])->name('blog_posting_form');
Route::post('/postBlog', [BlogController::class, 'postBlog'])->name('post_blog');
Route::post('/blogEditingForm', [BlogController::class, 'blogEditingForm'])->name('blog_editing_form');
Route::post('/eidtBlog', [BlogController::class, 'editBlog'])->name('edit_blog');
Route::post('/deleteBlog', [BlogController::class, 'deleteBlog'])->name('delete_blog');
Route::get('blogDedail/{id}', [BlogController::class, 'blogDetail'])->name('blogDetail');