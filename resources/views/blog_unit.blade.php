@if(count($blogs) > 0)
<ul class="blog-list-wrapper">
    @foreach($blogs as $blog)
    <li class="blog-unit box-shadow">
        <a class="blog-unit-wrapper" href="{{ route('blog_detail', ['id' => $blog['id']]) }}">
            <div class="blog-unit-info">
                <p class="title">{{ $blog['title'] }}</p>
                <p class="posted-at">{{ $blog['updated_at'] }}</td>
            </div>
            <p class="post-user"><a href="{{ route('profile_top', ['id' => $blog['created_by']]) }}">{{ $blog['name'] }}</a></p>
            <img class="thumbnail" src="{{ asset('storage/blog_thumbnail_images/noImage.png') }}">
        </a>
    </li>
    @endforeach
</ul>
@else
<p>{{ $word }}</p>
@endif