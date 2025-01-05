<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/header.css') }}">
</head>
<body>
    <header>
        <div class="header-wrapper">
            <div class="header-logo">
                <div class="header-logo-wrapper"><a href="{{ route('toppage') }}">mark</a></div>
            </div>
            <div class="header-search-bar">
                <div class="header-search-bar-wrapper">
                    <input class="search-textbox" type="search">
                    <input class="search-button" type="submit" value="検索🔍">
                </div>
            </div>
            <div class="header-nav">
                <ul class="header-nav-list">
                    <li><a href="{{ route('blog_posting_form') }}">投稿</a></li>
                    <li><a href="">後で見る</a></li>
                    @if(Auth::user())
                        <li><a href="{{ route('profile_top')}}">アカウント</a></li>
                    @endif
                    @if(!Auth::user())
                        <li><a href="{{ route('account_registeration_form') }}">新規作成</a></li>
                        <li><a href="{{ route('login_form') }}">ログイン</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </header>
</body>
</html>