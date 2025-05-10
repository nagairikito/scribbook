<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CommonParts/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <script src="{{ asset('js/profileTop.js') }}" defer></script>
    <script src="{{ asset('js/CommonParts/getScreenSize.js') }}" defer></script>

    
    <title>プロフィール</title>
</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents profile-top">
                    <div class="main-contents-wrapper">
                        <h1>プロフィール</h1>
                        <div class="profile">
                            @include('Account.session_messages')

                            <div class="profile-card">
                                @if(Auth::user() && Auth::id() == $user[0]['id'])
                                    <ul>
                                        <li class="open-edit-profile-modal" onclick="openModal(EDIT_PROFILE)">プロフィール編集</li>
                                        <li class="open-privacy-setting-modal" onclick="openModal(PRIVACY_SETTING)">プライバシー設定</li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="GET">
                                            @csrf
                                                <input type="hidden" name="id" value="{{ Auth::id() }}">
                                                <input type="submit" value="ログアウト">
                                            </form>
                                        </li>
                                    </ul>
                                @endif
                                <ul>
                                    <li><img src="{{ asset('storage/user_icon_images/' .$user[0]['icon_image']) }}"></li>
                                    <li>名前:　{{ $user[0]['name'] }}</li>
                                    <li>ユーザーID:　{{ $user[0]['id'] }}（ログインIDとは異なります）</li>

                                    @if(Auth::user() && Auth::id() == $user[0]['id'])
                                        <li class="open-favorite-user-modal" onclick="openModal(FAVORITE_USER)">お気に入りユーザー</li>

                                    @elseif(Auth::user() && Auth::id() != $user[0]['id'])
                                        @if($user[0]['favorite_flag'] == false)
                                            <form action="{{ route('register_favorite_user') }}" metohd="POST">
                                            @csrf
                                                <input type="submit" value="お気に入り登録">
                                                <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                                <input type="hidden" name="target_favorite_user_id" value="{{ $user[0]['id'] }}">
                                            </form>
                                        @else
                                            <form action="{{ route('delete_favorite_user') }}" metohd="POST">
                                                @csrf
                                                    <input type="submit" value="お気に入り登録解除">
                                                    <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                                    <input type="hidden" name="target_favorite_user_id" value="{{ $user[0]['id'] }}">
                                                    <input type="hidden" name="page_type" value="profile_top">
                                            </form>
                                        @endif
                                        <div>
                                            <form action="{{ route('display_talk_room') }}" method="get">
                                            @csrf
                                                <input type="hidden" name="sender" value="{{ Auth::id() }}">
                                                <input type="hidden" name="recipient" value="{{ $user[0]['id'] }}">
                                                <input type="submit" value="メッセージを送る">
                                            </form>
                                        </div>

                                    @endif

                                    <li>概要欄:<div class="discription">{{ $user[0]['discription'] }}</div></li>
                                </ul>
                            </div>
                        </div>

                        <h2>ブログ一覧</h2>
                        @if(count($blogs) > 0)
                            <table border="0">
                                @foreach($blogs as $blog)
                                    <tr key="{{ $blog['id'] }}">
                                            <td><a href="{{ route('blog_detail', ['id' => $blog['id']]) }}">{{ $blog['title'] }}</a></td>
                                            <td><a>{{ $blog['updated_at'] }}</a></td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <p>投稿がありません</p>
                        @endif
                        
                    </div>

                    @if(Auth::user() && Auth::id() == $user[0]['id'])
                        <!-- モーダル -->
                        <div class="modal close">
                            <div class="modal-wrapper">

                                <!-- プロフィール編集モーダル -->
                                <div class="modal-contents edit-profile display-none">
                                    <form action="{{ route('update_profile') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                        <ul>
                                            <li class="close-edit-profile-modal" onclick="closeModal(EDIT_PROFILE)">✕</li>
                                            <li><input type="submit" value="保存"></li>
                                        </ul>
                                        <div>
                                            @if(session('error_update'))
                                                <p class="error-message">{{ session('error_update') }}</p>
                                            @endif
                                        </div>
                                        <ul>
                                            
                                            <li>
                                                <img src="{{ asset('storage/user_icon_images/' . Auth::user()->icon_image) }}" style="width: 200px; height: 200px;">
                                                <i onclick="initUserIcon()">✕</i>
                                                <input type="file" name="icon_image_file">
                                                <input type="hidden" name="icon_image" value="{{ Auth::user()->icon_image }}">
                                            </li>
                                            <li>
                                                <p>名前:</p>
                                                <input type="text" name="name" value="{{ Auth::user()->name }}">
                                                @if($errors->has('name'))
                                                    <p class="error-message">{{ $errors->first('name') }}</p>
                                                @endif
                                            </li>
                                            <li>
                                                <p>ユーザーID:</p>
                                                <p>{{ Auth::user()->id }}（ログインIDとは異なります、ユーザーIDは変更できません）</p>
                                            </li>
                                            <li>
                                                <p>概要欄:</p>
                                                <textarea name="discription" cols="70" rows="15">{{ Auth::user()->discription }}</textarea>
                                                @if($errors->has('discription'))
                                                    <p class="error-message">{{ $errors->first('discription') }}</p>
                                                @endif
                                            </li>
                                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" name="login_id" value="{{ Auth::user()->login_id }}">
                                            <input type="hidden" name="password" value="{{ Auth::user()->password }}">
                                            <input type="hidden" name="password_confirmation" value="{{ Auth::user()->password }}">
                                        </ul>
                                    </form>
                                    <form action="{{ route('delete_account') }}">
                                    @csrf
                                        <input type="hidden" name="id" value="{{ Auth::id() }}">
                                        <input type="submit" value="アカウント削除">
                                    </form>
                                </div>

                                <!-- プライバシー設定モーダル -->
                                <div class="modal-contents privacy-setting display-none">
                                    <form action="{{ route('update_profile') }}" method="POST">
                                    @csrf
                                        <ul>
                                            <li class="close-privacy-setting-modal" onclick="closeModal(PRIVACY_SETTING)">✕</li>
                                            <li><input type="submit" value="保存"></li>
                                        </ul>
                                        <ul>
                                            <li>
                                                <p>現在のログインID:</p>
                                                <p>{{ Auth::user()->login_id }}</p>
                                            </li>
                                            <li>
                                                <p>新しいログインID:</p>
                                                <input type="text" name="login_id">
                                                @if($errors->has('login_id'))
                                                    <p class="error-message">{{ $errors->first('login_id') }}</p>
                                                @endif
                                                <p>※ログインIDのみ変更したい場合は新しいパスワードと確認用パスワードに現在のパスワードを入力してください</p>
                                            </li>
                                            <li>
                                                <p>新しいパスワード:</p>
                                                <input type="password" name="password">
                                                @if($errors->has('password'))
                                                    <p class="error-message">{{ $errors->first('password') }}</p>
                                                @endif
                                            </li>
                                            <li>
                                                <p>確認用パスワード:</p>
                                                <input type="password" name="password_confirmation">
                                            </li>
                                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                                            <input type="hidden" name="icon_image" value="{{ Auth::user()->icon_image }}">
                                            <input type="hidden" name="discription" value="{{ Auth::user()->discription }}">
                                        </ul>
                                    </form>
                                </div>

                                <!-- お気に入りユーザーモーダル -->
                                <div class="modal-contents favorite-user display-none">
                                    <div class="close-privacy-setting-modal" onclick="closeModal(FAVORITE_USER)">✕</div>
                                    @if(count($user[0]['favorite_users']) > 0)
                                        <ul>
                                            @foreach($user[0]['favorite_users'] as $favorite_user)
                                                <li>
                                                    <div><a href="{{ route('profile_top', ['id' => $favorite_user['id']]) }}"><img src="{{ asset('storage/user_icon_images/' . $favorite_user['icon_image']) }}"></a></div>
                                                    <div><a href="{{ route('profile_top', ['id' => $favorite_user['id']]) }}"></a>{{ $favorite_user['name'] }}</div>
                                                    <form action="{{ route('delete_favorite_user') }}">
                                                    @csrf
                                                        <input type="submit" value="お気に入り登録解除">
                                                        <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                                        <input type="hidden" name="target_favorite_user_id" value="{{ $favorite_user['id'] }}">
                                                        <input type="hidden" name="page_type" value="my_favorite_users">
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>お気に入り登録されているユーザーがいません</p>
                                    @endif
                                </div>

                                <!-- モーダル背景 -->
                                <div class="modal-background"></div>

                            </div>
                        </div>
                    @endif
                </div>
                
                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')
</body>
</html>