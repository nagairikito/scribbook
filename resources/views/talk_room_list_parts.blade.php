<div class="talk-room-list-wrapper">
    @if(count($talkRoomList) > 0)
        @foreach($talkRoomList as $talkRoom)
            <div class="talk-room-unit">
                <a href="{{ route('display_talk_room', ['sender' => Auth::id(), 'recipient' => $talkRoom['user_id']]) }}" class="talk-room-unit-wrapper">
                    <div class="user-icon-wrapper">
                        <img class="user-icon" src="{{ $talkRoom['icon_image'] }}">
                    </div>    
                    <div class="contents">
                        <div class="top">
                            <p class="user-name">{{ $talkRoom['name'] }}</p>
                            <div class="unread-messege-count-wrapper mr-20p">
                                @if($talkRoom['unReadMsgCount'] > 0)
                                    <div class="unread-messege-count-circle">
                                        <span class="unread-messege-count">{{$talkRoom['unReadMsgCount']}}</span>
                                    </div>
                                @else
                                    <div></div>
                                @endif
                            </div>
                        </div>
                        <div id="bottom" class="bottom">
                            @if($talkRoom['message'] != null && $talkRoom['attached_file_path'] == null)
                                <div class="message-wrapper">
                                    <p class="message">{{ $talkRoom['message'] }}</p>
                                </div>
                                <div class="send-time-wrapper">
                                    <p class="send-time">{{ $talkRoom['talk_created_at'] }}</p>
                                </div>    
                            @elseif($talkRoom['message'] == null && $talkRoom['attached_file_path'] != null)
                                <p>画像を送信しました</p>
                                <div>
                                    <p>{{ $talkRoom['talk_created_at'] }}</p>
                                </div>    
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

