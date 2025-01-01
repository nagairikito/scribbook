<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント新規登録フォーム</title>
</head>
<body>
    @include('a_CommonParts.header')

    <main>
        <div class="main-contents-wrapper">
            <h1>アカウント新規登録フォーム</h1>
            <div class="account-register-form-wrapper">
                <form action="{{ route('register_account') }}" method="POST">
                    @csrf
                    <p>ユーザー名</p>
                    <input type="text" name="name">
                    @if($errors->has('name'))
                        <p>{{ $errors->first('name') }}</p>
                    @endif

                    <p>ログインID</p>
                    <input type="text" name="login_id">
                    @if($errors->has('login_id'))
                        <p>{{ $errors->first('login_id') }}</p>
                    @endif

                    <p>パスワード</p>
                    <input type="password" name="password">
                    @if($errors->has('password'))
                        <p>{{ $errors->first('password') }}</p>
                    @endif

                    <p>確認用パスワード</p> 
                    <input type="password" name="password_confirmation"><br>

                    <input type="submit" value="新規登録">
                    @if(session('error_account_registeraion'))
                        <p>{{ session('error_account_registeraion') }}</p>
                    @endif
                </form>
            </div>
        </div>
    </main>

    @include('a_CommonParts.footer')
</body>
</html>