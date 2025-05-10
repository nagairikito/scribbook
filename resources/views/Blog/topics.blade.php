<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScribBook</title>

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CommonParts/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topics.css') }}">
    <script src="{{ asset('js/CommonParts/getScreenSize.js') }}" defer></script>
    <script src="{{ asset('js/topicsGetScreenSize.js') }}" defer></script>


</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">
                    @include('TopPage.session_messages')

                    <div class="blog-list">
                        <h2>トピックス</h2>
                        @if(count($allBlogs) > 0)
                            <ul class="blog-list-wrapper">
                                @foreach($allBlogs as $blog)
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
                            <p>ブログがありません</p>
                        @endif
                    </div>

                </div>
                
                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')
    
</body>
</html>