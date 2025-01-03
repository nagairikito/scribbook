<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScribBook</title>

    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/TopPage/toppage.css">

</head>
<body>
    @include('a_CommonParts.header')
    
    <main>
        <div class="main-wrapper">

            <div class="advertise-area">広告</div>

            <div class="main-contents">
                @include('TopPage.session_messages')

                <div class="topics">
                    <ul class="topic-wrapper">
                        <li class="topic-article">
                            <a href="" class="topic-article-wrapper">
                                <div class="topic-article-title">topic1</div>
                                <div class="topic-article-image">topic-image</div>
                                <div class="topic-article-contents">topic-contents</div>
                            </a>    
                        </li>
                        <li class="topic-article">
                            <div class="topic-article-title">topic2</div>
                            <div class="topic-article-image">topic-image</div>
                            <div class="topic-article-contents">topic-contents</div>
                        </li>
                        <li class="topic-article">
                            <div class="topic-article-title">topic3</div>
                            <div class="topic-article-image">topic-image</div>
                            <div class="topic-article-contents">topic-contents</div>
                        </li>
                        <li class="topic-article">
                            <div class="topic-article-title">topic4</div>
                            <div class="topic-article-image">topic-image</div>
                            <div class="topic-article-contents">topic-contents</div>
                        </li>
                        <li class="topic-article">
                            <div class="topic-article-title">topic5</div>
                            <div class="topic-article-image">topic-image</div>
                            <div class="topic-article-contents">topic-contents</div>
                        </li>
                    </ul>
                </div>

                <table class="article-list" border="1">
                    <tr>
                        <th>タイトル</th>
                        <th>コンテンツ</th>
                        <th>投稿者</th>
                        <th>投稿日</th>
                    </tr>
                    <tr>
                        <td><a href="">ブログの作り方</a></td>
                        <td><a href="">日々の何気ない出来事を書き込む</a></td>
                        <td class="post-user">srcibookun</td>
                        <td class="posted-at">2024/12/15</td>
                        
                    </tr>
                </table>

            </div>

            <div class="advertise-area">広告</div>
                
        </div>
    </main>

    @include('a_CommonParts.footer')
    
</body>
</html>