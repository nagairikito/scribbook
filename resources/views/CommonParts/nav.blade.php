<nav>
    <div class="nav-wrapper">
        @if(Auth::user())
            <div><a href="{{ route('blog_posting_form') }}" class="fos-1_15rem"><i class="bi bi-send-plus mr-20p fos-1_3rem"></i>投稿</a></div>
        @endif
            <div><a href="{{ route('toppage') }}" class="fos-1_15rem"><i class="bi bi-house-door mr-17_5p fos-1_5rem ml-minus1_5p"></i>トップ</a></div>
            <div><a href="{{ route('topics') }}" class="fos-1_15rem"><i class="bi bi-graph-up-arrow mr-18_5p fos-1_3rem"></i>トピックス</a></div>
        @if(Auth::user())
            <div><a href="{{ route('my_blogs', ['id' => Auth::id()]) }}" class="fos-1_15rem"><i class="bi bi-journals mr-17_5p fos-1_5rem ml-minus1_5p"></i>マイブログ</a></div>
            <div><a href="{{ route('favorite_user_blogs', ['id' => Auth::id()]) }}" class="fos-1_15rem"><i class="bi bi-person mr-17p fos-1_75rem ml-minus3p"></i>お気に入りユーザー</a></div>
            <div><a href="{{ route('favorite_blogs', ['id' => Auth::id()]) }}" class="fos-1_15rem"><i class="bi bi-journal-bookmark mr-20p fos-1_3rem"></i>評価したブログ</a></div>
            <div><a href="{{ route('show_browsing_history', ['id' => Auth::id()]) }}" class="fos-1_15rem"><i class="bi bi-clock-history mr-20p fos-1_3rem"></i>履歴</a></div>
        @endif
    </div>
</nav>