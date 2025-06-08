@if(count($blogs) > 0)
<ul class="blog-list-wrapper">
    @foreach($blogs as $blog)
    <li class="blog-unit">
        <a href="{{ route('blog_detail', ['id' => $blog['id']]) }}">
            <p class="title">{{ $blog['title'] }}</p>
            <p class="blog-contents">{{ $blog['contents'] }}</p>
            <p class="posted-at">{{ $blog['created_at'] }}</td>
        </a>
        <p class="post-user"><a href="{{ route('profile_top', ['id' => $blog['created_by']]) }}">{{ $blog['name'] }}</a></p>
    </li>
    @endforeach
</ul>
@else
<p>{{ $word }}</p>
@endif