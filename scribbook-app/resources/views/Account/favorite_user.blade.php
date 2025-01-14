<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お気に入りユーザー</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/header.css') }}">
</head>
<body>
    @include('a_CommonParts.header')

    <main>
        <div class="main-wrapper">
            <h1>お気に入りユーザー</h1>
            @if(count($favoriteUsers) > 0)
                <ul>
                    @foreach($favoriteUsers as $favoriteUser)
                        <li>
                            <div>{{ $favoriteUser['icon_image'] }}</div>
                            <p>{{ $favoriteUser['name'] }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>お気に入り登録したユーザーはいません</p>
            @endif
        </div>
    </main>

    @include('a_CommonParts.footer')

</body>
</html>