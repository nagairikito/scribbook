<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブログ投稿フォーム</title>
</head>
<body>
    @include('a_CommonParts.header')

    <main>
        <div class="main-contents-wrapper">
            <h1>ブログ投稿フォーム</h1>
            <div class="post-blog-form-wrapper">
                <form action="{{ route('post_blog') }}" method="POST">
                    @csrf
                    <h2>タイトル</h2>
                    <input type="text" name="title">
                    <h2>コンテンツ</h2>
                    <textarea name="contents" col="" row=""></textarea>
                    <input type="submit" value="投稿">
                </form>
            </div>
        </div>
    </main>

    @include('a_CommonParts.footer')
</body>
</html>