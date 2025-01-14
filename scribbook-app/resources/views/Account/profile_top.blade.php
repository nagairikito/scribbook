<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Account/account.css') }}">
    <script src="{{ asset('js/Account/profileTop.js') }}" defer></script>
    
    <title>プロフィール</title>
</head>
<body>
    @include('a_CommonParts.header')

    <main id="profile-top">
        <div class="profile-top-wrapper">
            <h1>プロフィール</h1>
            <div class="profile">
                @include('Account.session_messages')

                <div class="profile-card">
                    @if(Auth::user() && Auth::id() == $user[0]['id'])
                        <ul>
                            <li class="open-edit-profile-modal" onclick="openModal(EDIT_PROFILE)">プロフィール編集</li><!-- onClickでモーダルオンにする、パスワードをかける -->
                            <li class="open-privacy-setting-modal" onclick="openModal(PRIVACY_SETTING)">プライバシー設定</li><!-- onClickでモーダルオンにする、パスワードをかける、アカウント削除機能 -->
                            <li>
                                <form action="{{ route('logout') }}" metohd="POST">
                                @csrf
                                    <input type="hidden" name="id" value="{{ Auth::id() }}">
                                    <input type="submit" value="ログアウト">
                                </form>
                            </li>
                        </ul>
                    @endif

                    <ul>
                        <li>{{ $user[0]['icon_image'] }}</li>
                        <li>名前:　{{ $user[0]['name'] }}</li>
                        <li>ユーザーID:　{{ $user[0]['id'] }}（ログインIDとは異なります）</li>
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
                    <div class="modal-contents edit-profile display-none">
                        <form action="{{ route('update_profile') }}" method="POST">
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
                                
                                <li>{{ Auth::user()->icon_image }}</li>
                                <li>
                                    <p>プロフィール画像選択:</p>
                                    <input type="file" name="icon_image" value="{{ Auth::user()->icon_image }}">
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

                    <div class="modal-background"></div>

                </div>
            </div>
        @endif

    </main>


    @include('a_CommonParts.footer')
</body>
</html>