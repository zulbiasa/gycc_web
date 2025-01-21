<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        /* Gradient background */
        body {
            background: linear-gradient(90deg, #E1F0FA, #F7E0E3);
            font-family: 'Arial', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        html {
            overflow: scroll;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar {
            width: 0;  /* Remove scrollbar space */
            background: transparent;  /* Optional: just make scrollbar invisible */
        }
        /* Optional: show position indicator in red */
        ::-webkit-scrollbar-thumb {
            background: #FF0000;
        }

        @keyframes gradientAnimation {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        /* Card styling */
        .card {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
        }

        .card-body {
            padding: 20px;
        }

        /* Highlight on hover */
        .card:hover {
            transform: scale(1.02);
            transition: all 0.2s ease-in-out;
        }

        /* Button styling */
        .btn-primary, .btn-secondary {
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn-primary:hover, .btn-secondary:hover {
            opacity: 0.9;
            transition: opacity 0.3s ease-in-out;
        }

        /* Fixed Footer */
        #fixed-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #ffffff;
            padding: 10px 20px;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #total-cost {
            color: #007BFF;
            font-weight: bold;
            font-size: 20px;
        }

        /* Services container */
        #services-container {
            flex-grow: 1;
            padding-bottom: 60px; /* Space for footer */
            overflow-y: auto; /* Enables only vertical scrolling within the services container */
        }

        /* Responsive layout */
        @media (max-width: 768px) {
            .card {
                margin-bottom: 20px;
            }
            #fixed-footer {
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4" style="color: #007BFF;">Choose Your Services</h1>
        <p class="text-center text-muted mb-5">
            Select the services you require. You can add all services at once or choose them individually.
        </p>

        <!-- Buttons -->
        <div class="text-center mb-4">
            <button id="add-all-services" class="btn btn-secondary">Add All Services</button>
            <button id="clear-all-services" class="btn btn-danger ms-2">Clear All Services</button>
        </div>

        <!-- Services Grid -->
        <div id="services-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"></div>

        <!-- Fixed Footer -->
        <div id="fixed-footer">
            <h4>Total Cost: <span id="total-cost">RM 0.00</span></h4>
            <a href="/quotation" class="btn btn-primary" id="submit-selection">Submit Selection</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const servicesContainer = document.getElementById('services-container');
            const totalCostElement = document.getElementById('total-cost');
            const addAllButton = document.getElementById('add-all-services');
            const clearAllButton = document.getElementById('clear-all-services');
            let totalCost = 0;

            // Fetch services from Laravel controller
            fetch('/fetch-services')
                .then(response => response.json())
                .then(services => {
                    servicesContainer.innerHTML = ''; // Clear previous content

                    Object.keys(services).forEach(key => {
                        const service = services[key];

                        const serviceHTML = `
                            <div class="col">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-primary">${service.service}</h5>
                                        <p class="card-text text-muted">${service.description}</p>
                                        <p><strong>Location:</strong> ${service.location}</p>
                                        <p><strong>Cost:</strong> RM ${parseFloat(service.cost).toFixed(2)}</p>
                                        <div class="mt-auto">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input service-checkbox" id="service-${key}" data-service="${service.service}" data-cost="${service.cost}">
                                                <label class="form-check-label" for="service-${key}">Select this service</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        servicesContainer.insertAdjacentHTML('beforeend', serviceHTML);
                    });

                    // Add event listeners to checkboxes
                    document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                        checkbox.addEventListener('change', updateTotalCost);
                    });

                    // Add functionality to "Add All Services" button
                    addAllButton.addEventListener('click', () => {
                        document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                            checkbox.checked = true;
                        });
                        updateTotalCost();
                    });

                    // Add functionality to "Clear All Services" button
                    clearAllButton.addEventListener('click', () => {
                        document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        updateTotalCost();
                    });

                    // Function to update the total cost
                    function updateTotalCost() {
                        const checkboxes = document.querySelectorAll('.service-checkbox:checked');
                        totalCost = Array.from(checkboxes).reduce((sum, checkbox) => {
                            return sum + parseFloat(checkbox.dataset.cost);
                        }, 0);

                        totalCostElement.textContent = `RM ${totalCost.toFixed(2)}`;
                    }
                });

            // Redirect to user_info.blade.php with selected services
            document.getElementById('submit-selection').addEventListener('click', () => {
                const selectedServices = [];
                document.querySelectorAll('.service-checkbox:checked').forEach(checkbox => {
                    selectedServices.push({
                        service: checkbox.dataset.service,
                        cost: checkbox.dataset.cost
                    });
                });

                sessionStorage.setItem('selectedServices', JSON.stringify(selectedServices));
            });
        });
    </script>

</body>
</html>
