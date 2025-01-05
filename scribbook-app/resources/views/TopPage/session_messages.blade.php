<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/common.css">

    <title>Document</title>
</head>
<body>
    @if(session('error'))
        <p class="error-message">{{ session('error') }}</p>
    @endif

    @if(session('success_login'))
        <p class="success-message">{{ session('success_login') }}</p>
    @endif

    @if(session('success_logout'))
        <p class="success-message">{{ session('success_logout') }}</p>
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

    @if(session('success_edit_blog'))
        <p class="success-message">{{ session('success_edit_blog') }}</p>
    @endif

    @if(session('success_delete_blog'))
        <p class="success-message">{{ session('success_delete_blog') }}</p>
    @endif
</body>
</html>
