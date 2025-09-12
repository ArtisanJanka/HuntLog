<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f3f4f6;
        }
        .container {
            text-align: center;
        }
        a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            background-color: #24961aff;
            border-radius: 5px;
        }
        a:hover {
            background-color: #24961aff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome</h1>

        @if (Route::has('login'))
            @auth
                <p>You are logged in. <a href="{{ url('/dashboard') }}">Go to Dashboard</a></p>
            @else
                <a href="{{ route('login') }}">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        @endif
    </div>
</body>
</html>
