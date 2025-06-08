@extends('CommonParts.app')

@section('head')
    @parent
    <title>登録完了のお知らせ</title>
@endsection

@section('contents')
    <div class="main-contents">
        <div>
            <h1>アカウント登録完了のお知らせ</h1>
            <p>アカウントの登録が完了しました</p><br>
            <p>ログインは<a href="{{ route('login_form') }}" class="underline">こちら</a>から</p><br>
            <a href="{{ route('toppage') }}" class="underline">トップページへ戻る</p>
        </div>
    </div>
    </div>
@endsection