<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopPageController extends Controller
{
    public function __construct() {
        
    }
    
    /**
     * トップページ初期表示
     */
    public function index() {
        return view('TopPage/toppage');
    }
}
