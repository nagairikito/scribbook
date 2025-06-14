@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <script src="{{ asset('js/search.js') }}" defer></script>
    
    <title>お気に入りブログ</title>
@endsection

@section('contents')
    <div class="main-contents">
        <div class="main-contents-wrapper"></div>

        <div class="category-view">
            <div class="category-blog">
                @include('blog_unit', ['blogs' => $result['blogs'], 'word' => 'キーワードと一致するブログが見つかりません'])
            </div>

            <div class="category-user">
                @if(count($result['users']) > 0)
                    <div>
                        @foreach($result['users'] as $user)
                            <div>
                                <div><a href="{{ route('profile_top', ['id' => $user['id']]) }}">{{ $user['icon_image'] }}</a></div>
                                <div>
                                    <a href="{{ route('profile_top', ['id' => $user['id']]) }}">{{ $user['name'] }}</a>
                                    @if($user['id'] == Auth::id())
                                        <form action="{{ route('register_favorite_user') }}" metohd="POST">
                                            @csrf
                                            <input type="submit" class="favorite-user-button mb-15p" value="お気に入り登録">
                                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" name="target_favorite_user_id" value="{{ $user['id'] }}">
                                        </form>
                                    @else
                                        <form action="{{ route('delete_favorite_user') }}" metohd="POST">
                                            @csrf
                                            <input type="submit" class="favorite-user-button mb-15p" value="お気に入り登録解除">
                                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" name="target_favorite_user_id" value="{{ $user['id'] }}">
                                            <input type="hidden" name="page_type" value="profile_top">
                                        </form>
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