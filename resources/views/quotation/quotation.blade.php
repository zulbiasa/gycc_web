<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            .services-list, .user-form {
                flex: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Selected Services -->
        <div class="services-list">
            <h4 class="mb-3">Selected Services</h4>
            <div class="services-scrollable">
                <ul class="list-group mb-3" id="selected-services-list"></ul>
            </div>
            <div class="total-cost">
                Total Cost: <span id="total-cost">RM 0.00</span>
            </div>
        </div>
        <!-- User Information Form -->
        <div class="user-form">
            <h4 class="mb-3">User Information</h4>
            <form id="user-form" method="POST">
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
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
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

        document.getElementById('user-form').addEventListener('submit', (event) => {
            event.preventDefault();

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();

            if (!name || !email || !phone) {
                alert('Please fill out all fields correctly.');
                return;
            }

            const selectedServices = JSON.parse(sessionStorage.getItem('selectedServices')) || [];
            const serviceIds = selectedServices.map(service => service.id);

            if (serviceIds.length === 0) {
                alert('No services selected.');
                return;
            }

            const totalCost = selectedServices.reduce((sum, service) => sum + parseFloat(service.cost), 0);
            const formData = {
                name: name,
                email: email,
                phone: phone,
                services: serviceIds,
                totalCost: totalCost,
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


            fetch('/submit-quotation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // Add the token here
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        sessionStorage.removeItem('selectedServices');
                        window.location.href = '{{route('thankyou')}}';
                    } else {
                        alert(data.error || 'An error occurred.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to submit the quotation. Please try again.');
                });
        });
    </script>
</body>
</html>
