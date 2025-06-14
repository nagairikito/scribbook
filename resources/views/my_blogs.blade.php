@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
    <title>マイブログ</title>
@endsection

@section('contents')
    <div class="main-contents">
        <h1>マイブログ</h1>
        @include('blog_unit', ['word' => '投稿がありません'])
    </div>
@endsection