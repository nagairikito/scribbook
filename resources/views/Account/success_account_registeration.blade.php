<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了のお知らせ</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CommonParts/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CommonParts/main.css') }}">
    <script src="{{ asset('js/CommonParts/getScreenSize.js') }}" defer></script>

</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">
                    <div>
                        <h1>登録完了のお知らせ</h1>
                        <p>登録が完了しました</p><br>
                        <p>ログインは<a href="{{ route('login_form') }}" class="underline">こちら</a>から</p><br>
                        <a href="{{ route('toppage') }}" class="underline">トップページへ戻る</p>
                    </div>
                </div>

                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')

</body>
</html>