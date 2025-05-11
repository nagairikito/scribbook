<!DOCTYPE html>
<html lang="ja">
<head>
    @section('head')
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/common.css') }}">
        <link rel="stylesheet" href="{{ asset('css/CommonParts/main.css') }}">
        <script src="{{ asset('js/CommonParts/getScreenSize.js') }}" defer></script>
    @show
</head>
<body>
    @include('CommonParts.header')
        <main id="main">
            <div class="main-wrapper">
                @include('CommonParts.nav')

                @yield('contents')

                @include('CommonParts.advertisement')
            </div>
        </main>

    @include('CommonParts.footer')

    @yield('js')

</body>
</html>