<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/TopPage/toppage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/main.css') }}">
    <script src="{{ asset('js/a_CommonParts/getScreenSize.js') }}" defer></script>
    <title>ScribBook_{{ $blog[0]['title'] }}</title>
</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">

                    <div class="blog">
                        @include('Blog.session_messages')

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

                        <p>投稿者：　{{ $blog[0]['name'] }}</p>
                        <p>投稿日時：　{{ $blog[0]['updated_at'] }}</p>
                        <p>{{ $blog[0]['contents'] }}</p>

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
                                            <p>{{ $comment['name'] }}</p>
                                            <p>{{ $comment['created_at'] }}</p>
                                            <p>{{ $comment['comment'] }}</p>
                                        </li>
                                    @endforeach
                                </ul>

                            @else
                                <p>コメントはまだありません</p>
                            @endif

                        </div>
                    </div>
                </div>

                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')
</body>
</html>