@extends('CommonParts.app')

@section('head')
    @parent
    <title>ScribBook_{{ $blog[0]['title'] }}</title>
@endsection

@section('contents')
    <div class="main-contents">
        <div class="blog">
            @include('session_messages')

            <h2>{{ $blog[0]['title'] }}</h2>

            @if(Auth::id() == $blog[0]['created_by'])
            <form action="{{ route('blog_editing_form')}}" method="POST">
                @csrf
                <input type="submit" value="編集">
                <input type="hidden" name="blog_id" value="{{ $blog[0]['id'] }}">
                <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
            </form>
            <form action="{{ route('delete_blog')}}" method="POST">
                @csrf
                <input type="submit" value="削除">
                <input type="hidden" name="blog_id" value="{{ $blog[0]['id'] }}">
                <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
            </form>
            @endif

            @if(Auth::user())
            @if($blog[0]['favorite_flag'] == false)
            <form action="{{ route('register_favorite_blog') }}" method="POST">
                @csrf
                <input type="submit" value="お気に入り登録">
                <input type="hidden" name="blog_id" value="{{ $blog[0]['id'] }}">
                <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
            </form>
            @else
            <form action="{{ route('delete_favorite_blog') }}" method="POST">
                @csrf
                <input type="submit" value="お気に入り登録解除">
                <input type="hidden" name="blog_id" value="{{ $blog[0]['id'] }}">
                <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
            </form>
            @endif

            @endif

            <div>投稿者：　{{ $blog[0]['name'] }}</div>
            <div>投稿日時：　{{ $blog[0]['updated_at'] }}</div>
            <div>{!! $blog[0]['contents'] !!}</div>

        </div>

        <div class="blog-comments">
            <div class="blog-comments-wrapper">

                @if(Auth::user())
                <form action="{{ route('post_comment') }}" method="POST">
                    @csrf
                    <input type="text" name="comment" placeholder="コメント...">
                    <input type="submit" value="送信">

                    <input type="hidden" name="target_blog" value="{{ $blog[0]['id'] }}">
                    <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                </form>
                @endif

                @if(session('error_post_comment'))
                <p>{{ session('error_post_comment') }}</p>
                @endif

                @if(count($comments) > 0)
                <ul class="comment-list">
                    @foreach($comments as $comment)
                    <li>
                        <div class="profile"><img src="{{ asset('storage/user_icon_images/' . $comment['icon_image']) }}"></div>
                        <div>{{ $comment['name'] }}</div>
                        <div>{{ $comment['created_at'] }}</div>
                        <div>{{ $comment['comment'] }}</div>
                    </li>
                    @endforeach
                </ul>

                @else
                <p>コメントはまだありません</p>
                @endif

            </div>
        </div>
    </div>
@endsection