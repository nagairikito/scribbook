
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Blog/topics.css') }}">
    <script src="{{ asset('js/a_CommonParts/getScreenSize.js') }}" defer></script>
    <script src="{{ asset('js/Blog/topicsGetScreenSize.js') }}" defer></script>


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
                        <ul class="">
                            @if(count($talkRoomList) > 0)
                                @foreach($talkRoomList as $talkRoom)
                                    <li>
                                        <a href="{{ route('display_talk_room', ['sender' => Auth::id(), 'recipient' => $talkRoom['user_id']]) }}">
                                            <img class="" src="{{ asset('storage/user_icon_images/' .$talkRoom['icon_image']) }}">
                                            <div class="">
                                                <p>{{ $talkRoom['name'] }}</p>
                                                    @if($talkRoom['latest_message']['attached_file_path'] == null)
                                                        <p>{{ $talkRoom['latest_message']['message'] }}</p>
                                                    @elseif($talkRoom['latest_message']['message'] == null)
                                                        <p>画像を送信しました</p>
                                                    @endif
                                                <p>{{ $talkRoom['updated_at'] }}</p>    
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            
                            @else
                                <li>トークがありません</li>
                            @endif
                        </ul>
                    </div>

                </div>

                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')
</body>
</html>