<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Proyecto')</title>

    {{-- CSS y JS globales --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS extra por página --}}
    @stack('styles')
</head>

<body>
    @include('components.header')
    <main>
        @yield('content')
    </main>

    @include('components.footer')

    {{-- JS extra por página --}}
    @stack('scripts')
</body>

</html>
