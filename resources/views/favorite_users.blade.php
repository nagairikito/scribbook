@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <script src="{{ asset('js/profileTop.js') }}" defer></script>
    <title>プロフィール</title>
@endsection

@section('contents')
    <div class="main-contents">
        @if(count($user[0]['favorite_users']) > 0)
        <ul>
            @foreach($user[0]['favorite_users'] as $favorite_user)
            <li>
                <div><a href="{{ route('profile_top', ['id' => $favorite_user['id']]) }}"><img src="{{ asset('storage/user_icon_images/' . $favorite_user['icon_image']) }}"></a></div>
                <div><a href="{{ route('profile_top', ['id' => $favorite_user['id']]) }}"></a>{{ $favorite_user['name'] }}</div>
                <form action="{{ route('delete_favorite_user') }}">
                    @csrf
                    <input type="submit" value="お気に入り登録解除">
                    <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                    <input type="hidden" name="target_favorite_user_id" value="{{ $favorite_user['id'] }}">
                    <input type="hidden" name="page_type" value="my_favorite_users">
                </form>
            </li>
            @endforeach
        </ul>
        @else
        <p>お気に入り登録されているユーザーがいません</p>
        @endif
    </div>
@endsection