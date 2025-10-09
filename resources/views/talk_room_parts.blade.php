<div class="talk-room-wrapper">
    @if(count($talkRoomDatas['messages']) > 0)
        @foreach($talkRoomDatas['messages'] as $message)
            <div class="message {{ $message['created_by'] == $talkRoomDatas['sender'] ? 'send' : 'receive' }}">
                @if($message['created_by'] == $talkRoomDatas['recipient']['id'])
                    <div>
                        @if($talkRoomDatas['recipient']['icon_image'] != 'noImage.png')
                            <img class="user-icon" src="{{ $talkRoomDatas['recipient']['icon_image'] }}">
                        @else
                            <img class="user-icon" src="{{ asset('commonImages/user_icon_images/noImage.png') }}">
                        @endif
                    </div>
                @endif
                <div class="message-contents-wrapper">
                    <p class="message-contents">{{ $message['message'] }}</p>
                    <div class="msg-stauts-sp {{ $message['created_by'] == $talkRoomDatas['sender'] ? 'send' : 'receive' }}">
                        <p class="send-at-sp">{{ $message['updated_at'] }}</p>
                        @if($message['read_flag'] == config('consts.TALK.READ_FLAG_ON') && $message['created_by'] == $talkRoomDatas['sender'])
                            <p class="already-read-sp">既読</p>
                        @else
                            <p></p>
                        @endif
                    </div>
                </div>
                <div class="msg-stauts {{ $message['created_by'] == $talkRoomDatas['sender'] ? 'send' : 'receive' }}">
                    @if($message['read_flag'] == config('consts.TALK.READ_FLAG_ON') && $message['created_by'] == $talkRoomDatas['sender'])
                        <p class="already-read">既読</p>
                    @else
                        <p></p>
                    @endif
                    <p class="send-at">{{ $message['updated_at'] }}</p>
                </div>
            </div>
        @endforeach    
    @else
        <div></div>
    @endif
</div>

