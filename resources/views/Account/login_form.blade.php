<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインフォーム</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/main.css') }}">
    <script src="{{ asset('js/a_CommonParts/getScreenSize.js') }}" defer></script>



</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">    
                    <div class="main-contents-wrapper">
                        <h1>ログインフォーム</h1>
                        <div class="login-form-wrapper">
                            <form action="{{ route('login') }}" method="POST">
                            @csrf
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
                                <input type="submit" value="ログイン">
                            </form>
                            @if(session('error_login'))
                                <p class="error-message">{{ session('error_login') }}</p>
                            @endif
                        </div>
                        <p>アカウントをお持ちでない方は<a href="{{ route('account_registeration_form') }}">こちら</a></p>
                    </div>
                </div>

                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')

</body>
</html>