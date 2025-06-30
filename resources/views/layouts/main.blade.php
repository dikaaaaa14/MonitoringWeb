<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Tambahkan CSS lainnya sesuai yang kamu pakai -->
</head>
<body>
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Topbar --}}
    @include('partials.topbar')

    {{-- Konten utama --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- JS --}}
    <script src="{{ asset('js/script.js') }}"></script>
    <!-- Tambahkan JS lain sesuai kebutuhan -->
</body>
</html>
