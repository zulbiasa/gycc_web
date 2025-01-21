@extends('layouts.app')

@section('title', 'Register Care Plan')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">


<style>
    /* Basic styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    padding: 20px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.custom-form form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-top: 20px;
}

.row {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
}

.col {
    flex: 1;
    padding: 10px;
}

.label-column {
    flex: 1;
    max-width: 200px; /* Label column width */
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.input-column {
    flex: 2;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-size: 14px;
    margin-bottom: 5px;
}

input, select {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
}

input[type="file"] {
    padding: 3px;
}

select {
    height: 40px;
}

/* Responsive design */
@media (max-width: 768px) {
    .label-column {
        max-width: none;
    }

    .row {
        flex-direction: column;
    }

    .col {
        width: 100%;
    }
}

    /* Cards Container */
    .cards-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    /* Individual Card Style */
    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        padding: 20px;
        text-align: left;
    }

    .card h3 {
        font-size: 1rem;
        color: #333;
        background-color: #DCDFE1;
        line-height: 3;
        padding-left: 10px;
    }

    .card p {
        font-size: 1rem;
        line-height: 2;
    }
 /* Recent Activity Section */
 .recent-activity {
        background-color: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }

    .recent-activity h2 {
        font-size: 1.8rem;
        color: #333;
        margin-bottom: 20px;
    }

    .recent-activity ul {
        list-style: none;
        padding: 0;
    }

    .recent-activity li {
        background-color: #f9f9f9;
        margin: 12px 0;
        padding: 12px;
        border-radius: 6px;
        font-size: 1.1rem;
        transition: background-color 0.3s ease;
    }

    .recent-activity li:hover {
        background-color: #f1f1f1;
    }


    /* Caregiver Specific Section */
    .caregiver-specific {
        margin-top: 30px;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .caregiver-specific h3 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 10px;
    }

    .caregiver-specific p {
        font-size: 1.2rem;
        color: #666;
    }

     /* Add responsive styling for the chart container */
     .sales-graph {
        max-width: 80%;  /* Set maximum width for the graph container */
        margin: 0 auto;  /* Center the graph horizontally */
        padding: 20px;
    }

    .chart-container {
        position: relative;
        height: 400px;  /* Set a fixed height for the chart */
    }


    /* Responsive Design */
    @media (max-width: 768px) {
        .cards-container {
            flex-direction: column;
        }

        .card {
            width: 100%;
            margin-bottom: 20px;
        }

        .recent-activity {
            margin-top: 20px;
        }
    }

    .color-main {
        backgroundColor: white;
    }

    /* Custom styles for the form */

    .button-center {
    text-align: center;
}

    
</style>

  <!-- Admin Dashboard -->
@if ($role === 'Admin')
    
@endif


<!-- Caregiver Dashboard -->

