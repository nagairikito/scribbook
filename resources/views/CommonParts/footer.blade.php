    <footer id="footer">
        <div class="footer-wrapper">
            <div>
                <img src="{{ asset('commonImages/scribbook_logo.png') }}" class="footer-logo" alt="">
            </div>
        </div>
    </footer>

    <section id="footer-menu">
        <div class="footer-menu-wrapper">
            @if(Auth::user())
            <div><a href="{{ route('toppage') }}" class="fos-1_15rem"><i class="bi bi-house-door fos-1_3rem"></i></a></div>
            <div><a href="{{ route('blog_posting_form') }}" class="fos-1_15rem"><i class="bi bi-send-plus fos-1_3rem"></i></a></div>
            <div class="footer-menu-talk-area"><a href="{{ route('talk_room_list') }}" class="fos-1_15rem"><i class="bi bi-envelope fos-1_3rem"></i></a></div>
            <div>
                <a class="footer-user-icon" href="{{ route('profile_top', ['id' => Auth::id()]) }}">
                    @if(Auth::user()->icon_image !== 'noImage.png')
                        @if(app()->environment('production'))
                            <img class="footer-menu-user-icon" src="{{ Auth::user()->icon_image }}">
                        @else
                            <img class="footer-menu-user-icon" src="{{ asset('storage/user_icon_images/' . Auth::user()->icon_image) }}">
                        @endif
                    @else
                        <img class="footer-menu-user-icon" src="{{ asset('commonImages/user_icon_images/noImage.png') }}">
                    @endif
                </a>
            </div>
            @endif
            @if(!Auth::user())
            <div><a href="{{ route('account_registeration_form') }}" class="fos-1_15rem"><i class="bi bi-person-plus mr-5p fos-1_3rem"></i>新規作成</a></div>
            <div><a href="{{ route('login_form') }}" class="fos-1_15rem"><i class="bi bi-box-arrow-in-left mr-5p fos-1_3rem"></i>ログイン</a></div>
            @endif
        </div>
    </section>