@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogPosting.css') }}">
    <script src="{{ asset('js/blogPosting.js') }}" defer></script>
    <title>ブログ投稿フォーム</title>
@endsection

@section('contents')
    <div id="blog-posting" class="main-contents">
        <div class="main-contents-wrapper">
            <h1>ブログ投稿フォーム</h1>
            <div class="blog-posting-form-wrapper">
                <form id="blog-posting-form" action="{{ route('post_blog') }}" method="POST">
                @csrf
                    <h2>タイトル</h2>
                    <input type="text" name="title">
                    @if($errors->has('title'))
                        <p class="error-message">{{ $errors->first('title') }}</p>
                    @endif
                    <h2>コンテンツ</h2>
                    <textarea id="replacement-contents" name="contents" style="display: none;"></textarea>
                    <div id="original-contents" contenteditable="true"><br></div>

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
                <div>
                    <div class="tool-list">
                        <div>
                            <button onclick="addImage()">画像追加</button>
                        </div>
                        <div>
                            <button id="color-selector-button" onclick="showColorSelector()">文字色</button>
                            <!-- <input type="color" id="color-selector" name="color" style="display: none;" value="#888888"> -->
                            <input type="color" id="color-selector" name="color" style="display: none;" value="#ffffff" onchange="upateColor(this.value)">
                        </div>
                    </div>
                    <div class="tool-setting">
                        <div class="tool-setting-field"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection