@extends('CommonParts.app')

@section('head')
    @parent
    <script src="{{ asset('js/blogEditing.js') }}" defer></script>
    <title>編集フォーム_{{ $blog[0]['title'] }}</title>
@endsection

@section('contents')
    <div id="blog-posting" class="main-contents">
    <div class="main-contents-wrapper">
        <h1>ブログ編集フォーム</h1>
        <div class="blog-posting-form-wrapper">
            <form id="blog-posting-form" class="blog-posting-form" action="{{ route('edit_blog') }}" method="POST">
            @csrf
                <h2>タイトル</h2>
                <input type="text" name="title" value="{{ $blog[0]['title'] }}">
                @if($errors->has('title'))
                    <p class="error-message">{{ $errors->first('title') }}</p>
                @endif
                <h2>コンテンツ</h2>
                <textarea id="replacement-contents" name="contents" style="display: none;"></textarea>
                <div id="original-contents" class="original-contents" contenteditable="true">{!! $blog[0]['contents'] !!}</div>

                @if($errors->has('contents'))
                    <p class="error-message">{{ $errors->first('contents') }}</p>
                @endif
                <br>
                <input type="submit" value="編集完了">
                @if(session('error_post_blog'))
                    <p class="error-message">{{ session('error_post_blog') }}</p>
                @endif

                <input type="hidden" name="blog_id" value="{{ $blog[0]['id'] }}">
                <input type="hidden" id="create-user-id" name="user_id" value="{{ Auth::id() }}">
            </form>

            <div>
                <div class="tool-list">
                    <div>
                        <button onclick="adoptFontSize()">文字サイズ</button>
                        <input type="number" min="1" max="100" onchange="setFontSize(this.value)">
                    </div>
                    <div>
                        <button onclick="adoptFontStyleItaric()">斜体</button>
                    </div>
                    <div>
                        <button onclick="adoptStrikeThrough()">取消線</button>
                    </div>
                    <div>
                        <button onclick="adoptUnderLine()">下部線</button>
                    </div>
                    <div>
                        <button onclick="adoptOpacity()">透明度</button>
                        <input type="number" min="0" max="100" onchange="setOpacity(this.value)">
                    </div>
                    <div>
                        <button onclick="adoptFontColor()">文字色</button>
                        <input type="color" value="#ffffff" onchange="setFontColor(this.value)">
                    </div>
                    <div>
                        <button onclick="adoptBackGroundColor()">背景色</button>
                        <input type="color" value="#ffffff" onchange="setBackGroundColor(this.value)">
                    </div>
                    <div>
                        <button onclick="adoptUrl()">リンク作成</button>
                    </div>
                    <div>
                        <button onclick="addImage()">画像インポート</button>
                    </div>
                    <div>
                        <button onclick="adoptImageSize()">画像サイズ適用</button>
                        <input type="number" class="set-iamge-size-input-box" value="300" onchange="setImageSize(this.value)">
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