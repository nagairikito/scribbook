    @if(session('error'))
        <p class="error-message">{{ session('error') }}</p>
    @endif

    @if(session('success_login'))
        <p class="success-message">{{ session('success_login') }}</p>
    @endif

    @if(session('success_logout'))
        <p class="success-message">{{ session('success_logout') }}</p>
    @endif

    @if(session('success_delete'))
        <p class="success-message">{{ session('success_delete') }}</p>
    @endif

    @if(session('error_delete'))
        <p class="error-message">{{ session('error_delete') }}</p>
    @endif

    @if(session('success_post_blog'))
        <p class="success-message">{{ session('success_post_blog') }}</p>
    @endif

    @if(session('error_delete'))
        <p class="error-message">{{ session('error_post_blog') }}</p>
    @endif

    @if(session('error_get_blog_detail'))
        <p class="error-message">{{ session('error_get_blog_detail') }}</p>
    @endif

