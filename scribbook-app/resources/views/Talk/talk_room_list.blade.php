
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Blog/topics.css') }}">
    <script src="{{ asset('js/a_CommonParts/getScreenSize.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>  <!-- JQuery-->
    <meta name="csrf-token" content="{{ csrf_token() }}">



    <title>トークルームリスト</title>
</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">    
                    <div class="main-contents-wrapper">
                        <h1>トーク</h1>
                        <ul class="talk-room-list">
                        </div>
                </div>

                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            getTalkRoomList();
        });
        polling();
        
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $("[name='csrf-token']").attr("content") },
        })

        function polling() {
            initPolling();

            const MAX_POLLING_COUNT = 5 // ポーリング回数
            let pollingInterval;
            let pollingCount = 0;
            // if(pollingCount < MAX_POLLING_COUNT) {
            for(let i=0; pollingCount<MAX_POLLING_COUNT; i++) {
                execPolling();
                pollingCount += 1;
                // console.log('ポーリング'+pollingCount+'回目');
                if(i==4) {
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