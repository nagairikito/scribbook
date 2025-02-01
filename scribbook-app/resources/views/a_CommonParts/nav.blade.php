<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">

</head>
<body>

<div class="nav">
    <ul class="nav-wrapper">
            <li><a href="{{ route('toppage') }}">トップ</a></li>
            <li><a href="">トピックス</a></li>
        @if(Auth::user())
            <li>評価したユーザー</li>
            <li><a href="{{ route('favorite_blogs_page', ['id' => Auth::id()]) }}">評価したブログ</a></li>
            <li<a href="">履歴</a></li>
            <li<a href="">トーク</a></li>
        @endif
    </ul>
</div>

</body>
</html>