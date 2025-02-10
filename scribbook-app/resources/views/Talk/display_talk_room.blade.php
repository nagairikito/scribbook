
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


    <title>マイブログ</title>
</head>
<body>
    @include('a_CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('a_CommonParts.nav')

                <div class="main-contents">    
                    <div class="main-contents-wrapper">
                        <h1>{{ $talkRoom['recipient'] }}</h1>
                        @if($talkRoom['messages'] > 0)
                            <div>
                                @foreach($talkRoom['messages'] as $message)
                                    <p>{{ $message['message'] }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('send_message') }}" method="POST">
                        @csrf
                            <input type="text" name="message">
                            <input type="hidden" name="sender" value="{{ Auth::id() }}">
                            <input type="hidden" name="recipient" value="{{ $talkRoom['recipient'] }}">
                            <input type="submit" value="送信">

                        </form>
                    </div>

                </div>

                @include('a_CommonParts.advertise')
            </div>
        </main>
    @include('a_CommonParts.footer')
</body>
</html>