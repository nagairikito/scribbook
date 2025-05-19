<!DOCTYPE html>
<html lang="ja">

<head>
    @section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CommonParts/main.css') }}">
    <script src="{{ asset('js/CommonParts/getScreenSize.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
    <!-- <script src="{{ asset('js/talkRoomList.js') }}" defer></script> -->
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script> <!-- JQuery-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>トークルームリスト</title>
</head>

<body>
    @include('CommonParts.header')
    <main id="main">
        <div class="main-wrapper">
            @include('CommonParts.nav')

            <div class="main-contents">
                <h1>トーク</h1>
                <ul class="talk-room-list"></ul>
            </div>

            @include('CommonParts.advertisement')
        </div>
    </main>

    @include('CommonParts.footer')

<script defer>
    let replacementResHTML;

    document.addEventListener("DOMContentLoaded", function() {
        getTalkRoomList(true);
    });
    polling();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $("[name='csrf-token']").attr("content")
        },
    })

    function polling() {
        pollingInterval = setInterval(function() {
            getTalkRoomList(false);
        }, 2000);
    }

    function getTalkRoomList(firstFlag) {
        $.ajax({
                url: '/getTalkRoomList',
                method: 'GET',
                dataType: "json",
            })
            .done((res) => {
                html = parse(res.html);

                if(firstFlag == true) {
                    replacementResHTML = html
                    $('.talk-room-list').html(html);
                }

                MatchConfirmationFlag = equalFlag(html)

                if(MatchConfirmationFlag == true) {
                    return;
                }

                $('.talk-room-list').html('');
                $('.talk-room-list').html(html);
                replacementResHTML = html;
            })
            .fail((error) => {
                console.log('失敗！');
            });
    }

    function equalFlag(data) {
        return replacementResHTML.isEqualNode(data);
    }

    function parse(htmlStr) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlStr, 'text/html');
        const newNode = doc.body.firstChild;
        return newNode;
    }
</script>
</body>

</html>



