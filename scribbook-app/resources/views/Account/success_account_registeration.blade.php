<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了のお知らせ</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/a_CommonParts/header.css">
</head>
<body>
    @include('a_CommonParts.header')

    <div>
        <h1>登録完了のお知らせ</h1>
        <p>登録が完了しました</p><br>
        <p>ログインは<a href="{{ route('login_form') }}" class="underline">こちら</a>から</p><br>
        <a href="{{ route('toppage') }}" class="underline">トップページへ戻る</p>
    </div>

    @include('a_CommonParts.footer')

</body>
</html>