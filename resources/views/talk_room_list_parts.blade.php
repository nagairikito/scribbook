<div class="talk-room-list-wrapper">
    @if(count($talkRoomList) > 0)
        @foreach($talkRoomList as $talkRoom)
            <div class="talk-room-unit">
                <a href="{{ route('display_talk_room', ['sender' => Auth::id(), 'recipient' => $talkRoom['user_id']]) }}" class="talk-room-unit-wrapper">
                    <div class="user-icon-wrapper">
                        <img class="user-icon" src="{{ asset('storage/user_icon_images/' .$talkRoom['icon_image']) }}">
                    </div>    
                    <div class="contents">
                        <div class="top">
                            <p>{{ $talkRoom['name'] }}</p>
                            <div class="unread-messege-count-wrapper mr-20p">
                                <div class="unread-messege-count">2</div>
                            </div>
                        </div>
                        <div class="bottom">
                            @if($talkRoom['latest_message']['message'] != null && $talkRoom['latest_message']['attached_file_path'] == null)
                                <p>{{ $talkRoom['latest_message']['message'] }}</p>
                                <p>{{ $talkRoom['updated_at'] }}</p>    
                            @elseif($talkRoom['latest_message']['message'] == null && $talkRoom['latest_message']['attached_file_path'] != null)
                                <p>画像を送信しました</p>
                                <p>{{ $talkRoom['updated_at'] }}</p>    
                            @else
                                <p></p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    
    @else
        <div></div>
    @endif
</div>
