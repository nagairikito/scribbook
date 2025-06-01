<div class="talk-room-wrapper">
    @if(count($talkRoomDatas['messages']) > 0)
        @foreach($talkRoomDatas['messages'] as $message)
            <div class="message {{ $message['created_by'] == $talkRoomDatas['sender'] ? 'send' : 'receive' }}">
                @if($message['created_by'] == $talkRoomDatas['recipient'][0]['id'])
                    <div>
                        <img class="user-icon" src="{{ asset('storage/user_icon_images/' . $talkRoomDatas['recipient'][0]['icon_image']) }}">
                    </div>
                @endif
                <p class="message-contents">{{ $message['message'] }}</p>
                <p class="send-at">{{ $message['updated_at'] }}</p>
            </div>
        @endforeach
    
    @else
        <div></div>
    @endif
</div>

