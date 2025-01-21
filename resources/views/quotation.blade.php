<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(90deg, #E1F0FA, #F7E0E3);
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 20px;
            gap: 20px;
            height: 100%;
        }
        .services-list, .user-form {
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }
        .services-list {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            flex: 2;
        }
        .services-scrollable {
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 15px;
            padding-right: 10px;
        }
        .services-scrollable::-webkit-scrollbar {
            width: 8px;
        }
        .services-scrollable::-webkit-scrollbar-thumb {
            background: #F7E0E3;
            border-radius: 4px;
        }
        .services-scrollable::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }
        .total-cost {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #007BFF;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            padding: 10px 12px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .list-group-item .badge {
            font-size: 14px;
            padding: 5px 10px;
            background-color: #6c757d;
        }
        .user-form {
            flex: 1;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 10px;
        }

        /* Mobile View */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .services-list {
                flex: none;
            }
            .user-form {
                flex: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Top/Left: Selected Services -->
        <div class="services-list">
            <h4 class="mb-3">Selected Services</h4>
            <div class="services-scrollable">
                <ul class="list-group mb-3" id="selected-services-list"></ul>
            </div>
            <div class="total-cost">
                Total Cost: <span id="total-cost">RM 0.00</span>
            </div>
        </div>

        <!-- Bottom/Right: User Information Form -->
        <div class="user-form">
            <h4 class="mb-3">User Information</h4>
            <form action="/submit-user-info" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number:</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth:</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#emailChoiceModal">
                <button type="#" class="btn btn-primary w-100">Submit</button>
                </a>
            </form>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectedServicesList = document.getElementById('selected-services-list');
        const totalCostElement = document.getElementById('total-cost');
        const selectedServices = JSON.parse(sessionStorage.getItem('selectedServices')) || [];
        let totalCost = 0;

        selectedServices.forEach(service => {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item';
            listItem.innerHTML = `
                <span>${service.service}</span>
                <span class="badge">RM ${parseFloat(service.cost).toFixed(2)}</span>
            `;
            selectedServicesList.appendChild(listItem);
            totalCost += parseFloat(service.cost);
        });

        totalCostElement.textContent = `RM ${totalCost.toFixed(2)}`;
    });

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function openEmail(provider) {
        // Retrieve form data
        const name = document.getElementById('name').value;
        const emailInput = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const dob = document.getElementById('dob').value;

        // Validate form data
        if (!name || !emailInput || !phone || !dob) {
            alert("Please fill in all required fields.");
            return;
        }

        // Validate email format
        if (!validateEmail(emailInput)) {
            alert("Please enter a valid email address.");
            return;
        }

        // Retrieve selected services
        const selectedServices = JSON.parse(sessionStorage.getItem('selectedServices')) || [];
        let servicesList = selectedServices.map(
            (service) => `- ${service.service}: RM ${parseFloat(service.cost).toFixed(2)}`
        ).join('\n');
        let totalCost = selectedServices.reduce((sum, service) => sum + parseFloat(service.cost), 0);

        // Construct the email body
        let body = `
Dear Support Team,

User Information:
- Name: ${name}
- Email: ${emailInput}
- Phone: ${phone}
- Date of Birth: ${dob}

Below is the selected services that I'm interest:

Selected Services:
${servicesList}

Total Cost: RM ${totalCost.toFixed(2)}

Feel free to contact me for any information.
Thank you for your assistance.

Best regards,
${name}
        `.trim();

        body = encodeURIComponent(body); // Encode the email body

        // Construct the email URL based on the chosen provider
        let url = "";
        switch (provider) {
            case 'gmail':
                url = `https://mail.google.com/mail/?view=cm&fs=1&to=ainasfea0@gmail.com&su=GYCC+ Support Request - Assistance Needed&body=${body}`;
                break;
            case 'yahoo':
                url = `https://compose.mail.yahoo.com/?to=ainasfea0@gmail.com&subject=GYCC+ Support Request - Assistance Needed&body=${body}`;
                break;
            case 'outlook':
                url = `https://outlook.live.com/owa/?path=/mail/action/compose&to=ainasfea0@gmail.com&subject=GYCC+ Support Request - Assistance Needed&body=${body}`;
                break;
        }

        // Open the email client in a new tab
        window.open(url, '_blank');

        // Close the modal
        bootstrap.Modal.getInstance(document.getElementById('emailChoiceModal')).hide();
    }
</script>
</body>
</html>
