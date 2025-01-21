@extends('layouts.app')

@section('title', 'Register')

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
    <div class="container mt-5">
        <!-- Overview Cards Section -->
        <div class="cards-container">
            <div class="card">
                <h3>Total Users</h3>
                <p>{{ $totalUsersWithRole2 }}</p> <!-- Displaying total users with role 2 -->
            </div>
            <div class="card">
                <h3>Active Services</h3>
                <p>30 cobaan</p>
            </div>
            <div class="card">
                <h3>Pending Tasks</h3>
                <p>12 cobaan</p>
            </div>
        </div>

             <!-- Sales Graph Section -->
        <div class="sales-graph">
            <h3>Sales & Care Plan Revenue</h3>
            <div class="chart-container">
                <canvas id="salesGraph"></canvas>
            </div>
        </div>
       

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // Sample data, replace it with the actual data from your backend
            var salesData = {
                dates: ['2024-12-01', '2024-12-02', '2024-12-03', '2024-12-04'],  // Sample dates
                revenue: [120, 250, 180, 320]  // Sample revenue for each date
            };

            var ctx = document.getElementById('salesGraph').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'line',  // Line chart (can be changed to 'bar' for bar chart)
                data: {
                    labels: salesData.dates,  // X-axis (dates)
                    datasets: [{
                        label: 'Revenue',  // Label for the line
                        data: salesData.revenue,  // Y-axis (revenue)
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',  // Fill color under the line
                        borderColor: 'rgba(75, 192, 192, 1)',  // Line color
                        borderWidth: 2,  // Line width
                        tension: 0.4,  // Smooth line (0.4 is a good value for smoothness)
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',  // Points color
                        pointRadius: 5  // Points radius
                    }]
                },
                options: {
                    responsive: true,  // Make the chart responsive
                    maintainAspectRatio: false,  // Allow chart resizing
                    scales: {
                        y: {
                            beginAtZero: true,  // Start Y-axis from 0
                            ticks: {
                                callback: function(value) { return '$' + value; }  // Format Y-axis ticks as currency
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,  // Show the legend
                            position: 'top',  // Position of the legend
                            labels: {
                                font: {
                                    size: 14  // Font size for the legend labels
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return '$' + tooltipItem.raw;  // Format tooltip as currency
                                }
                            }
                        }
                    }
                }
            });
        </script>
        <!-- Recent Activity Section -->
        <div class="recent-activity">
            <h2>Recent Activities</h2>
            <ul>
                <li>Task 1 - Completed</li>
                <li>Task 2 - Pending</li>
                <li>New user registered</li>
                <li>Service updated</li>
            </ul>
        </div>
    </div>
@endif


