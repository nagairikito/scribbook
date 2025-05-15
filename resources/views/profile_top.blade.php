@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/profileTop.css') }}">
    <script src="{{ asset('js/profileTop.js') }}" defer></script>
    <title>プロフィール</title>
@endsection

@section('contents')
    <div class="main-contents profile-top">
        <div class="main-contents-wrapper">
            <h1>プロフィール</h1>
            <div class="profile">
                @include('session_messages')

                <div class="profile-card">
                    @if(Auth::user() && Auth::id() == $user[0]['id'])
                        <div class="profile-button-list">
                            <div class="open-edit-profile-modal" onclick="openModal(EDIT_PROFILE)"><p>プロフィール編集</p></div>
                            <div class="open-privacy-setting-modal" onclick="openModal(PRIVACY_SETTING)"><p>プライバシー設定</p></div>
                            <div>
                                <form action="{{ route('logout') }}" method="GET">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ Auth::id() }}">
                                    <input type="submit" class="logout-button" value="ログアウト">
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="profile-contents">
                        <div class="left-contents">
                            <div>
                                <div class="profile-image-frame">
                                    <img src="{{ asset('storage/user_icon_images/' .$user[0]['icon_image']) }}" class="profile-icon">
                                </div>
                                @if(Auth::user() && Auth::id() == $user[0]['id'])
                                    <div class="open-favorite-user-modal" onclick="openModal(FAVORITE_USER)">お気に入りユーザー</div>

                                @elseif(Auth::user() && Auth::id() != $user[0]['id'])
                                    @if($user[0]['favorite_flag'] == false)
                                        <form action="{{ route('register_favorite_user') }}" metohd="POST">
                                            @csrf
                                            <input type="submit" class="favorite-user-button mb-15p" value="お気に入り登録">
                                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" name="target_favorite_user_id" value="{{ $user[0]['id'] }}">
                                        </form>
                                    @else
                                        <form action="{{ route('delete_favorite_user') }}" metohd="POST">
                                            @csrf
                                            <input type="submit" class="favorite-user-button mb-15p" value="お気に入り登録解除">
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
                                            <input type="submit" class="message-button" value="メッセージを送る">
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="right-contents">
                            <div>
                                <p>名前</p>
                                <p class="word-wrap">{{ $user[0]['name'] }}</p>
                            </div>
                            <div>
                                <p>ユーザーID<p>
                                <p class="word-wrap">{{ $user[0]['id'] }}（ログインIDとは異なります）<p>
                            </div>
                            <div>
                                <p>概要欄</p>
                                <p class="word-wrap">{{ $user[0]['discription'] }}</p>
                            </div>
                        </div>
                    </div>

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
                        <div>
                            <div class="close-edit-profile-modal" onclick="closeModal(EDIT_PROFILE)">✕</div>
                            <div><input type="submit" value="保存"></div>
                        </div>
                        <div>
                            @if(session('error_update'))
                            <p class="error-message">{{ session('error_update') }}</p>
                            @endif
                        </div>
                        <div>

                            <div>
                                <img src="{{ asset('storage/user_icon_images/' . Auth::user()->icon_image) }}" style="width: 200px; height: 200px;">
                                <i onclick="initUserIcon()">✕</i>
                                <input type="file" name="icon_image_file">
                                <input type="hidden" name="icon_image" value="{{ Auth::user()->icon_image }}">
                            </div>
                            <div>
                                <p>名前:</p>
                                <input type="text" name="name" value="{{ Auth::user()->name }}">
                                @if($errors->has('name'))
                                <p class="error-message">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                            <div>
                                <p>ユーザーID:</p>
                                <p>{{ Auth::user()->id }}（ログインIDとは異なります、ユーザーIDは変更できません）</p>
                            </div>
                            <div>
                                <p>概要欄:</p>
                                <textarea name="discription" cols="70" rows="15">{{ Auth::user()->discription }}</textarea>
                                @if($errors->has('discription'))
                                <p class="error-message">{{ $errors->first('discription') }}</p>
                                @endif
                            </div>
                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                            <input type="hidden" name="login_id" value="{{ Auth::user()->login_id }}">
                            <input type="hidden" name="password" value="{{ Auth::user()->password }}">
                            <input type="hidden" name="password_confirmation" value="{{ Auth::user()->password }}">
                        </div>
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
                        <div>
                            <div class="close-privacy-setting-modal" onclick="closeModal(PRIVACY_SETTING)">✕</div>
                            <div><input type="submit" value="保存"></div>
                        </div>
                        <div>
                            <div>
                                <p>現在のログインID:</p>
                                <p>{{ Auth::user()->login_id }}</p>
                            </div>
                            <div>
                                <p>新しいログインID:</p>
                                <input type="text" name="login_id">
                                @if($errors->has('login_id'))
                                <p class="error-message">{{ $errors->first('login_id') }}</p>
                                @endif
                                <p>※ログインIDのみ変更したい場合は新しいパスワードと確認用パスワードに現在のパスワードを入力してください</p>
                            </div>
                            <div>
                                <p>新しいパスワード:</p>
                                <input type="password" name="password">
                                @if($errors->has('password'))
                                <p class="error-message">{{ $errors->first('password') }}</p>
                                @endif
                            </div>
                            <div>
                                <p>確認用パスワード:</p>
                                <input type="password" name="password_confirmation">
                            </div>
                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                            <input type="hidden" name="icon_image" value="{{ Auth::user()->icon_image }}">
                            <input type="hidden" name="discription" value="{{ Auth::user()->discription }}">
                        </div>
                    </form>
                </div>

                <!-- お気に入りユーザーモーダル -->
                <div class="modal-contents favorite-user display-none">
                    <div class="close-privacy-setting-modal" onclick="closeModal(FAVORITE_USER)">✕</div>
                    @if(count($user[0]['favorite_users']) > 0)
                    <div>
                        @foreach($user[0]['favorite_users'] as $favorite_user)
                        <div>
                            <div><a href="{{ route('profile_top', ['id' => $favorite_user['id']]) }}"><img src="{{ asset('storage/user_icon_images/' . $favorite_user['icon_image']) }}"></a></div>
                            <div><a href="{{ route('profile_top', ['id' => $favorite_user['id']]) }}"></a>{{ $favorite_user['name'] }}</div>
                            <form action="{{ route('delete_favorite_user') }}">
                                @csrf
                                <input type="submit" value="お気に入り登録解除">
                                <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                <input type="hidden" name="target_favorite_user_id" value="{{ $favorite_user['id'] }}">
                                <input type="hidden" name="page_type" value="my_favorite_users">
                            </form>
                        </div>
                        @endforeach
                    </div>
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
@endsection