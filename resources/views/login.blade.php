<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYCC+ Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(90deg,rgb(198, 229, 250), #F7E0E3);
            font-family: 'Arial', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background-color: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .login-box h1 {
            font-weight: bold;
            color: #003366;
            margin-bottom: 10px;
        }

        .login-box p {
            color: #6c757d;
            font-size: 14px;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #003366;
            border: none;
            border-radius: 8px;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #002244;
        }

        .form-check-input {
            margin-top: 3px;
        }

        footer {
            font-size: 12px;
            margin-top: 20px;
            color: #6c757d;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>GYCC+</h1>
            <p>The GYCC+ System is designed to assist in senior citizen care management. It streamlines caregiver tasks, monitors senior activities, and enhances the quality of client data management.</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username Input -->
                <div class="mb-3 text-start">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required autofocus>
                    @error('username')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="mb-3 text-start">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Sign In</button>

                <!-- Admin Contact -->
                <div class="contact-admin">
                    <p>Having trouble logging in?</p>
                    <p>Please contact the system administrator:</p>
                    <p>
                        <strong>Admin Name:</strong> Mrs. Ena Sofea <br>
                        <strong>📧Email:</strong> <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#emailChoiceModal">admin@gyccplus.com</a> <br>
                        <strong>📞Phone:</strong> +60 123-456-789
                    </p>
                </div>

            </form>

            <!-- Footer -->
            <footer>
                &copy;2024 Senior Care Solutions. Empowering lives.
            </footer>
        </div>
    </div>

    <!-- Email Choice Modal -->
    <div class="modal fade" id="emailChoiceModal" tabindex="-1" aria-labelledby="emailChoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailChoiceModalLabel">Choose Your Email Provider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Would you like to send an email through one of the following providers?</p>
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-outline-primary" onclick="openEmail('gmail')">Gmail</button>
                        <button class="btn btn-outline-primary" onclick="openEmail('yahoo')">Yahoo Mail</button>
                        <button class="btn btn-outline-primary" onclick="openEmail('outlook')">Outlook</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEmail(provider) {
            let email = "ainasfea0@gmail.com";
            let subject = "GYCC+ Support Request - Assistance Needed";
            let body = `
                Dear Support Team,

                I am reaching out regarding an issue I encountered while using the GYCC+ system. Below are the details:

                - Issue Description: [Please describe the problem you are facing.]
                - Steps to Reproduce: [Optional - Share the steps leading to the issue.]
                - Username (if applicable): [Enter your username here.]
                - Preferred Contact Method: [Provide your contact details.]

                I would appreciate your assistance in resolving this matter.

                Thank you for your time and support.

                Best regards,
                [Your Name Here]
            `;
            body = encodeURIComponent(body);

            let url = "";
            switch (provider) {
                case 'gmail':
                    url = `https://mail.google.com/mail/?view=cm&fs=1&to=${email}&su=${subject}&body=${body}`;
                    break;
                case 'yahoo':
                    url = `https://compose.mail.yahoo.com/?to=${email}&subject=${subject}&body=${body}`;
                    break;
                case 'outlook':
                    url = `https://outlook.live.com/owa/?path=/mail/action/compose&to=${email}&subject=${subject}&body=${body}`;
                    break;
            }

            // Open the chosen email provider
            window.open(url, '_blank');
            // Close the modal
            bootstrap.Modal.getInstance(document.getElementById('emailChoiceModal')).hide();
        }
    </script>
</body>
</html>
