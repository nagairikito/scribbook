@if(session('success_update'))
    <p class="success-message">{{ session('success_update') }}</p>
@endif

@if(session('error_logout'))
    <p class="error-message">{{ session('error_logout') }}</p>
@endif

@if(session('success_edit_blog'))
    <p class="success-message">{{ session('success_edit_blog') }}</p>
@endif

@if(session('success_delete_blog'))
    <p class="success-message">{{ session('success_delete_blog') }}</p>
@endif

