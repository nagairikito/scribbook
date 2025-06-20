@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
    <title>ScribBook</title>
@endsection

@section('contents')
    <div class="main-contents">
        @include('session_messages')
        <div class="blog-list">
            <h2>トピックス</h2>
            @include('blog_unit', ['word' => 'トピックスがありません'])
        </div>
    </div>
@endsection