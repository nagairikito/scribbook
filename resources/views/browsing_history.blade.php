@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/toppage.css') }}">
    <script src="{{ asset('js/topPageGetScreenSize.js') }}" defer></script>
    <title>閲覧履歴</title>
@endsection

@section('contents')
    <div class="main-contents">
        <div class="blog-list">
            <h2>閲覧履歴</h2>
            @if(count($browsingHistory) > 0)
                <ul class="blog-list-wrapper">
                    @foreach($browsingHistory as $blog)
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
                <p>閲覧履歴がありません</p>
            @endif
            @include('blog_unit', ['word' => '閲覧履歴がありません'])
        </div>
    </div>
@endsection