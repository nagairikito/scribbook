@php
    $hideAdvertisementPage = ['blog_posting_form', 'blog_editing_form'];
@endphp


<!DOCTYPE html>
<html lang="ja">
<head>
    @section('head')
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/common.css') }}">
        <link rel="stylesheet" href="{{ asset('css/blogUnit.css') }}">
        <link rel="stylesheet" href="{{ asset('css/CommonParts/main.css') }}">
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script> <!-- JQuery-->
        <script src="{{ asset('js/displayScreenSizeSetting.js') }}" defer></script>
        <script src="{{ asset('js/blogUnitSizeSetting.js') }}" defer></script>
        <script src="{{ asset('js/common.js') }}" defer></script>
    @show
</head>
<body>
    @include('CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('CommonParts.nav')

                @yield('contents')

                @if(!in_array(Route::currentRouteName(), $hideAdvertisementPage))
                    @if(!isset($blogData))
                        @include('CommonParts.advertisement')
                    @else
                        @include('CommonParts.advertisement', ['blogData' => $blogData])
                    @endif
                @endif
            </div>
        </main>

    @include('CommonParts.footer')

    @yield('js')

</body>
</html>