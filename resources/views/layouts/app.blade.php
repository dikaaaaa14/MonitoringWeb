<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                @include('layouts.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<style>
        .sidebar {
            min-height: 100vh;
            background-color: rgb(29, 48, 82);
            padding: 20px;
            border-right: 1px solid #dee2e6;
        }
        .sidebar-item {
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            color: white;
        }
        .sidebar-item:hover {
            background-color: #e9ecef;
            color: black;
        }
        .sidebar-item.active {
            background-color:rgb(40, 87, 170);
            color: white;
        }
        .main-content {
            padding: 20px;
        }
    </style>
</html>
