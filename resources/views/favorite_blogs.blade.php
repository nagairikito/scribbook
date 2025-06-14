@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
    <title>お気に入りブログ</title>
@endsection

@section('contents')
    <div class="main-contents">
        <h1>お気に入りブログ</h1>
        @include('blog_unit', ['word' => 'お気に入り登録したブログはありません'])
    </div>
@endsection