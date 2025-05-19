@extends('CommonParts.app')

@section('head')
    @parent
    <script src="{{ asset('js/blogEditing.js') }}" defer></script>
    <title>編集フォーム_{{ $blog[0]['title'] }}</title>
@endsection

@section('contents')
    <div class="main-contents">
    <h1>ブログ編集フォーム</h1>
        <div class="post-blog-form-wrapper">
            @if(session('success_delete_blog'))
                <p>{{ session('success_delete_blog') }}</p>
            @endif
            <form id="blog-editing-form" action="{{ route('edit_blog') }}" method="POST">
            @csrf
                <h2>タイトル</h2>
                <input type="text" name="title" value="{{ $blog[0]['title'] }}">
                @if($errors->has('title'))
                    <p class="error-message">{{ $errors->first('title') }}</p>
                @endif
                <h2>コンテンツ</h2>
                <textarea id="replacement-contents" name="contents" style="display: none;"></textarea>
                <div id="original-contents" contenteditable="true">{!! $blog[0]['contents'] !!}</div>
                @if($errors->has('contents'))
                    <p class="error-message">{{ $errors->first('contents') }}</p>
                @endif
                <br>
                <input type="submit" value="投稿">
                @if(session('error_post_blog'))
                    <p class="error-message">{{ session('error_post_blog') }}</p>
                @endif

                <input type="hidden" name="blog_id" value="{{ $blog[0]['id'] }}">
                <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
            </form>
        </div>
    </div>
@endsection