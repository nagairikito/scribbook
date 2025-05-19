@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
    <script src="{{ asset('js/blogUnitGetScreenSize.js') }}" defer></script>
    <title>お気に入りユーザーのブログ</title>
@endsection

@section('contents')
    <div class="main-contents">
        <div class="blog-list">
            <h2>お気に入りユーザーのブログ</h2>
            @if(count($blogsPostedByFavoriteUser) > 0)
            <ul class="blog-list-wrapper">
                @foreach($blogsPostedByFavoriteUser as $blog)
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
@endsection