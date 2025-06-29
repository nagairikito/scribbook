<div class="advertisement-area">
    <div class="advertisement-wrapper">
        <!-- ブログ詳細広告 -->
        @if(isset($blogData['blog_detail_flag']))
            @if(count($blogData['advertisement']) > 0)
                @foreach($blogData['advertisement'] as $advertisement)
                    <a href="{{ $advertisement['url'] }}" target="_blank" class="advertisement_image">
                        <img src="{{ asset('storage/advertisement_images/' .$advertisement['advertisement_image_name']) }}" >
                    </a>
                    
                    @if($advertisement['created_by'] == Auth::id() || $blog[0]['created_by'] == Auth::id())
                        <form action="{{ route('delete_advertisement') }}" method="POST">
                        @csrf
                            <input type="hidden" name="advertisement_id" value="{{ $advertisement['id'] }}">
                            <input type="hidden" name="blog_id" value="{{ $advertisement['blog_id'] }}">
                            <input type="hidden" name="advertisement_image_name" value="{{ $advertisement['advertisement_image_name'] }}">
                            <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                            <input type="submit" value="広告を削除">
                            @if(session('error_delete_advertisement'))
                                <p class="error-message">{{ session('error_delete_advertisement') }}</p>
                            @endif
                        </form>
                    @endif
                    @endforeach
            @else
                <form action="{{ route('register_advertisement') }}" method="POST" enctype="multipart/form-data" class="adv_regster_frame">
                @csrf
                    <ul>
                        <li>
                            <p>広告を登録する</p>
                        </li>
                        <li>
                            <p>画像</p>
                            <input type="file" name="advertisement_image_file">
                        </li>
                        <li>
                            <p>URL</p>
                            <input type="text" name="url">
                        </li>
                        <li>
                            <input type="submit" value="登録">
                        </li>
                    </ul>
                    <input type="hidden" name="target_blog" value="{{ $blogData['blog']['id'] }}">
                    <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                    @if(session('error_register_advertisement'))
                        <p class="error-message">{{ session('error_register_advertisement') }}</p>
                    @endif
                </form>
            @endif
        @endif
    </div>
</div>