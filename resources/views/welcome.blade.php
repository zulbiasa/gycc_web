<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Years Care Connect</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body {
            background: linear-gradient(90deg,rgb(198, 229, 250), #F7E0E3);
            font-family: Arial, sans-serif;
            animation: gradientAnimation 10s ease-in-out infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-size: 200% 200%;
            position: relative;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .welcome-container {
            text-align: center;
        }

        .login-btn {
            position: fixed;
            top: 20px;
            right: 20px;
        }

        .service-plan-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-transparent">
        <a href="{{ route('login') }}" class="btn btn-primary login-btn">Login</a>
    </nav>
    <div class="welcome-container">
        <h1>Welcome to Golden Years Care Connect</h1>
        <p>Empowering care and connection for the golden years.</p>
        <a href="{{ route('careplan-quote') }}" class="btn btn-secondary service-plan-btn">View Service Plans</a>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
