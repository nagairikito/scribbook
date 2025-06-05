    <header id="header">
        <div class="header-wrapper">
            <div class="header-logo">
                <div class="header-logo-wrapper"><a href="{{ route('toppage') }}">mark</a></div>
            </div>
            <div class="header-search-bar">
                <form action="{{ route('search') }}" method="GET" class="header-search-bar-wrapper">
                @csrf
                    @if(isset($keyword))
                    <input class="search-textbox" type="search" aria-label="Search" name="keyword" value="{{$keyword ? $keyword : ''}}" placeholder="ブログ名・ユーザー名" required="required">
                    @else
                        <input class="search-textbox" type="search" aria-label="Search" name="keyword" placeholder="ブログ名・ユーザー名">
                    @endif
                    <!-- <input class="search-button" type="submit" value="検索" class="btn btn-default"> -->
                    <button class="search-button" type="submit">検索 <i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="header-nav">
                <div class="header-nav-list">
                    @if(Auth::user())
                        <div><a href="{{ route('blog_posting_form') }}" class="fos-1_15rem"><i class="bi bi-send-plus mr-5p fos-1_15rem"></i>投稿</a></div>
                        <div><a href="{{ route('talk_room_list') }}" class="fos-1_15rem"><i class="bi bi-envelope mr-5p fos-1_15rem"></i>トーク</a></div>
                        <!-- <div><a href=""><i class="bi bi-bell"></i>通知</a></div> -->
                        <div>
                            <a class="header-user-icon-href" href="{{ route('profile_top', ['id' => Auth::id()]) }}">
                                <img class="header-user-icon-image" src="{{ asset('storage/user_icon_images/' . Auth::user()->icon_image) }}">
                            </a>
                        </div>
                    @endif
                    @if(!Auth::user())
                        <div><a href="{{ route('account_registeration_form') }}" class="fos-1_15rem"><i class="bi bi-person-plus mr-5p fos-1_3rem"></i>新規作成</a></div>
                        <div><a href="{{ route('login_form') }}" class="fos-1_15rem"><i class="bi bi-box-arrow-in-left mr-5p fos-1_3rem"></i>ログイン</a></div>
                    @endif
                </div>
            </div>
        </div>
    </header>
