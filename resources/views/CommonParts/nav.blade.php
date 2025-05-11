<div class="nav">
    <ul class="nav-wrapper">
        @if(Auth::user())
            <li><a href="{{ route('blog_posting_form') }}">投稿</a></li>
        @endif
            <li><a href="{{ route('toppage') }}">トップ</a></li>
            <li><a href="{{ route('topics') }}">トピックス</a></li>
        @if(Auth::user())
            <li><a href="{{ route('my_blogs', ['id' => Auth::id()]) }}">マイブログ</a></li>
            <li><a href="{{ route('favorite_user_blogs', ['id' => Auth::id()]) }}">評価したユーザー</a></li>
            <li><a href="{{ route('favorite_blogs', ['id' => Auth::id()]) }}">評価したブログ</a></li>
            <li><a href="{{ route('show_browsing_history', ['id' => Auth::id()]) }}">履歴</a></li>
        @endif
    </ul>
</div>