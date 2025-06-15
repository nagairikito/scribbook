@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/profileTop.css') }}">
    <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
    <script src="{{ asset('js/profileTop.js') }}" defer></script>
    <title>プロフィール</title>
@endsection

@section('contents')
    <div id="profile-top" class="main-contents profile-top">
        <div class="main-contents-wrapper">
            <h1>プロフィール</h1>
            <div class="profile">
                @include('session_messages')

                <div class="profile-card">
                    @if(Auth::user() && Auth::id() == $user['id'])
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
                                    <img src="{{ asset('storage/user_icon_images/' .$user['icon_image']) }}" class="profile-icon">
                                </div>
                                @if(Auth::user() && Auth::id() == $user['id'])
                                    <div class="open-favorite-user-modal" onclick="openModal(FAVORITE_USER)">お気に入りユーザー</div>

                                @elseif(Auth::user() && Auth::id() != $user['id'])
                                    @if($user['favorite_flag'] == false)
                                        <form action="{{ route('register_favorite_user') }}" metohd="POST">
                                            @csrf
                                            <input type="submit" class="favorite-user-button mb-15p" value="お気に入り登録">
                                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" name="target_favorite_user_id" value="{{ $user['id'] }}">
                                        </form>
                                    @else
                                        <form action="{{ route('delete_favorite_user') }}" metohd="POST">
                                            @csrf
                                            <input type="submit" class="favorite-user-button mb-15p" value="お気に入り登録解除">
                                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" name="target_favorite_user_id" value="{{ $user['id'] }}">
                                            <input type="hidden" name="page_type" value="profile_top">
                                        </form>
                                    @endif
                                    <div>
                                        <form action="{{ route('display_talk_room') }}" method="get">
                                            @csrf
                                            <input type="hidden" name="sender" value="{{ Auth::id() }}">
                                            <input type="hidden" name="recipient" value="{{ $user['id'] }}">
                                            <input type="submit" class="message-button" value="メッセージを送る">
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="right-contents">
                            <div>
                                <p>名前</p>
                                <p class="word-wrap">{{ $user['name'] }}</p>
                            </div>
                            <div>
                                <p>ユーザーID<p>
                                <p class="word-wrap">{{ $user['id'] }}<span class="user-id-attention">※ログインIDとは異なります</span><p>
                            </div>
                            <div>
                                <p>概要欄</p>
                                <p class="word-wrap">{{ $user['discription'] }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="blog-list">
                    <h2>ブログ一覧</h2>
                    @include('blog_unit', ['word' => '投稿がありません'])
                </div>
            </div>
        </div>

        @if(Auth::user() && Auth::id() == $user['id'])
        <!-- モーダル -->
        <div id="modal" class="modal close">
            <div class="modal-wrapper">

                <!-- プロフィール編集モーダル -->
                <div id="profile-editing-modal" class="modal-contents edit-profile display-none">
                    <div class="profile-editing-modal-wrapper">
                        <form action="{{ route('update_profile') }}" class="profile-editing-form-area" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="profile-editing-control-buttons">
                                <div class="close-edit-profile-modal" onclick="closeModal(EDIT_PROFILE)">✕</div>
                                <div class="profile-updating-button-wrapper"><input type="submit" class="profile-updating-button" value="保存"></div>
                            </div>
                            <div class="profile-editing-contents-area">
                                @if(session('error_update'))
                                    <div>
                                        <p class="error-message me">{{ session('error_update') }}</p>
                                    </div>
                                @endif
                                <div class="profile-editing-input-area">
                                    <div class="profile-editing-user-icon-area">
                                        <!-- <div class="profile-editing-user-icon-area-wrapper"> -->
                                            <div class="img-delete-btn-wrapper" onclick="deleteProfileIconImage()"><span class="img-delete-btn-content">✕</span></div>
                                            <img id="icon-image" src="{{ asset('storage/user_icon_images/' . Auth::user()->icon_image) }}" class="profile-editing-user-icon">
                                            <div class="display-center">
                                                <input class="user-icon-choice-button" type="file" name="icon_image_file">
                                            </div>
                                            <input id="edit-icon-image" type="hidden" name="icon_image" value="{{ Auth::user()->icon_image }}">
                                        <!-- </div> -->
                                    </div>
                                    <div>
                                        <p>名前</p>
                                        <input type="text" class="input-box" name="name" value="{{ old('name', Auth::user()->name) }}">
                                        @if($errors->has('name'))
                                        <p class="error-message me">{{ $errors->first('name') }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        <p>概要欄</p>
                                        <textarea name="discription" cols="70" rows="15" style="resize: none;">{{ old('discription', Auth::user()->discription) }}</textarea>
                                        @if($errors->has('discription'))
                                        <p class="error-message me">{{ $errors->first('discription') }}</p>
                                        @endif
                                    </div>
                                    <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                    <input type="hidden" name="login_id" value="{{ Auth::user()->login_id }}">
                                    <input type="hidden" name="password" value="{{ Auth::user()->password }}">
                                    <input type="hidden" name="password_confirmation" value="{{ Auth::user()->password }}">
                                </div>
                            </div>
                        </form>
                        <form action="{{ route('delete_account') }}" class="account-deleting-button-area">
                            @csrf
                            <input type="hidden" name="id" value="{{ Auth::id() }}">
                            <input type="submit" class="account-deleting-button" value="アカウント削除">
                        </form>
                    </div>
                </div>

                <!-- プライバシー設定モーダル -->
                <div id="privacy-setting-modal" class="modal-contents privacy-setting display-none">
                    <div class="privacy-setting-modal-wrapper">
                        <form action="{{ route('update_profile') }}" class="privacy-setting-form-area" method="POST">
                            @csrf
                            <div class="privacy-setting-control-buttons">
                                <div class="close-privacy-setting-modal" onclick="closeModal(PRIVACY_SETTING)">✕</div>
                                <div class="privacy-setting-updating-button-wrapper"><input type="submit" class="privacy-setting-updating-button" value="保存"></div>
                            </div>
                            <div class="privacy-setting-contents-area">
                                <div class="privacy-setting-input-area">
                                    <div>
                                        <p>ユーザーID</p>
                                        <div>
                                            <p class="user-id-value">{{ Auth::user()->id }}</p>
                                            <p class="user-id-attention">※ログインIDとは異なります、ユーザーIDは変更できません</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p>ログインID</p>
                                        <div>
                                            <p>現在のログインID:</p>
                                            <p class="login-id-value">{{ Auth::user()->login_id }}</p>
                                        </div>
                                        <div>
                                            <p>新しいログインID:</p>
                                            <input type="text" class="input-box" name="login_id" value="{{ old('login_id') }}">
                                            @if($errors->has('login_id'))
                                            <p class="error-message privacy">{{ $errors->first('login_id') }}</p>
                                            @endif
                                            <p class="password-attention">※ログインIDのみ変更したい場合は新しいパスワードと確認用パスワードに現在のパスワードを入力してください</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p>パスワード</p>
                                        <div>
                                            <p>新しいパスワード:</p>
                                            <input type="password" class="input-box" name="password">
                                            @if($errors->has('password'))
                                            <p class="error-message privacy">{{ $errors->first('password') }}</p>
                                            @endif
                                        </div>
                                        <div>
                                            <p>確認用パスワード:</p>
                                            <input type="password" class="input-box" name="password_confirmation">
                                        </div>
                                    </div>
                                        <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                                        <input type="hidden" name="icon_image" value="{{ Auth::user()->icon_image }}">
                                        <input type="hidden" name="discription" value="{{ Auth::user()->discription }}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- お気に入りユーザーモーダル -->
                <div id="favorite-user-modal" class="modal-contents favorite-user display-none">
                    <div class="favorite-user-modal-wrapper">
                        <div class="favorite-user-control-buttons">
                            <div class="close-favorite-user-modal" onclick="closeModal(FAVORITE_USER)">✕</div>
                        </div>
                        @if(count($user['favorite_users']) > 0)
                        <div class="favorite-user-list">
                            <div class="favorite-user-list-wrapper">
                                @foreach($user['favorite_users'] as $favorite_user)
                                <div class="favorite-user-unit">
                                    <div class="left">
                                        <a href="{{ route('profile_top', ['id' => $favorite_user['id']]) }}">
                                            <img class="favorite-user-icon" src="{{ asset('storage/user_icon_images/' . $favorite_user['icon_image']) }}">
                                        </a>
                                    </div>
                                    <div class="center">
                                        <div class="favorite-user-name"><a href="{{ route('profile_top', ['id' => $favorite_user['id']]) }}"></a>{{ $favorite_user['name'] }}</div>
                                        <a href="{{ route('display_talk_room', ['sender' => Auth::id(), 'recipient' => $favorite_user['id']]) }}">
                                            <i class="bi bi-chat-dots fos-1_75rem ml-15p"></i>
                                        </a>
                                    </div>
                                    <div class="right">
                                        <form action="{{ route('delete_favorite_user') }}" class="delete-favorite-user-button-wrapper">
                                            @csrf
                                            <input type="submit" class="delete-favorite-user-button" value="お気に入り登録解除">
                                            <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" name="target_favorite_user_id" value="{{ $favorite_user['id'] }}">
                                            <input type="hidden" name="page_type" value="my_favorite_users">
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <p>お気に入り登録されているユーザーがいません</p>
                        @endif
                    </div>
                </div>

                <!-- モーダル背景 -->
                <div class="modal-background"></div>

            </div>
        </div>
        @endif
    </div>
@endsection