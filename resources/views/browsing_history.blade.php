@extends('CommonParts.app')

@section('head')
    @parent
    <title>閲覧履歴</title>
@endsection

@section('contents')
    <div class="main-contents">
        <div class="main-contents-wrapper blog-list">
            <h2>閲覧履歴</h2>
            @include('blog_unit', ['word' => '閲覧履歴がありません'])
        </div>
    </div>
@endsection