<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/header.css') }}">

</head>
<body>
    <header id="header">
        <div class="header-wrapper">
            <div class="header-logo">
                <div class="header-logo-wrapper"><a href="{{ route('toppage') }}">mark</a></div>
            </div>
            <div class="header-search-bar">
                <form action="{{ route('search') }}" method="GET" class="header-search-bar-wrapper">
                @csrf
                    <input class="search-textbox" type="search" name="keyword">
                    <input class="search-button" type="submit" value="検索🔍">
                </form>
            </div>
            <div class="header-nav">
                <ul class="header-nav-list">
                    @if(Auth::user())
                        <li><a href="{{ route('blog_posting_form') }}">投稿</a></li>
                        <li><a href="">通知</a></li>
                        <li><a href="{{ route('profile_top', ['id' => Auth::id()]) }}">アカウント</a></li>
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