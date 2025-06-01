@extends('CommonParts.app')

@section('head')
    @parent
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script> <!-- JQuery-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/talkRoom.css') }}">
    <title>トークルーム｜{{ $talkRoom['recipient_name'] }}</title>
@endsection

@section('contents')
    <div id="talk-room" class="main-contents">
        <div class="message-display-header">
            <a class="return-btn-wrapper mr-15p" href="{{ route('talk_room_list') }}"><i class="bi bi-chevron-left fos-1_5rem"></i></a>
            <p>{{ $talkRoom['recipient_name'] }}</p>
        </div>
        <div class="contents">
            <div class="message-display-wrapper">
                <div class="message-display">
                </div>
            </div>

            <div class="message-send-textbox">
                <form>
                    @csrf
                    <input type="text" class="input-message" name="message">
                    <!-- <textarea name="message" class="input-message" rows="2" cols="100"></textarea> -->
                    <input type="hidden" name="sender" value="{{ Auth::id() }}">
                    <input type="hidden" name="recipient" value="{{ $talkRoom['recipient'] }}">
                    <label for="message-send-button" class="message-send-button-wrapper"><i class="bi bi-send message-send-button"></i></label>
                    <button id="message-send-button" class="hidden-message-send-button" type="submit" style="display: none;"></button>
                    <!-- <button id="message-send-button" type="submit" style="display: none;"></button> -->

                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        //message-display以下の要素
        let replacementResHTML;

        // Ajax通信 
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $("[name='csrf-token']").attr("content")
                },
            })

            //メッセージ送信
            $('.hidden-message-send-button').on('click', function(e) {
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
                        sender: sender,
                        recipient: recipient,
                        message: message,
                    },

                })
                .done((res) => {
                    getMessages(res.data);
                })
                .fail((error) => {
                });
            });

        });

        //画面ロード時
        window.addEventListener("DOMContentLoaded", function() {
            getMessages(setUpUser().data1, true);
            polling();
        });

        //対象ユーザーの設定
        function setUpUser() {
            let queryString = window.location.search;
            let urlParams = new URLSearchParams(queryString);
            let sender = urlParams.get("sender");
            let recipient = urlParams.get("recipient");
            let data1 = {
                'sender': sender,
                'recipient': recipient
            };
            let data2 = {
                'sender': recipient,
                'recipient': sender
            };
            let datas = {
                'data1': data1,
                'data2': data2,
            }

            return datas;
        }

        function getScrollHeight() {
            let elm = document.querySelector('.message-display');
            elm.scrollTop = elm.scrollHeight;
        }

        //メッセージの取得
        function getMessages(request, firstFlag = false) {
            sender = request.sender;
            recipient = request.recipient;

            $.ajax({
                    url: '/getMessages',
                    method: 'GET',
                    dataType: "json",
                    data: {
                        sender: sender,
                        recipient: recipient,
                    },
            })
            .done((res) => {
                html = parse(res.html);
console.log(html)
                if(firstFlag == true) {
                    replacementResHTML = html
                    $('.message-display').html(html);
                    return;
                }

                MatchConfirmationFlag = equalFlag(html)

                if(MatchConfirmationFlag == true) {
                    return;
                }

                $('.message-display').html('');
                $('.message-display').html(html);
                replacementResHTML = html;

                // talkRoom.messages.forEach(function(message) {
                //     let selector;
                //     if (message.created_by == talkRoom.sender) {
                //         selector = "send";
                //     } else {
                //         selector = "receive";
                //     }
                //     html = `
                //         <div class="message ${selector}">
                //             ${selector === "receive" ?
                //                 `
                //                 <div>
                //                     <img class="user-icon" src="{{ asset('storage/user_icon_images/${talkRoom.recipient[0].icon_image }') }}">
                //                 </div>
                //                 `
                //                 :
                //                 ``
                //             }
                //             <p class="message-contents">${message.message}</p>
                //             <p class="send-at">${message.updated_at}</p>
                //         </div>
                //     `;
                //     $(".message-display").append(html);
                // });
            })
            .fail((error) => {
                console.log('失敗！');
            });
        }

        //一定間隔処理
        function polling() {
            let datas = setUpUser(); // 送信者と受信者をURLパラメータから取得
            const POLLING_DURATION = 2000 // ポーリング間隔

            // ポーリング処理
            setInterval(function() {
                getMessages(datas['data1']);
            }, POLLING_DURATION);
        }

        //前のhtmlと最新のhtmlの要素が一致しているかを判定
        function equalFlag(data) {
            return replacementResHTML.isEqualNode(data);
        }

        //htmlのパース化
        function parse(htmlStr) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlStr, 'text/html');
            const newNode = doc.body.firstChild;
            return newNode;
        }


    </script>
@endsection
