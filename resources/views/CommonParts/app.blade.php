@php
    $hideAdvertisementPage = ['blog_posting_form', 'blog_editing_form'];
@endphp


<!DOCTYPE html>
<html lang="ja">
<head>
    @section('head')
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/common.css') }}">
        <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
        <link rel="stylesheet" href="{{ asset('css/CommonParts/main.css') }}">
        <script src="{{ asset('js/displayScreenSizeSetting.js') }}" defer></script>
        <script src="{{ asset('js/blogUnitSizeSetting.js') }}" defer></script>
    @show
</head>
<body>
    @include('CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('CommonParts.nav')

                @yield('contents')

                @if(!in_array(Route::currentRouteName(), $hideAdvertisementPage))
                    @include('CommonParts.advertisement')
                @endif
            </div>
        </main>

    @include('CommonParts.footer')

    @yield('js')

</body>
</html>