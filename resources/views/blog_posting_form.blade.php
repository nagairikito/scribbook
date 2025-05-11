@extends('CommonParts.app')

@section('head')
    @parent
    <title>ブログ投稿フォーム</title>
@endsection

@section('contents')
    <div class="main-contents">
        <h1>ブログ投稿フォーム</h1>
        <div class="post-blog-form-wrapper">
            <form action="{{ route('post_blog') }}" method="POST">
            @csrf
                <h2>タイトル</h2>
                <input type="text" name="title">
                @if($errors->has('title'))
                    <p class="error-message">{{ $errors->first('title') }}</p>
                @endif
                <h2>コンテンツ</h2>
                <textarea name="contents" cols="150" rows="30"></textarea>
                @if($errors->has('contents'))
                    <p class="error-message">{{ $errors->first('contents') }}</p>
                @endif
                <br>
                <input type="submit" value="投稿">
                @if(session('error_post_blog'))
                    <p class="error-message">{{ session('error_post_blog') }}</p>
                @endif

                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
            </form>
        </div>
    </div>
@endsection