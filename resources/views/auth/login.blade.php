<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">    
<body>
    <div class="login-box">
       <h2 class="login-title">
        Login
        <img src="{{ asset('img/puyuhkecik.png') }}" alt="Quail Icon" class="title-icon">
        </h2>

        <form method="POST" action="{{ route('login.process') }}">
            @csrf 
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-login mt-3">Login</button>
            <div class="text-center mt-3">
             Belum punya akun? <a href="{{ route('register') }}">Register</a>
            </div>
        </form>
    </div>
    <div class="social-icon">
    <a href="https://www.instagram.com/pradikaa___/" target="_blank">
        <img src="{{ asset('img/instagram.png') }}" alt="Instagram" />
    </a>
    <a href="https://www.tiktok.com/@mynameisdikkk" target="_blank">
        <img src="{{ asset('img/tiktok.png') }}" alt="Tiktok" />
    </a>
</div>
</body>
<style>
        body {
            background: url('{{ asset("img/test.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        
        /* Optional: Add overlay for better text readability */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }
        
        .login-box {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-login {
            background-color:rgb(29, 48, 82);
            color: white;
            width: 100%;
            border: none;
        }
        
        .btn-login:hover {
            background-color:rgb(29, 48, 82);
            color: white;
        }
        
        .register-link {
            margin-top: 15px;
            text-align: center;
        }
        
        h2 {
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            font-weight: bold;
        }
        
        label {
            color: #fff;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        
        .text-center a {
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        
        .text-center {
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        
        /* Make form elements more readable */
        .form-control {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: #333;
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 0 0.2rem rgba(29, 48, 82);
            border-color:rgb(29, 48, 82);
        }
        
        .form-control::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }
        .social-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 3;
        display: flex;
        gap: 10px;
        }

        .social-icon img {
        width: 32px;
        height: 32px;
        transition: transform 0.3s ease;
        }

        .social-icon img:hover {
        transform: scale(1.1);
        }
        .login-title {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 50px;
        font-weight: bold;
        color: #fff; /* sesuaikan warna teks jika background gelap */
        }

        .title-icon {
        width: 60px;
        height: auto;
        }


    </style>
</head>
</html>