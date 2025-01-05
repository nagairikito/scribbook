<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $blog[0]->title }}</title>
</head>
<body>
    @include('a_CommonParts.header')

    <main>
        <div class="main-contents-wrapper">
            <div class="post-blog-form-wrapper">
                @if(session('error_delete_blog'))
                    <p>{{ session('error_delete_blog') }}</p>
                @endif
                <h2>{{ $blog[0]->title }}</h2>
                @if(Auth::id() == $blog[0]->created_by)
                    <form action="{{ route('blog_editing_form')}}" method="POST">
                    @csrf
                        <input type="submit" value="編集">
                        <input type="hidden" name="blog_id" value="{{ $blog[0]->id }}">
                        <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                    </form>
                    <form action="{{ route('delete_blog')}}" method="POST">
                    @csrf
                        <input type="submit" value="削除">
                        <input type="hidden" name="blog_id" value="{{ $blog[0]->id }}">
                        <input type="hidden" name="login_user_id" value="{{ Auth::id() }}">
                    </form>
                @endif
                <p>投稿者：　{{ $blog[0]->name }}</p>
                <p>投稿日時：　{{ $blog[0]->created_at }}</p>
                <p>{{ $blog[0]->contents }}</p>

            </div>
        </div>
    </main>

    @include('a_CommonParts.footer')
</body>
</html>