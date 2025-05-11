@extends('CommonParts.app')

@section('head')
    @parent
    <title>ログインフォーム</title>
@endsection

@section('contents')
    <div class="main-contents">
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
        <p>アカウントをお持ちでない方は<a href="{{ route('account_registeration_form') }}" class="underline">こちら</a></p>
    </div>
@endsection