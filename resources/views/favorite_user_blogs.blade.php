@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
    <title>お気に入りユーザーのブログ</title>
@endsection

@section('contents')
    <div class="main-contents">
        <div class="blog-list">
            <h2>お気に入りユーザーのブログ</h2>
            @include('blog_unit', ['word' => 'ブログがありません'])
        </div>
    </div>
@endsection