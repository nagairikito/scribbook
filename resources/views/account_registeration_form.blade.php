@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/accountRegistrationForm.css') }}">
    <title>アカウント新規登録フォーム</title>
@endsection

@section('contents')
    <div id="account-registration-form" class="main-contents">
        <div class="main-contents-wrapper">
            <form action="{{ route('register_account') }}" class="account-registration-form-area" method="POST">
            @csrf
                <div class="app-logo-area">
                    <img src="{{ asset('commonImages/scribbook_logo.png') }}" class="app-logo">
                </div>
                <div class="user-name-area">
                    <p>ユーザー名</p>
                    <input type="text" name="name" class="input-box name" value="{{ old('name') }}">
                    @if($errors->has('name'))
                    <p class="error-message">{{ $errors->first('name') }}</p>
                    @endif
                </div>
                <div class="login-id-area">
                    <p>ログインID</p>
                    <input type="text" name="login_id" class="input-box login-id" value="{{ old('login_id') }}">
                    @if($errors->has('login_id'))
                    <p class="error-message">{{ $errors->first('login_id') }}</p>
                    @endif
                </div>
                <div class="password-area">
                    <p>パスワード</p>
                    <input type="password" name="password" class="input-box pw">
                    @if($errors->has('password'))
                    <p class="error-message">{{ $errors->first('password') }}</p>
                    @endif
                </div>
                <div class="password-confirmation-area">
                    <p>確認用パスワード</p>
                    <input type="password" name="password_confirmation" class="input-box pw-conf"><br>
                </div>
                <div class="account-registration-button-area">
                    <input type="submit" class="account-registration-button" value="新規登録">
                    @if(session('error_account_registeraion'))
                    <p class="error-message">{{ session('error_account_registeraion') }}</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection