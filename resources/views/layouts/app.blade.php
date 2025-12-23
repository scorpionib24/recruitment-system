<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- Toastr CSS (يجب أن يأتي بعد app.scss لضمان عدم التعارض ) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'] )
</head>
<body>
    <div id="app">
        {{-- ... (محتوى Navbar لا يتغير) ... --}}
        <nav class="navbar ..."> ... </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    {{-- === قسم السكربتات الجديد === --}}

    {{-- 1. jQuery (لا يزال ضرورياً لـ Toastr وكود AJAX الذي كتبناه) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- 2. Toastr JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    {{-- 3. إعدادات Toastr --}}
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-left",
            "preventDuplicates": true,
        };
    </script>

    {{-- 4. السكربتات المخصصة لكل صفحة --}}
    @stack('scripts' )

</body>
</html>
