@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogPosting.css') }}">
    <script src="{{ asset('js/blogPosting.js') }}" defer></script>
    <script>
        const APP_URL = "{{ env('APP_URL') }}"
        const APP_ENV = "{{ app()->environment() }}"
    </script>
    <title>ブログ投稿フォーム</title>
@endsection

@section('contents')
    <div id="blog-posting" class="main-contents">
        <div class="main-contents-wrapper">
            <h1>ブログ投稿フォーム</h1>
            <div class="blog-posting-form-wrapper">
                <form id="blog-posting-form" class="blog-posting-form" action="{{ route('post_blog') }}" method="POST">
                @csrf
                    <h2>サムネイル</h2>
                    <div class="thumbnail-area">
                        <div id="thumbnail-preview-box" class="thumbnail-preview-box">
                            <p>
                                ここにサムネイル用画像をドラッグアンドドロップしてください<br><br><br>
                                ファイルを選択ボタンからも登録できます
                            </p>
                        </div>
                        <input type="file" id="import-thumbail-input" class="import-thumbail-btn" onchange="importThumbnail(this)">
                        <input type="hidden" id="submit-thumbnail-name" name="thumbnail_name">
                        <input type="hidden" id="submit-thumbnail-img" name="thumbnail_img">
                    </div>
                    <h2>タイトル</h2>
                    <input type="text" name="title" class="blog-title" value="{{ old('title') }}">
                    @if($errors->has('title'))
                        <p class="error-message">{{ $errors->first('title') }}</p>
                    @endif
                    <h2>コンテンツ</h2>
                    <textarea id="replacement-contents" name="contents" style="display: none;"></textarea>
                    <div id="original-contents" class="original-contents" contenteditable="true">{!! old('contents') !!}</div>

                    @if($errors->has('contents'))
                        <p class="error-message">{{ $errors->first('contents') }}</p>
                    @endif
                    <br>
                    <input type="submit" value="投稿">
                    @if(session('error_post_blog'))
                        <p class="error-message">{{ session('error_post_blog') }}</p>
                    @endif

                    <input type="hidden" id="create-user-id" name="user_id" value="{{ Auth::id() }}">
                </form>

                <div class="tools-field">
                    <div class="tool-list">
                        <div>
                            <button class="tool-btn" onclick="adoptFontSize()">文字サイズ</button>
                            <input type="number" class="sub-tool-btn" min="1" max="100" onchange="setFontSize(this.value)">
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
                            <input type="number" class="sub-tool-btn" min="0" max="100" onchange="setOpacity(this.value)">
                        </div>
                        <div>
                            <button class="tool-btn" onclick="adoptFontColor()">文字色</button>
                            <input type="color" class="sub-tool-btn" value="#ffffff" onchange="setFontColor(this.value)">
                        </div>
                        <div>
                            <button class="tool-btn" onclick="adoptBackGroundColor()">背景色</button>
                            <input type="color" class="sub-tool-btn" value="#ffffff" onchange="setBackGroundColor(this.value)">
                        </div>
                        <div>
                            <button class="tool-btn" onclick="adoptUrl()">リンク作成</button>
                        </div>
                        <div>
                            <button class="tool-btn" onclick="addImageTool()">画像インポート</button>
                        </div>
                        <div>
                            <button class="tool-btn" onclick="adoptImageSize()">画像サイズ適用</button>
                            <input type="number" class="sub-tool-btn" value="300" onchange="setImageSize(this.value)">
                        </div>

                    </div>
                    <div class="alert-field">
                        <div class="tools-err-msg"></div>
                    </div>
                    <!-- <div class="imported-img-field-wrapper">
                        <div class="imported-img-field"></div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
@endsection