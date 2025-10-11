@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <script src="{{ asset('js/search.js') }}" defer></script>
    
    <title>お気に入りブログ</title>
@endsection

@section('contents')
    <div class="main-contents">
        <!-- <div class="main-contents-wrapper"></div> -->

        <div class="select-category-btns">
            <div class="select-btn blog">ブログ</div>
            <div class="select-btn user">ユーザー</div>
        </div>


        <div class="category-view">
            <div class="category blog">
                @include('blog_unit', ['blogs' => $result['blogs'], 'word' => 'キーワードと一致するブログが見つかりません'])
            </div>

            <div class="category user display-none">
                @if(count($result['users']) > 0)
                    <div class="category-user-wrapper">
                        @foreach($result['users'] as $user)
                            <div class="user-unit">
                                <a href="{{ route('profile_top', ['id' => $user['id']]) }}"><img class="user-icon" src="{{ $user['icon_image'] }}"></a>
                                <div class="right">
                                    <a class="user-name" href="{{ route('profile_top', ['id' => $user['id']]) }}">{{ $user['name'] }}</a>
                                    @if($user['id'] != Auth::id())
                                        @if($user['login_user_id'] != Auth::id())
                                            <form action="{{ route('register_favorite_user') }}" metohd="POST">
                                                @csrf
                                                <input type="submit" class="favorite-user-button mb-15p" value="お気に入り登録">
                                                <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                                <input type="hidden" name="target_favorite_user_id" value="{{ $user['id'] }}">
                                            </form>
                                        @elseif($user['login_user_id'] == Auth::id())
                                            <form action="{{ route('delete_favorite_user') }}" metohd="POST">
                                                @csrf
                                                <input type="submit" class="favorite-user-button mb-15p" value="お気に入り登録解除">
                                                <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                                <input type="hidden" name="target_favorite_user_id" value="{{ $user['id'] }}">
                                                <input type="hidden" name="page_type" value="profile_top">
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else
                    <p>キーワードと一致するユーザーが見つかりません</p>
                @endif
            </div>
        </div>
    </div>
@endsection