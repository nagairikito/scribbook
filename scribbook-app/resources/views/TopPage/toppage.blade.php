<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScribBook</title>

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/main.css') }}">
    <script src="{{ asset('js/a_CommonParts/getScreenSize.js') }}" defer></script>


</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">
                    @include('TopPage.session_messages')

                    <div class="topics">
                        <h2>トピックス</h2>
                        <ul class="topic-wrapper">
                            <li class="topic-article">
                                <a href="" class="topic-article-wrapper">
                                    <div class="topic-article-title">topic1</div>
                                    <div class="topic-article-image">topic-image</div>
                                    <div class="topic-article-contents">topic-contents</div>
                                </a>    
                            </li>
                            <li class="topic-article">
                                <div class="topic-article-title">topic2</div>
                                <div class="topic-article-image">topic-image</div>
                                <div class="topic-article-contents">topic-contents</div>
                            </li>
                            <li class="topic-article">
                                <div class="topic-article-title">topic3</div>
                                <div class="topic-article-image">topic-image</div>
                                <div class="topic-article-contents">topic-contents</div>
                            </li>
                            <li class="topic-article">
                                <div class="topic-article-title">topic4</div>
                                <div class="topic-article-image">topic-image</div>
                                <div class="topic-article-contents">topic-contents</div>
                            </li>
                            <li class="topic-article">
                                <div class="topic-article-title">topic5</div>
                                <div class="topic-article-image">topic-image</div>
                                <div class="topic-article-contents">topic-contents</div>
                            </li>
                        </ul>
                    </div>

                    <table class="article-list" border="1">
                        <tr>
                            <th>タイトル</th>
                            <th>コンテンツ</th>
                            <th>投稿者</th>
                            <th>投稿日</th>
                        </tr>
                        @if(count($allBlogs) > 0)
                            @foreach($allBlogs as $blog)
                                <tr>
                                    <td><a href="{{ route('blog_detail', ['id' => $blog['id']]) }}">{{ $blog['title'] }}</a></td>
                                    <td class="blog-contents"><a href="{{ route('blog_detail', ['id' => $blog['id']]) }}">{{ $blog['contents'] }}</a></td>
                                    <td class="post-user"><a href="{{ route('profile_top', ['id' => $blog['created_by']]) }}">{{ $blog['name'] }}</a></td>
                                    <td class="posted-at">{{ $blog['created_at'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr></tr>
                        @endif
                    </table>
                </div>
                
                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')
    
</body>
</html>