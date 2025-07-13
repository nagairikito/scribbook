@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
    <meta name="robots" content="index, follow">
    <meta name="description" content="自由帳感覚で書けるブログです。">
    <title>ScribBook</title>
    <meta name="google-site-verification" content="pbytO9E5mWVEeBLZsKppXwgMLprUyzWuya5kzsMYmww" />
@endsection

@section('contents')
    <div class="main-contents">
        @include('session_messages')

        <div class="blog-list">
            <h2>トップ</h2>
            @include('blog_unit', ['word' => 'ブログがありません'])
        </div>
    </div>
@endsection