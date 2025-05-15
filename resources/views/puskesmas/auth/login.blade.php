<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>Login - Laravel</title>
    <style>
        /* Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        .login-container {
            background: white;
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px 30px;
        }

        .login-container h1 {
            font-weight: 700;
            font-size: 2rem;
            color: #4a4a4a;
            margin-bottom: 30px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 0.9rem;
            margin-bottom: 6px;
            color: #555;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"] {
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1.8px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #667eea;
            outline: none;
        }

        .remember-me {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            color: #555;
            font-size: 0.9rem;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        button[type="submit"] {
            background: #667eea;
            color: white;
            font-size: 1.1rem;
            padding: 14px 0;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            transition: background-color 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        button[type="submit"]:hover {
            background: #5a6cdc;
            box-shadow: 0 6px 20px rgba(90, 108, 220, 0.6);
        }

        .links {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: #4a54e1;
            text-decoration: underline;
        }

        .error-message {
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: #e74c3c;
            font-weight: 600;
            text-align: center;
        }

        @media (max-width: 400px) {
            .login-container {
                padding: 30px 20px;
                border-radius: 10px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <h1>Login</h1>

    @if ($errors->any())
        <div class="error-message">
            <ul style="list-style:none;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('loginUser') }}">
        @csrf

        <label for="email">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com" />

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required placeholder="********" />

        <div class="remember-me">
            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} />
            <label for="remember" style="margin:0; cursor:pointer;">Remember Me</label>
        </div>

        <button type="submit">Sign In</button>

        <div class="links">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            @endif
            @if (Route::has('register'))
                <a href="{{ route('register') }}">Register</a>
            @endif
        </div>
    </form>
</div>
</body>
</html>

