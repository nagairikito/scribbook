<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CommonParts/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CommonParts/main.css') }}">
    <script src="{{ asset('js/CommonParts/getScreenSize.js') }}" defer></script>

    <title>アカウント新規登録フォーム</title>
</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">
                    <div class="main-contents-wrapper">
                        <h1>アカウント新規登録フォーム</h1>
                        <div class="account-register-form-wrapper">
                            <form action="{{ route('register_account') }}" method="POST">
                                @csrf
                                <p>ユーザー名</p>
                                <input type="text" name="name">
                                @if($errors->has('name'))
                                    <p class="error-message">{{ $errors->first('name') }}</p>
                                @endif

                                <p>ログインID</p>
                                <input type="text" name="login_id">
                                @if($errors->has('login_id'))
                                    <p class="error-message">{{ $errors->first('login_id') }}</p>
                                @endif

                                <p>パスワード</p>
                                <input type="password" name="password">
                                @if($errors->has('password'))
                                    <p class="error-message">{{ $errors->first('password') }}</p>
                                @endif

                                <p>確認用パスワード</p> 
                                <input type="password" name="password_confirmation"><br>

                                <input type="submit" value="新規登録">
                                @if(session('error_account_registeraion'))
                                    <p class="error-message">{{ session('error_account_registeraion') }}</p>
                                @endif
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