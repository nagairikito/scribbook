<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Blog/topics.css') }}">
    <script src="{{ asset('js/a_CommonParts/getScreenSize.js') }}" defer></script>
    <script src="{{ asset('js/Blog/topicsGetScreenSize.js') }}" defer></script>


    <title>お気に入りブログ</title>
</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">    
                    <div class="main-contents-wrapper">
                        <h1>お気に入りブログ</h1>
                        @if(count($favoriteBlogs) > 0)
                            <ul class="blog-list-wrapper">
                                @foreach($favoriteBlogs as $blog)
                                    <li class="blog-unit">
                                        <a href="{{ route('blog_detail', ['id' => $blog['id']]) }}">
                                            <p class="title">{{ $blog['title'] }}</p>
                                            <p class="blog-contents">{{ $blog['contents'] }}</p>
                                            <p class="posted-at">{{ $blog['created_at'] }}</td>
                                        </a>
                                        <p class="post-user"><a href="{{ route('profile_top', ['id' => $blog['created_by']]) }}">{{ $blog['name'] }}</a></p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>お気に入り登録したブログはありません</p>
                        @endif
                    </div>

                </div>

                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')
</body>
</html>