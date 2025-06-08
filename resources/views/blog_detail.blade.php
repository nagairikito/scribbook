@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogDetail.css') }}">
    <title>ScribBook_{{ $blogData['blog']['title'] }}</title>
@endsection

@section('contents')
    <div id="blog-detail" class="main-contents">
        <div class="blog-detail-wrapper">
            <div class="blog">
                @include('session_messages')

                <div class="blog-head">
                    <h2>{{ $blogData['blog']['title'] }}</h2>

                    @if(Auth::id() == $blogData['blog']['created_by'])
                    <div class="blog-control-buttons">
                        <form action="{{ route('blog_editing_form')}}" class="edit-button-area" method="GET">
                        @csrf
                            <input type="submit" class="edit-button" value="編集">
                            <input type="hidden" name="blog_id" value="{{ $blogData['blog']['id'] }}">
                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                        </form>
                        <form action="{{ route('delete_blog')}}" class="delete-button-area" method="POST">
                            @csrf
                            <input type="submit" class="delete-button" value="削除">
                            <input type="hidden" name="blog_id" value="{{ $blogData['blog']['id'] }}">
                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                        </form>
                    </div>
                    @endif

                    <div class="head-contents">
                        <div class="left">
                            <div>
                                <p>投稿者：　</p>
                                <a href="{{ route('profile_top', ['id' => $blogData['blog']['created_by']]) }}">{{ $blogData['blog']['name'] }}</a>
                            </div>
                            <div>投稿日時：　{{ $blogData['blog']['updated_at'] }}</div>
                        </div>
                        <div class="right">
                            @if(Auth::user())
                                <div class="right-contents">
                                    @if($blogData['blog']['favorite_flag'] == false)
                                    <form action="{{ route('register_favorite_blog') }}" class="" method="POST">
                                        @csrf
                                        <label for="favo-on-submit-button" class="favo-on-button-frame"><i class="bi bi-heart fos-1_75rem favo-on-button"></i></label>
                                        <button id="favo-on-submit-button" type="submit" style="display: none;"></button>
                                        <input type="hidden" name="blog_id" value="{{ $blogData['blog']['id'] }}">
                                        <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                    </form>
                                    @else
                                    <form action="{{ route('delete_favorite_blog') }}" method="POST">
                                        @csrf
                                        <label for="favo-off-submit-button" class="favo-off-button-frame"><i class="bi bi-heart-fill fos-1_75rem favo-off-button"></i></label>
                                        <button id="favo-off-submit-button" type="submit" style="display: none;"></button>
                                        <input type="hidden" name="blog_id" value="{{ $blogData['blog']['id'] }}">
                                        <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                    </form>
                                    @endif
                                    <div><i class="bi bi-eye mr-5p"></i>{{ $blogData['blog']['view_count'] }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="blog-body">
                    <div>{!! $blogData['blog']['contents'] !!}</div>
                </div>
            </div>

            <div class="blog-comments">
                <div class="blog-comments-wrapper">

                    @if(Auth::user())
                    <form action="{{ route('post_comment') }}" class="comment-input-box-area" method="POST">
                        @csrf
                        <input type="text" name="comment" value="{{ old('comment') }}" class="comment-input-box" placeholder="コメント...">
                        <label for="comment-submit-button" class="comment-send-button-wrapper"><i class="bi bi-send comment-send-button"></i></label>
                        <button id="comment-submit-button" type="submit" style="display: none;"></button>

                        <input type="hidden" name="target_blog" value="{{ $blogData['blog']['id'] }}">
                        <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                    </form>
                    @endif
                    @if($errors->has('comment'))
                        <p>{{ $errors->first('comment') }}</p>
                    @endif
                    @if(session('error_post_comment'))
                        <p>{{ session('error_post_comment') }}</p>
                    @endif

                    <div class="comment-list">
                        @if(count($blogData['comments']) > 0)
                            @foreach($blogData['comments'] as $comment)
                            <div class="comment">
                                <div class="left">
                                    <a href="{{ route('profile_top', ['id' => $comment['created_by']]) }}">
                                        <img class="user-icon" src="{{ asset('storage/user_icon_images/' . $comment['icon_image']) }}">
                                    </a>
                                </div>
                                <div class="right">
                                    <div class="user-name">
                                        <a href="{{ route('profile_top', ['id' => $comment['created_by']]) }}">
                                            {{ $comment['name'] }}
                                        </a>
                                        <span class="comment-send-time">{{ $comment['created_at'] }}</span>
                                    </div>
                                    <div>{{ $comment['comment'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div>コメントはまだありません</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection