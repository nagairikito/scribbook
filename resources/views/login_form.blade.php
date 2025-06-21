@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/loginForm.css') }}">
    <title>ログインフォーム</title>
@endsection

@section('contents')
    <div id="login-form" class="main-contents">
        <div class="main-contents-wrapper">
            <form action="{{ route('login') }}" method="POST" class="login-form-area">
            @csrf
                <div class="app-logo-area">
                    <img src="{{ asset('storage/scribbook_top_logo.png') }}" class="app-logo">
                </div>
                <div class="login-id-area">
                    <p>ログインID</p>
                    <input type="text" name="login_id" class="input-box" value="{{ old('login_id') }}">
                    @if($errors->has('login_id'))
                    <p class="error-message">{{ $errors->first('login_id') }}</p>
                    @endif
                </div>
                <div class="password-area">
                    <p>パスワード</p>
                    <input type="password" name="password" class="input-box">
                    @if($errors->has('password'))
                    <p class="error-message">{{ $errors->first('password') }}</p>
                    @endif
                </div>
                <div class="login-button-area">
                    <input type="submit" class="login-button" value="ログイン">
                    @if(session('error_login'))
                        <p class="error-message">{{ session('error_login') }}</p>
                    @endif
                </div>
                <p class="to-registration-form">アカウントをお持ちでない方は<a href="{{ route('account_registeration_form') }}" class="underline">こちら</a></p>
            </form>
        </div>
    </div>
@endsection