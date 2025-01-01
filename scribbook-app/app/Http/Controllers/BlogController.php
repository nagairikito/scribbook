<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    
    public function __construct() {
        
    }
    
    /**
     * ブログ投稿フォーム
     */
    public function postBlogForm() {
        return view('Blog/post_blog_form');
    }

    /**
     * ブログ投稿
     */
    public function postBlog() {

    }


}
