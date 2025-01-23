<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 20%;
            background-color: #f7f7f7;
            color: #333;
        }
        .countdown {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Replace the current history state to prevent revisiting
            history.replaceState(null, null, location.href);

            let countdown = 5; // Countdown in seconds
            const countdownElement = document.getElementById('countdown');

            const timer = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;

                if (countdown <= 0) {
                    clearInterval(timer);
                    window.location.href = '{{route('welcome')}}';
                }
            }, 1000);

            window.onpopstate = () => {
                // Redirect to the welcome page if the user tries to go back
                window.location.href = '{{route('welcome')}}';
            };
        });
    </script>
</head>
<body>
    <h1>Thank You!</h1>
    <p>Your quotation has been successfully submitted.</p>
    <p>You will be redirected to the welcome page in <span id="countdown" class="countdown">5</span> seconds.</p>
</body>
</html>
