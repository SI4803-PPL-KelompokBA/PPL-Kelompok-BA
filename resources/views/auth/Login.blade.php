<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CBAM Tracker</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            width: 100%;
            font-family: 'Segoe UI', sans-serif;
            background-image:
                linear-gradient(
                    rgba(10, 35, 20, 0.30),
                    rgba(10, 35, 20, 0.40)
                ),
                url("{{ asset('images/login-bg.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 390px;
            padding: 38px 35px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        }

        .logo {
            width: 85px;
            height: 85px;
            border-radius: 24px;
            background: linear-gradient(
                145deg,
                #e5f8e7,
                #c9edcf
            );
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(36, 131, 75, 0.12);
        }

        .logo i {
            font-size: 43px;
            color: #24834B;
        }

        .login-card h2 {
            font-size: 23px;
            font-weight: 700;
            color: #173D29;
            margin-bottom: 9px;
        }

        .login-card .subtitle {
            font-size: 13px;
            color: #8a958e;
            margin-bottom: 25px;
        }

        .form-control {
            height: 46px;
            border: 1px solid #e0e7e2;
            border-radius: 8px;
            font-size: 13px;
            padding: 12px;
        }

        .form-control:focus {
            border-color: #24834B;
            box-shadow: 0 0 0 3px rgba(36, 131, 75, 0.10);
        }

        .input-group-text {
            background: white;
            border: 1px solid #e0e7e2;
            border-left: 0;
            cursor: pointer;
            color: #718078;
        }

        .login-btn {
            width: 100%;
            height: 45px;
            border: none;
            border-radius: 8px;
            background: #24834B;
            color: white;
            font-size: 14px;
            font-weight: 600;
            margin-top: 8px;
            transition: 0.2s;
        }

        .login-btn:hover {
            background: #176638;
        }

        .back-btn {
            display: block;
            width: 100%;
            padding: 11px;
            margin-top: 12px;
            border: 1px solid #24834B;
            border-radius: 8px;
            background: white;
            color: #24834B;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .back-btn:hover {
            background: #e5f5e7;
            color: #176638;
        }

        .footer-text {
            margin-top: 22px;
            font-size: 11px;
            color: #89958d;
        }

        .alert {
            font-size: 12px;
            text-align: left;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
            }

            .login-card h2 {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">

       <div class="logo">
        <svg xmlns="http://www.w3.org/2000/svg"
            width="45"
            height="45"
            viewBox="0 0 24 24"
            fill="none"
            stroke="#24834B"
            stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M20 4c-7 0-13 2-15 8-1.5 4.5 2 8 6 6 6-2 9-8 9-14Z"/>
            <path d="M5 19c3-5 7-8 12-11"/>
        </svg>
    </div>

        <h2>Welcome to CBAM Tracker</h2>


        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('cbam.login.process') }}" method="POST">
            @csrf

            <div class="mb-3">
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Email address"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                >
            </div>

            <div class="mb-3">
                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Password"
                        required
                        autocomplete="current-password"
                    >

                    <button
                        class="input-group-text"
                        type="button"
                        onclick="togglePassword()"
                        aria-label="Show or hide password"
                    >
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn">
                Log in
            </button>
        </form>

        <a href="{{ route('welcome') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back to Welcome
        </a>

        <div class="footer-text">
            © 2026 CBAM Tracker
        </div>

    </div>

    <!--Untuk hide and show password pas masukin password -->
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>

</body>
</html>
