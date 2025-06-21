@if(count($blogs) > 0)
<ul class="blog-list-wrapper">
    @foreach($blogs as $blog)
    <li class="blog-unit box-shadow">
        <a class="blog-unit-wrapper" href="{{ route('blog_detail', ['id' => $blog['id']]) }}"></a>
        <div class="blog-unit-info">
            <p class="title">{{ $blog['title'] }}</p>
            <p class="posted-at">{{ $blog['updated_at'] }}</td>
        </div>
    

        <a class="post-user" href="{{ route('profile_top', ['id' => $blog['created_by']]) }}">
            <img class="blog-user-icon" src="{{ asset('storage/user_icon_images/' . $blog['icon_image']) }}">
            <p>{{ $blog['name'] }}</p>
        </a>

        @if($blog['thumbnail'] != 'noImage.png')
            <img class="thumbnail" src="{{ asset('storage/blog_thumbnail_images/' . $blog['blog_unique_id'] . '_' . $blog['thumbnail']) }}">
        @else
            <img class="thumbnail" src="{{ asset('storage/blog_thumbnail_images/noImage.png') }}">
        @endif
    </li>
    @endforeach
</ul>
@else
<p>{{ $word }}</p>
@endif