@extends('CommonParts.app')

@section('head')
    @parent
    <title>アカウント新規登録フォーム</title>
@endsection

@section('contents')
    <div class="main-contents">
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
@endsection