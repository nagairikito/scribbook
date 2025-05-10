<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CommonParts/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <script src="{{ asset('js/CommonParts/getScreenSize.js') }}" defer></script>
    <script src="{{ asset('js/search.js') }}" defer></script>

    <title>お気に入りブログ</title>
</head>
<body>
    @include('a_CommonParts.header')
    
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')
                <div class="main-contents">
            
                    <div class="category-view">
                        <div class="category-all">
                            @if(count($result) > 0)
                                @foreach($result as $category => $values)

                                    @if($category === 'blogs')
                                        @if(count($values) > 0)
                                            <table class="article-list" border="1">
                                                <tr>
                                                    <th>タイトル</th>
                                                    <th>コンテンツ</th>
                                                    <th>投稿者</th>
                                                    <th>投稿日</th>
                                                </tr>

                                                @foreach($values as $blog)
                                                    <tr>
                                                        <td><a href="{{ route('blog_detail', ['id' => $blog['id']]) }}">{{ $blog['title'] }}</a></td>
                                                        <td class="blog-contents"><a href="{{ route('blog_detail', ['id' => $blog['id']]) }}">{{ $blog['contents'] }}</a></td>
                                                        <td class="post-user"><a href="{{ route('profile_top', ['id' => $blog['created_by']]) }}">{{ $blog['name'] }}</a></td>
                                                        <td class="posted-at">{{ $blog['created_at'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif

                                    @elseif($category === 'users')
                                        @if(count($values) > 0)
                                            <ul>
                                                @foreach($values as $user)
                                                    <li>
                                                        <div><a href="{{ route('profile_top', ['id' => $user['id']]) }}">{{ $user['icon_image'] }}</a></div>
                                                        <div><a href="{{ route('profile_top', ['id' => $user['id']]) }}"></a>{{ $user['name'] }}</div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    @endif

                                @endforeach
                            @else
                                <p>キーワードと一致する検索結果が見つかりません</p>
                            @endif
                        </div>

                        <div class="category-blog">
                            @if(count($result['blogs']) > 0)
                                <table class="article-list" border="1">
                                    <tr>
                                        <th>タイトル</th>
                                        <th>コンテンツ</th>
                                        <th>投稿者</th>
                                        <th>投稿日</th>
                                    </tr>
                                    @foreach($result['blogs'] as $blog)
                                        <tr>
                                            <td><a href="{{ route('blog_detail', ['id' => $blog['id']]) }}">{{ $blog['title'] }}</a></td>
                                            <td class="blog-contents"><a href="{{ route('blog_detail', ['id' => $blog['id']]) }}">{{ $blog['contents'] }}</a></td>
                                            <td class="post-user"><a href="{{ route('profile_top', ['id' => $blog['created_by']]) }}">{{ $blog['name'] }}</a></td>
                                            <td class="posted-at">{{ $blog['created_at'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>

                            @else
                                <p>キーワードと一致するブログが見つかりません</p>
                            @endif
                        </div>

                        <div class="category-user">
                            @if(count($result['users']) > 0)
                                <ul>
                                    @foreach($result['users'] as $user)
                                        <li>
                                            <div><a href="{{ route('profile_top', ['id' => $user['id']]) }}">{{ $user['icon_image'] }}</a></div>
                                            <div><a href="{{ route('profile_top', ['id' => $user['id']]) }}"></a>{{ $user['name'] }}</div>
                                        </li>
                                    @endforeach
                                </ul>

                            @else
                                <p>キーワードと一致するユーザーが見つかりません</p>
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