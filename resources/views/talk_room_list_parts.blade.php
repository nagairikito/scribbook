<!-- <ul class=""> -->
    @if(count($talkRoomList) > 0)
        @foreach($talkRoomList as $talkRoom)
            <li>
                <a href="{{ route('display_talk_room', ['sender' => Auth::id(), 'recipient' => $talkRoom['user_id']]) }}">
                    <img class="" src="{{ asset('storage/user_icon_images/' .$talkRoom['icon_image']) }}">
                    <div class="">
                        <p>{{ $talkRoom['name'] }}</p>
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
                </a>
            </li>
        @endforeach
    
    @else
        <li></li>
    @endif
<!-- </ul> -->
