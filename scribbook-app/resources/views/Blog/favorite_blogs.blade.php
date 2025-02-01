<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/main.css') }}">
    <script src="{{ asset('js/a_CommonParts/getScreenSize.js') }}" defer></script>

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
                            <table class="article-list" border="1">
                                <tr>
                                    <th>タイトル</th>
                                    <th>コンテンツ</th>
                                    <th>投稿者</th>
                                    <th>投稿日</th>
                                </tr>
                                @foreach($favoriteBlogs as $favoriteBlog)
                                    <tr>
                                        <td><a href="{{ route('blog_detail', ['id' => $favoriteBlog['id']]) }}">{{ $favoriteBlog['title'] }}</a></td>
                                        <td class="blog-contents"><a href="{{ route('blog_detail', ['id' => $favoriteBlog['id']]) }}">{{ $favoriteBlog['contents'] }}</a></td>
                                        <td class="post-user"><a href="{{ route('profile_top', ['id' => $favoriteBlog['created_by']]) }}">{{ $favoriteBlog['name'] }}</a></td>
                                        <td class="posted-at">{{ $favoriteBlog['created_at'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
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