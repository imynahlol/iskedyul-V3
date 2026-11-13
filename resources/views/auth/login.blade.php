<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | iSKEDYUL</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                radial-gradient(circle at top left, rgba(128, 0, 0, 0.12), transparent 40%),
                linear-gradient(135deg, #fffdf7, #f5eeee);
            color: #2b2022;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            padding: 40px;
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(80, 0, 0, 0.09);
            backdrop-filter: blur(16px);
        }

        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            background: #800000;
            color: #ffdf00;
            border-radius: 16px;
            font-size: 25px;
            font-weight: 800;
        }

        .brand h1 {
            color: #800000;
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand p {
            margin-top: 6px;
            color: #817477;
            font-size: 12px;
        }

        .form-heading {
            margin-bottom: 24px;
        }

        .form-heading h2 {
            font-size: 21px;
            font-weight: 700;
            color: #302326;
        }

        .form-heading p {
            margin-top: 5px;
            font-size: 13px;
            color: #897d80;
        }

        .form-group {
            margin-bottom: 19px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #443438;
        }

        .form-group input {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #e8dcdd;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.8);
            color: #302326;
            font-size: 13px;
            outline: none;
            transition: 0.2s ease;
        }

        .form-group input:focus {
            border-color: #800000;
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.08);
        }

        .form-group input::placeholder {
            color: #b4a9ab;
        }

        .error-message {
            margin-top: 6px;
            color: #c62828;
            font-size: 11px;
        }

        .alert-success {
            margin-bottom: 18px;
            padding: 12px 14px;
            background: #edf8ef;
            border: 1px solid #ccebd2;
            border-radius: 9px;
            color: #26733b;
            font-size: 12px;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            margin-top: 5px;
            border: none;
            border-radius: 10px;
            background: #800000;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .login-button:hover {
            background: #620000;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(128, 0, 0, 0.18);
        }

        .register-link {
            margin-top: 24px;
            text-align: center;
            color: #897d80;
            font-size: 12px;
        }

        .register-link a {
            color: #800000;
            font-weight: 700;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            color: #a69a9d;
            font-size: 10px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 28px 22px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">

        <div class="login-card">

            <div class="brand">
                <div class="brand-icon">i</div>
                <h1>iSKEDYUL</h1>
                <p>PUP Lopez Campus | Classroom Reservation System</p>
            </div>

            <div class="form-heading">
                <h2>Welcome back!</h2>
                <p>Sign in using your official Student or Faculty ID.</p>
            </div>

            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="user_id">Student / Faculty ID</label>

                    <input
                        type="text"
                        id="user_id"
                        name="user_id"
                        value="{{ old('user_id') }}"
                        placeholder="Enter your official ID"
                        required
                        autofocus
                    >

                    @error('user_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >

                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="login-button">
                    Sign In
                </button>

            </form>

            <div class="register-link">
                Don't have an account?
                <a href="{{ route('register') }}">Create an account</a>
            </div>

        </div>

        <div class="footer">
            Polytechnic University of the Philippines - Lopez Campus
        </div>

    </div>

</body>
</html>