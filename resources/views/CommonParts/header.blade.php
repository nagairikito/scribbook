    <header id="header">
        <div class="header-wrapper">
            <div class="header-logo">
                <div class="header-logo-wrapper">
                    <a href="{{ route('toppage') }}">
                        <img src="{{ asset('storage/scribbook_top_logo.png') }}" class="header-app-logo">
                    </a>
                </div>
            </div>
            <div class="header-search-bar">
                <form action="{{ route('search') }}" method="GET" class="header-search-bar-wrapper">
                @csrf
                    <input class="search-textbox" type="search" aria-label="Search" name="keyword" value="{{isset($result['keyword']) ? $result['keyword'] : ''}}" placeholder="ブログ名・ユーザー名" required="required">
                    <button class="search-button" type="submit">検索 <i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="sp-header-right-nav">
                <div class="sp-search-icon">
                    <i id="search-icon" class="bi bi-search fos-1_3rem"></i>
                </div>
                <div class="sp-blog-tools">
                    <i id="blog-tools" class="bi bi-tools fos-1_3rem"></i>
                </div>
                <div class="sp-list-icon">
                    <i id="sp-list-icon" class="bi bi-list fos-1_5rem"></i>
                </div>
            </div>
            <div class="header-nav">
                <div class="header-nav-list">
                    @if(Auth::user())
                        <div><a href="{{ route('blog_posting_form') }}" class="fos-1_15rem"><i class="bi bi-send-plus mr-5p fos-1_15rem"></i>投稿</a></div>
                        <div class="header-talk-area"><a href="{{ route('talk_room_list') }}" class="fos-1_15rem"><i class="bi bi-envelope mr-5p fos-1_15rem"></i>トーク</a></div>
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
        <div class="sp-header-search-bar">
            <form action="{{ route('search') }}" method="GET" class="sp-header-search-bar-wrapper">
            @csrf
                <input class="sp-search-textbox" type="search" aria-label="Search" name="keyword" value="{{isset($result['keyword']) ? $result['keyword'] : ''}}" placeholder="ブログ名・ユーザー名" required="required">
                <button class="sp-search-button" type="submit">検索 <i class="bi bi-search"></i></button>
            </form>
        </div>
        <div id="tool-list" class="tool-list">
            <div class="tool-list-wrapper">
                <div>
                    <button class="tool-btn" onclick="adoptFontSize()"><i class="bi bi-fonts fos-1_3rem"></i></button>
                    <input class="sub-tool-btn" type="number" min="1" max="100" onchange="setFontSize(this.value)">
                </div>
                <div>
                    <button class="tool-btn" onclick="adoptFontStyleItaric()"><i class="bi bi-type-italic fos-1_3rem"></i></button>
                </div>
                <div>
                    <button class="tool-btn" onclick="adoptStrikeThrough()"><i class="bi bi-type-strikethrough fos-1_3rem"></i></button>
                </div>
                <div>
                    <button class="tool-btn" onclick="adoptUnderLine()"><i class="bi bi-type-underline fos-1_3rem"></i></button>
                </div>
                <div>
                    <button class="tool-btn" onclick="adoptOpacity()"><i class="bi bi-transparency fos-1_15rem"></i></button>
                    <input class="sub-tool-btn" type="number" min="0" max="100" onchange="setOpacity(this.value)">
                </div>
                <div>
                    <button class="tool-btn" onclick="adoptFontColor()"><i class="bi bi-palette fos-1_15rem"></i></button>
                    <input class="sub-tool-btn" type="color" value="#ffffff" onchange="setFontColor(this.value)">
                </div>
                <div>
                    <button class="tool-btn" onclick="adoptBackGroundColor()"><i class="bi bi-palette-fill fos-1_15rem"></i></button>
                    <input class="sub-tool-btn" type="color" value="#ffffff" onchange="setBackGroundColor(this.value)">
                </div>
                <div>
                    <button class="tool-btn" onclick="adoptUrl()"><i class="bi bi-link fos-1_3rem"></i></button>
                </div>
                <div>
                    <button class="tool-btn" onclick="addImage()"><i class="bi bi-card-image fos-1_3rem"></i></button>
                </div>
                <div>
                    <button class="tool-btn" onclick="adoptImageSize()"><i class="bi bi-aspect-ratio fos-1_3rem"></i></button>
                    <input class="sub-tool-btn" type="number" class="set-iamge-size-input-box" value="300" onchange="setImageSize(this.value)">
                </div>
            </div>
        </div>
        <div class="imported-img-field-wrapper">
            <div class="imported-img-field"></div>
        </div>

    </header>
