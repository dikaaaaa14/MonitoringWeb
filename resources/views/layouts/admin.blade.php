<!DOCTYPE html>
<html>
<head>
    @include('dashboard.partials.head') <!-- CSS ditaruh di sini -->
</head>
<body>
    <div class="wrapper">
        @include('dashboard.partials.sidebar') <!-- Sidebar -->
        
        <div id="content">
            @yield('content') <!-- Konten utama -->
        </div>
    </div>

    @include('dashboard.partials.scripts') <!-- JS ditaruh di sini -->
    @yield('scripts') <!-- JS tambahan -->
</body>
</html>