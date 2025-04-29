<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/main.css') }}">
    <script src="{{ asset('js/a_CommonParts/getScreenSize.js') }}" defer></script>

    <title>ブログ投稿フォーム</title>
</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">    
                    <div class="main-contents-wrapper">
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
                </div>

                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')
</body>
</html>