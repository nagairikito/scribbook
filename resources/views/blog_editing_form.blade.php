@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogEditing.css') }}">
    <script src="{{ asset('js/blogEditing.js') }}" defer></script>
    <script>
        const APP_URL = "{{ env('APP_URL') }}"
    </script>
    <title>編集フォーム_{{ $blog['title'] }}</title>
@endsection

@section('contents')
    <div id="blog-editing" class="main-contents">
    <div class="main-contents-wrapper">
        <h1>ブログ編集フォーム</h1>
        <div class="blog-editing-form-wrapper">
            <form id="blog-editing-form" class="blog-editing-form" action="{{ route('edit_blog') }}" method="POST">
            @csrf
                <h2>サムネイル</h2>
                <div class="thumbnail-area">
                    <div id="thumbnail-preview-box" class="{{ $blog['thumbnail'] != 'noImage.png' ? '' : 'thumbnail-preview-box' }}">
                        @if($blog['thumbnail'] != 'noImage.png')
                            <div class="delete-btn-wrapper" onclick="deleteThumbnail()"><span class="delete-btn-content">✕</span></div>
                            <img id="thumbnail-preview-img" src="{{ asset('storage/blog_thumbnail_images/' . $blog['blog_unique_id'] . '_' . $blog['thumbnail']) }}" alt="{{ $blog['thumbnail'] }}" style="width: 300px; height: 300px;">
                        @else
                            <p>
                                ここにサムネイル用画像をドラッグアンドドロップしてください<br><br><br>
                                ファイルを選択ボタンからも登録できます
                            </p>
                        @endif
                    </div>
                    <input type="file" id="import-thumbail-input" class="import-thumbail-btn" onchange="importThumbnail(this)">
                    <input type="hidden" id="submit-thumbnail-name" name="thumbnail_name">
                    <input type="hidden" id="submit-thumbnail-img" name="thumbnail_img">
                </div>

                <h2>タイトル</h2>
                <input type="text" name="title" class="blog-title" value="{{ old('title', $blog['title']) }}">
                @if($errors->has('title'))
                    <p class="error-message">{{ $errors->first('title') }}</p>
                @endif
                <h2>コンテンツ</h2>
                <textarea id="replacement-contents" name="contents" style="display: none;"></textarea>
                <div id="original-contents" class="original-contents" contenteditable="true">{!! old('contents', $blog['contents']) !!}</div>

                @if($errors->has('contents'))
                    <p class="error-message">{{ $errors->first('contents') }}</p>
                @endif
                <br>
                <input type="submit" value="編集完了">
                @if(session('error_post_blog'))
                    <p class="error-message">{{ session('error_post_blog') }}</p>
                @endif

                <input type="hidden" name="blog_id" value="{{ $blog['id'] }}">
                <input type="hidden" id="create-user-id" name="login_user_id" value="{{ Auth::id() }}">
            </form>

            <div class="tools-field">
                <div class="tool-list">
                    <div>
                        <button class="tool-btn" onclick="adoptFontSize()">文字サイズ</button>
                        <input class="sub-tool-btn" type="number" min="1" max="100" onchange="setFontSize(this.value)">
                    </div>
                    <div>
                        <button class="tool-btn" onclick="adoptFontStyleItaric()">斜体</button>
                    </div>
                    <div>
                        <button class="tool-btn" onclick="adoptStrikeThrough()">取消線</button>
                    </div>
                    <div>
                        <button class="tool-btn" onclick="adoptUnderLine()">下部線</button>
                    </div>
                    <div>
                        <button class="tool-btn" onclick="adoptOpacity()">透明度</button>
                        <input class="sub-tool-btn" type="number" min="0" max="100" onchange="setOpacity(this.value)">
                    </div>
                    <div>
                        <button class="tool-btn" onclick="adoptFontColor()">文字色</button>
                        <input class="sub-tool-btn" type="color" value="#ffffff" onchange="setFontColor(this.value)">
                    </div>
                    <div>
                        <button class="tool-btn" onclick="adoptBackGroundColor()">背景色</button>
                        <input class="sub-tool-btn" type="color" value="#ffffff" onchange="setBackGroundColor(this.value)">
                    </div>
                    <div>
                        <button class="tool-btn" onclick="adoptUrl()">リンク作成</button>
                    </div>
                    <div>
                        <button class="tool-btn" onclick="addImage()">画像インポート</button>
                    </div>
                    <div>
                        <button class="tool-btn" onclick="adoptImageSize()">画像サイズ適用</button>
                        <input class="sub-tool-btn" type="number" class="set-iamge-size-input-box" value="300" onchange="setImageSize(this.value)">
                    </div>

                </div>
                <div class="tool-setting">
                    <div class="tool-setting-field"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection