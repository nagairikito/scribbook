
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/main.css') }}">
    <script src="{{ asset('js/a_CommonParts/getScreenSize.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>  <!-- JQuery-->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/Talk/talk.css') }}">



    <title>トークルーム｜{{ $talkRoom['recipient_name'] }}</title>
</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">    
                    <div class="main-contents-wrapper">
                        <div class="message-display-header">
                            <a class="return-btn" href="{{ route('talk_room_list', ['id' => Auth::id()]) }}">⇦</a>
                            <p>{{ $talkRoom['recipient_name'] }}</p>
                        </div>

                        <div class="message-display">
                        </div>

                        <div class="message-send-textbox">
                            <form>
                            @csrf
                                <input type="text" class="input-message" name="message">
                                <input type="hidden" name="sender" value="{{ Auth::id() }}">
                                <input type="hidden" name="recipient" value="{{ $talkRoom['recipient'] }}">
                                <input type="submit" class="send" value="送信">

                            </form>
                        </div>

                    </div>
                </div>

                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')


    <script>
        // Ajax通信 
        $(document).ready(function() {

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $("[name='csrf-token']").attr("content") },
            })

            $('.send').on('click', function (e) {
                e.preventDefault();

                sender = $('input[name="sender"]').val();
                recipient = $('input[name="recipient"]').val();
                message = $('input[name="message"]').val();

                let inputMessage = document.querySelector('.input-message');
                inputMessage.value = '';

                $.ajax({
                    url: "/sendMessage",
                    method: "POST",
                    dataType: "json",
                    data: {
                        sender : sender, 
                        recipient : recipient, 
                        message : message, 
                    },

                })
                .done((res) => {
                    getMessagesFisrt(res.data);
                    polling();
                    // getScrollHeight();
                    console.log('成功');
                })
                .fail((error) => {
                    console.log('失敗！');
                });
            });
        });

        function getMessagesFisrt(request) {
            sender = request.sender;
            recipient = request.recipient;

            $.ajax({
                url: '/getMessages',
                method: 'GET',
                dataType: "json",
                data: {
                    sender : sender, 
                    recipient : recipient, 
                },
            })
            .done((res) => {
                $('.message-display').html('');
                talkRoom = res.talkRoom;
                talkRoom.messages.forEach(function (message) {
                    let selector;
                    if(message.created_by == talkRoom.sender) {
                        selector = "send";
                    } else {
                        selector = "recipient";
                    }
                    html = `
                        <div class="message ${selector}">
                            <p class="message-contents">${message.message}</p>
                            <p class="send-at">${message.updated_at}</p>
                        </div>
                    `;
                    $(".message-display").append(html);
                    getScrollHeight();
                });

                // viewを渡すパターン
                // $(".message-display").html(res.html);
            })
            .fail((error) => {
                console.log('失敗！');
            });
        }

        function getMessages(request) {
            sender = request.sender;
            recipient = request.recipient;

            $.ajax({
                url: '/getMessages',
                method: 'GET',
                dataType: "json",
                data: {
                    sender : sender, 
                    recipient : recipient, 
                },
            })
            .done((res) => {
                $('.message-display').html('');
                talkRoom = res.talkRoom;
                talkRoom.messages.forEach(function (message) {
                    let selector;
                    if(message.created_by == talkRoom.sender) {
                        selector = "send";
                    } else {
                        selector = "recipient";
                    }
                    html = `
                        <div class="message ${selector}">
                            <p class="message-contents">${message.message}</p>
                            <p class="send-at">${message.updated_at}</p>
                        </div>
                    `;
                    $(".message-display").append(html);
                });

                // viewを渡すパターン
                // $(".message-display").html(res.html);
            })
            .fail((error) => {
                console.log('失敗！');
            });
        }

        // 以降 設定
        window.addEventListener("DOMContentLoaded", function () {
            let datas = setUpUser();
            getMessagesFisrt(datas['data1']);
            setInterval(() => {
                getMessages(datas['data1'])
            }, 3000);
        });

        function polling() {
            let datas = setUpUser(); // 送信者と受信者をURLパラメータから取得
            const POLLING_DURATION  = 2000 // ポーリング間隔
            // const POLLING_INTERVAL  = 10000 // ポーリング秒数

            // ポーリング処理
            pollingInterval = setInterval(function() {
                getMessages(datas['data1']);
            }, POLLING_DURATION);

            // ポーリング停止処理
            // setTimeout(() => {
            //     if (pollingInterval) {
            //         console.log('test');
            //         clearInterval(pollingInterval);
            //     }
            // }, POLLING_INTERVAL)
        }

        function setUpUser() {
            let queryString = window.location.search;
            let urlParams = new URLSearchParams(queryString);
            let sender = urlParams.get("sender");
            let recipient = urlParams.get("recipient");
            let data1 = {
                'sender' : sender,
                'recipient' : recipient
            };
            let data2 = {
                'sender' : recipient,
                'recipient' : sender
            };
            let datas = {
                'data1' : data1,
                'data2' : data2,
            }

            return datas;
        }

        function getScrollHeight() {
            let elm = document.querySelector('.message-display');
            elm.scrollTop = elm.scrollHeight;
        }

</script>
</body>
</html>