@if ($role === 'Caregiver')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h3>Care Plan</h3>

        <div class="cards-container">
            <div class="card">
                <h4>Care Plan Registration Form</h4> <br>
                <form method="POST" action="{{ url('/register-care-plan') }}">
                <h3 onclick="toggleMenu('details-section')">Details Section</h3>
                <div id="details-section" class="child-links">
                    <div class="row">
                            @csrf
                            <div class="col-12">
                                <!-- Client Name -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">Client Name</p>
                                    <select class="form-select" id="client_name" name="client_name" style="width: 500px;" required>
                                        <option value="" disabled selected>Select Client Name</option>
                                        @foreach ($clients as $key => $user)
                                            <option value="{{ $key }}">{{ $user['name'] ?? 'Unknown' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Care Type Plan -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">Care Type Plan</p>
                                    <select class="form-select" id="care_type" name="care_type" style="width: 500px;" required>
                                        <option value="" disabled selected>Select Care Type Plan</option>
                                        <option value="Residential">Residential</option>
                                        <option value="Center">Center</option>
                                    </select>
                                </div>

                                <!-- Start Date -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">Start Date</p>
                                    <input type="date" class="form-control" name="start_date" id="start_date" placeholder="start_date" style="width: 500px" required>
                                </div>

                                <!-- End Date -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">End Date</p>
                                    <input type="date" class="form-control" name="end_date" id="end_date" placeholder="end_date" style="width: 500px" required readonly>
                                </div>

                                <!-- Caregiver Name -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">Caregiver Name</p>
                                    <input type="text" class="form-control" placeholder="{{ $name }}" style="width: 500px" readonly>

                                    <!-- Hidden input to hold the actual value of $userId -->
                                    <input type="hidden" name="caregiver_name" value="{{ $userId }}">

                                </div>                                
                            
                        </div>
                    </div>

                </div>

                <h3 onclick="toggleMenu('service-section')">Service & Cost Section</h3>
                <div id="service-section" class="child-links">
                    <div class="row">
                        @csrf
                        <div class="col-12">
                            <!-- Service Selection -->
                            <div class="mb-3">
                                <p class="mb-0 me-3" style="width: 150px;">Service :</p>
                                <!--<label for="service" class="form-label" style="width: 150px;">Service</label>-->
                                <select class="form-select frequency-select" id="service" name="service" style="width: 500px;" required>
                                    <option value="" disabled selected>Select Service</option>
                                    @foreach ($services as $key => $service)
                                        <option value="{{ $key }}"
                                                data-service="{{ $service['service'] ?? 'Unknown' }}" 
                                                data-description="{{ $service['description'] ?? 'No description available' }}" 
                                                data-cost="{{ $service['cost'] ?? 0 }}">
                                            {{ $service['service'] ?? 'Unknown'}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Table Section -->
                            <div class="mt-4">
                                <!-- Table to display selected services -->
                                <table class="table table-bordered" id="serviceTable" style="display: none; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Service</th>
                                            <th>Description</th>
                                            <th>Frequency</th>
                                            <th>Cost per Session</th>
                                            <th>Subtotal Cost</th>
                                            <th style="display: none;">Service ID</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>

                                <!-- Total Cost -->
                                <div class="mt-3" id="totalCost" style="display: none;">
                                    <strong>Total Cost: </strong> RM<span id="totalCostValue">0</span>
                                </div>

                                <div class="button-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                </form>

            </div>
        </div>
    </div>
@endif

@endsection


<script>
document.addEventListener('DOMContentLoaded', function () {
    const serviceSelect = document.getElementById('service');
    const serviceTable = document.getElementById('serviceTable');
    const tableBody = serviceTable.querySelector('tbody');
    const totalCostDiv = document.getElementById('totalCost');
    const totalCostValue = document.getElementById('totalCostValue');
    let totalCost = 0; // Total cost tracker
    let rowCount = 0;  // Row counter

    serviceSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];

        // Get selected service data
        const serviceName = selectedOption.getAttribute('data-service');
        const serviceDescription = selectedOption.getAttribute('data-description');
        const costPerSession = parseFloat(selectedOption.getAttribute('data-cost'));

        if (serviceName && serviceDescription && costPerSession) {
            // Check for duplicate entries
            const existingRows = Array.from(tableBody.querySelectorAll('tr'));
            const isDuplicate = existingRows.some(row =>
                row.querySelector('td:nth-child(2)').textContent === serviceName
            );

            if (isDuplicate) {
                alert('This service is already added to the table!');
                return;
            }

            // Increment row count
            rowCount++;

            // Add a new row to the table
            const row = document.createElement('tr');
            const frequencyDropdown = `
                <select class="form-select frequency-select" required>
                    <option value="" disabled selected>Select Frequency</option>
                    <option value="365">Daily</option>
                    <option value="52">Weekly</option>
                    <option value="12">Monthly</option>
                </select>
            `;

            row.innerHTML = `
                <td>${rowCount}</td>
                <td>${serviceName}</td>
                <td>${serviceDescription}</td>
                <td>${frequencyDropdown}</td>
                <td>RM${costPerSession.toFixed(2)}</td>
                <td class="subtotal-cost">RM0.00</td>
                <td class="service-id" style="display: none;">${selectedOption.value}</td>
                <td><button class="btn btn-danger btn-sm remove-btn">X</button></td>
            `;

            // Add event listener for frequency change
            const frequencySelect = row.querySelector('.frequency-select');
            const subtotalCostCell = row.querySelector('.subtotal-cost');
            frequencySelect.addEventListener('change', function () {
                const frequencyValue = parseInt(this.value, 10);
                const subtotalCost = costPerSession * frequencyValue;
                subtotalCostCell.textContent = `RM${subtotalCost.toFixed(2)}`;
                updateTotalCost();
            });

            // Add event listener for the remove button
            row.querySelector('.remove-btn').addEventListener('click', function () {
                row.remove();
                updateRowNumbers();
                updateTotalCost();

                // Hide table and total cost if no rows remain
                if (tableBody.querySelectorAll('tr').length === 0) {
                    serviceTable.style.display = 'none';
                    totalCostDiv.style.display = 'none';
                }
            });

            tableBody.appendChild(row);

            // Update row numbers and show the table
            updateRowNumbers();
            serviceTable.style.display = 'table';
            totalCostDiv.style.display = 'block';
        }
    });

    // Function to update total cost
    function updateTotalCost() {
        totalCost = Array.from(tableBody.querySelectorAll('.subtotal-cost')).reduce((sum, cell) => {
            return sum + parseFloat(cell.textContent.replace('RM', '')) || 0;
        }, 0);
        totalCostValue.textContent = totalCost.toFixed(2);
    }

    // Function to update row numbers
    function updateRowNumbers() {
        Array.from(tableBody.querySelectorAll('tr')).forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
        });
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const tableBody = document.querySelector('#serviceTable tbody');  // Define the tableBody variable
    
    if (form && tableBody) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const caregiverId = document.querySelector('input[name="caregiver_name"]').value;
            const clientId = document.querySelector('select[name="client_name"]').value;
            const careType = document.querySelector('select[name="care_type"]').value;
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;

            const servicesData = [];
            tableBody.querySelectorAll('tr').forEach((row, index) => {
                const serviceId = row.querySelector('.service-id').textContent;
                const frequency = row.querySelector('.frequency-select').value;
                servicesData.push({
                    serviceId: serviceId,
                    frequency: frequency
                });
            });

            const totalCost = parseFloat(totalCostValue.textContent);

            // Send the data to the controller
            fetch('/register-care-plan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    caregiver_id: caregiverId,
                    client_id: clientId,
                    care_type: careType,
                    start_date: startDate,
                    end_date: endDate,
                    services: servicesData,
                    total_cost: totalCost
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);  // Show the success message
                if (data.reload) {
                    location.reload();  // Reload the page
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    } else {
        console.error('Form or table body not found');
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    startDateInput.addEventListener('change', function () {
        const startDate = new Date(this.value); // Get the selected start date
        if (!isNaN(startDate)) { // Ensure the date is valid
            const endDate = new Date(startDate); // Clone the start date
            endDate.setFullYear(endDate.getFullYear() + 1); // Add one year
            endDate.setDate(endDate.getDate() + 1); // Add one day
            endDateInput.value = endDate.toISOString().split('T')[0]; // Set the end date
        }
    });
});



</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>





