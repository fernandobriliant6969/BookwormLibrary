<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Bookworm Library</title>

        <link rel="icon" type="image/x-icon" href="{{ asset('assets/static/images/logo/favicon.ico') }}">
        <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">
    </head>

    <body>
        @yield('content')

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/compiled/js/loadingAnimation.js') }}"></script>
        @stack('scripts')
    </body>
</html>
