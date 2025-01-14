@if(session('error_delete_blog'))
    <p class="error-message">{{ session('error_delete_blog') }}</p>
@endif

@if(session('success_register_favorite_blog'))
    <p class="success-message">{{ session('success_register_favorite_blog') }}</p>
@endif

@if(session('error_register_favorite_blog'))
    <p class="error-message">{{ session('error_register_favorite_blog') }}</p>
@endif
