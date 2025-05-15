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
    <script src="{{ asset('js/talkRoomList.js') }}" defer></script>
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
    document.addEventListener("DOMContentLoaded", function() {
        getTalkRoomList();
    });
    polling();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $("[name='csrf-token']").attr("content")
        },
    })

    function polling() {
        initPolling();

        const MAX_POLLING_COUNT = 5 // ポーリング回数
        let pollingInterval;
        let pollingCount = 0;
        // if(pollingCount < MAX_POLLING_COUNT) {
        for (let i = 0; pollingCount < MAX_POLLING_COUNT; i++) {
            execPolling();
            pollingCount += 1;
            // console.log('ポーリング'+pollingCount+'回目');
            if (i == 4) {
                initPolling(pollingInterval);
                console.log('ポーリング終了');
            }
        }

        // initPolling(pollingInterval);
        // console.log('ポーリング終了');


        // ポーリング処理
        function execPolling() {
            pollingInterval = setInterval(function() {
                getTalkRoomList();
            }, 2000);
        }

        // ポーリングの初期化
        function initPolling(pollingInterval = null) {
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        }
    }

    function getTalkRoomList() {
        $.ajax({
                url: '/getTalkRoomList',
                method: 'GET',
                dataType: "json",
            })
            .done((res) => {
                $('.talk-room-list').html('');
                $('.talk-room-list').html(res.html);
            })
            .fail((error) => {
                console.log('失敗！');
            });
    }
</script>
</body>

</html>



