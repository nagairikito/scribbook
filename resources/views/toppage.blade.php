@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
    <title>ScribBook</title>
@endsection

@section('contents')
    <div class="main-contents">
        @include('session_messages')
            <li>ブログのサムネイル</li>
            <li>プロフィールのアイコン画像削除</li>
            <li>検索後画面</li>

        <div class="blog-list">
            <h2>トップ</h2>
            @include('blog_unit', ['word' => 'ブログがありません'])
        </div>
    </div>
@endsection