<!-- Caregiver Dashboard -->
@if ($role === 'Caregiver')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h3>Client management/ register</h3>

        <div class="cards-container">
            <div class="card">
                <h4>Client Registration Form</h4> <br>
                <h3 onclick="toggleMenu('client-profile')">Client's Profile</h3>
                <div id="client-profile" class="child-links">
                    <div class="row">
                        <form method="POST" action="{{ route('register.store') }}">
                            @csrf
                            <div class="col-12">
                                <!-- Full Name -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">Full Name</p>
                                    <input type="text" class="form-control" name="full_name" placeholder="Full Name">
                                </div>

                                <!-- IC Number -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">IC Number</p>
                                    <input type="number" class="form-control" name="ic_number" placeholder="IC Number">
                                </div>

                                <!-- Phone Number -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">Phone Number</p>
                                    <input type="number" class="form-control" name="phone_number" placeholder="Phone Number">
                                </div>

                                <!-- Date of Birth -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">Date of Birth</p>
                                    <input type="date" class="form-control" name="dob" placeholder="Date of Birth">
                                </div>

                                <!-- Home Address -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">Home Address</p>
                                    <input type="text" class="form-control" name="home_address" placeholder="Home Address">
                                </div>

                                <!-- Status -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">Status</p>
                                    <select class="form-select" name="status">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>

                                <!-- Gender -->
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0 me-3" style="width: 150px;">Gender</p>
                                    <select class="form-select" name="gender">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="female">Female</option>
                                        <option value="male">Male</option>
                                    </select>
                                </div>

                                <!-- Profile Picture -->
                                <!-- Add profile picture logic if needed -->

                                <div class="button-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div><br>
                            </form>
                        </div>
                    </div>

                </div>

                <h3 onclick="toggleMenu('authentication')">App Authentication</h3>
                <div id="authentication" class="child-links">
                    <div class="row">
                        @csrf
                        <div class="col-12">
                            <!-- Username -->
                            <div class="d-flex align-items-center mb-3">
                                <p class="mb-0 me-3" style="width: 150px;">Username</p>
                                <input type="text" class="form-control" name="username" placeholder="Username" style="width: 500px">
                            </div>
                            <!-- Password -->
                            <div class="d-flex align-items-center mb-3">
                                <p class="mb-0 me-3" style="width: 150px;">Password</p>
                                <input type="password" class="form-control" name="password" placeholder="Password" style="width: 500px">
                            </div>
                            <!-- Re-confirm Password -->
                            <div class="d-flex align-items-center mb-3">
                                <p class="mb-0 me-3" style="width: 150px;">Re-confirm Password</p>
                                <input type="password" class="form-control" name="confirm_password" placeholder="Re-confirm Password" style="width: 500px">
                            </div>
                        </div>
                    </div>
                </div>

                <h3 onclick="toggleMenu('medical')">Medical & Health Information</h3>
                <div id="medical" class="child-links">
                    <div class="row">
                        @csrf
                        <div class="col-12">
                            <!-- Blood type -->
                            <div class="d-flex align-items-center mb-3">
                                <p class="mb-0 me-3" style="width: 150px;">Blood Type</p>
                                <input type="text" class="form-control" name="blood_type" placeholder="Blood Type" style="width: 500px">
                            </div>
                            <!-- Allergic -->
                            <div class="d-flex align-items-center mb-3">
                                <p class="mb-0 me-3" style="width: 150px;">Allergic</p>
                                <select class="form-select" id="allergic" name="allergic" style="width: 500px;">
                                    <option value="" disabled selected>Select Yes or No</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                            <!-- Food -->
                            <div class="d-flex align-items-center mb-3">
                                <p class="mb-0 me-3" style="width: 150px;">Food</p>
                                <select class="form-select" id="food" name="food" style="width: 500px;" disabled>
                                    <option value="" disabled selected>Select Food Type</option>
                                    <option value="seafood">Seafood</option>
                                    <option value="dairy">Dairy Product</option>
                                </select>
                            </div>
                            <!-- Medicine -->
                            <div class="d-flex align-items-center mb-3">
                                <p class="mb-0 me-3" style="width: 150px;">Medicine</p>
                                <select class="form-select" id="medicine" name="medicine" style="width: 500px;" disabled>
                                    <option value="" disabled selected>Select Medicine Type</option>
                                    <option value="penicilin">Penicilin</option>
                                    <option value="aspirin">Aspirin</option>
                                    <option value="nsaids">Nonsteroidal Anti-inflammatory Drugs (NSAIDs)</option>
                                    <option value="not_related">Not Related</option>
                                </select>
                            </div>
                            <!-- Health Condition -->
                            <div class="d-flex align-items-center mb-3">
                                <p class="mb-0 me-3" style="width: 150px;">Health Condition</p>
                                <select class="form-select" id="health" name="health" style="width: 500px;">
                                    <option value="" disabled selected>Select Health Condition</option>
                                    <option value="heart_diseases">Heart Diseases</option>
                                    <option value="respiratory">Respiratory Diseases</option>
                                    <option value="cancer">Cancer</option>
                                    <option value="not_related">Not Related</option>
                                </select>
                                <button id="addCondition" class="btn btn-success ms-3">+</button>
                            </div>
                            <!-- List of Added Conditions -->
                            <div id="conditionList" class="mt-3" style="margin-left: 150px;">
                                <!-- List items will be added here -->
                            </div>

                            <!-- Divider -->
                            <hr style="border: 1px solid #333; margin: 20px 0;">

                            <!-- List of Medications -->
                            <div class="d-flex flex-column mb-3">
                                <!-- Title -->
                                <p class="mb-0 me-3" style="width: 150px;">List of Medications</p>

                                <!-- Medication Dropdown -->
                                <div class="d-flex align-items-center mt-2">
                                    <p class="mb-0 me-3" style="width: 150px;">Medication</p>
                                    <select class="form-select" id="health" name="health" style="width: 675px;">
                                        <option value="" disabled selected>Select Health Condition</option>
                                        <option value="heart_diseases">Heart Diseases</option>
                                        <option value="respiratory">Respiratory Diseases</option>
                                        <option value="cancer">Cancer</option>
                                        <option value="not_related">Not Related</option>
                                    </select>
                                    <button id="addCondition" class="btn btn-success ms-3">+</button>
                                </div>
                            </div>

                            <!-- Dosage and Intake Frequency -->
                            <div class="d-flex align-items-center mb-3">
                                <!-- Dosage -->
                                <div class="d-flex align-items-center me-4" style="width: 400px;">
                                    <p class="mb-0 me-3" style="width: 155px;">Dosage</p>
                                    <input type="text" class="form-control" name="dosage" placeholder="Dosage" style="width: 250px;">
                                </div>

                                <!-- Intake Frequency -->
                                <div class="d-flex align-items-center" style="width: 500px;">
                                    <p class="mb-0 me-3" style="width: 150px;">Frequency Intake</p>
                                    <input type="text" class="form-control" name="frequency" placeholder="Intake Frequency" style="width: 250px;">
                                </div>
                            </div>
                            <!-- Purpose -->
                            <div class="d-flex align-items-center mb-3">
                                <p class="mb-0 me-3" style="width: 150px;">Purpose</p>
                                <input type="text" class="form-control" name="purpose" placeholder="Purpose" style="width: 675px">
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const allergicDropdown = document.getElementById('allergic');
        const foodDropdown = document.getElementById('food');
        const medicineDropdown = document.getElementById('medicine');

        allergicDropdown.addEventListener('change', function () {
            if (this.value === 'yes') {
                foodDropdown.disabled = false; 
                medicineDropdown.disabled = false; // Enable food dropdown
            } else {
                foodDropdown.disabled = true;  // Disable food dropdown
                foodDropdown.value = '';       // Reset food dropdown value
                medicineDropdown.disabled = true;  
                medicineDropdown.value = ''; 
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const healthDropdown = document.getElementById('health');
        const addButton = document.getElementById('addCondition');
        const conditionList = document.getElementById('conditionList');

        addButton.addEventListener('click', function () {
            const selectedOption = healthDropdown.options[healthDropdown.selectedIndex];
            
            // Check if a valid option is selected
            if (healthDropdown.value !== "") {
                // Create a list item
                const listItem = document.createElement('p');
                listItem.textContent = `${conditionList.children.length + 1}. ${selectedOption.text}`;
                conditionList.appendChild(listItem);
            } else {
                alert('Please select a health condition first.');
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>





