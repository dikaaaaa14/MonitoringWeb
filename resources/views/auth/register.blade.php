<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #43cea2, #185a9d);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .register-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .btn-register {
            background-color: #17a2b8;
            color: white;
            width: 100%;
        }
        .login-link {
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h3 class="text-center mb-4">Register</h3>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <input type="text" name="name" placeholder="Name" class="form-control" required>
            </div>

            <div class="form-group">
                <input type="email" name="email" placeholder="Email" class="form-control" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password" class="form-control" required>
            </div>

            <div class="form-group">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-register">Register</button>

            <div class="login-link">
                <a href="{{ route('login') }}">Sudah punya akun? Login!</a>
            </div>
        </form>
    </div>
</body>
</html>
