@extends('CommonParts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
    <link rel="stylesheet" href="{{ asset('css/talkRoomList.css') }}">
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script> <!-- JQuery-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>トークルームリスト</title>
@endsection

@section('contents')
    <div class="main-contents">
        <h1>トーク</h1>
        <div class="talk-room-list"></div>
    </div>
@endsection

@section('js')
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
    // function polling() {
    //     initPolling();

    //     const MAX_POLLING_COUNT = 5 // ポーリング回数
    //     let pollingInterval;
    //     let pollingCount = 0;
    //     // if(pollingCount < MAX_POLLING_COUNT) {
    //     for (let i = 0; pollingCount < MAX_POLLING_COUNT; i++) {
    //         execPolling();
    //         pollingCount += 1;
    //         // console.log('ポーリング'+pollingCount+'回目');
    //         if (i == 4) {
    //             initPolling(pollingInterval);
    //             console.log('ポーリング終了');
    //         }
    //     }

    //     // initPolling(pollingInterval);
    //     // console.log('ポーリング終了');


    //     // ポーリング処理
    //     function execPolling() {
    //         pollingInterval = setInterval(function() {
    //             getTalkRoomList();
    //         }, 2000);
    //     }

    //     // ポーリングの初期化
    //     function initPolling(pollingInterval = null) {
    //         if (pollingInterval) {
    //             clearInterval(pollingInterval);
    //         }
    //     }
    // }

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
@endsection