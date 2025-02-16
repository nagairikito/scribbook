
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/a_CommonParts/main.css') }}">
    <script src="{{ asset('js/a_CommonParts/getScreenSize.js') }}" defer></script>

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
                            @if($talkRoom['messages'] > 0)
                                @foreach($talkRoom['messages'] as $message)

                                    @if($message['created_by'] == Auth::id() )
                                        <div class="message send">
                                            <p class="message-contents">{{ $message['message'] }}</p>
                                            <p class="send-at">{{ $message['updated_at'] }}</p>
                                        </div>

                                    @else
                                        <div class="message receive">
                                            <a href="{{ route('profile_top', $talkRoom['recipient']) }}">
                                                <img class="icon" src="{{ asset('storage/user_icon_images/' .$message['icon_image']) }}">
                                            </a>
                                            <div>
                                                <p class="message-contents">{{ $message['message'] }}</p>
                                            </div>
                                            <p class="send-at">{{ $message['updated_at'] }}</p>
                                        </div>

                                    @endif

                                @endforeach
                            @endif

                        </div>

                        <div class="message-send-textbox">
                            <form action="{{ route('send_message') }}" method="POST">
                            @csrf
                                <input type="text" name="message">
                                <input type="hidden" name="sender" value="{{ Auth::id() }}">
                                <input type="hidden" name="recipient" value="{{ $talkRoom['recipient'] }}">
                                <input type="submit" value="送信">

                            </form>
                        </div>

                    </div>
                </div>

                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')
</body>
</html>