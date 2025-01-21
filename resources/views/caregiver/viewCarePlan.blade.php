@extends('layouts.app')

@section('title', 'List of Care Plan')

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

    .custom-link {
        color: black; /* Default color */
        text-decoration: none; /* No underline */
        transition: color 0.3s; /* Smooth color change */
    }

    .custom-link:hover {
        color: blue; /* Hover color */
    }

    .cost-column {
    width: 100px; /* Adjust width as necessary */
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
        <h3>Care Plan/ view</h3>

        <div class="cards-container">
            <div class="card">
                <h4>List of Care Plans</h4> <br>
                <hr style="border: 1px solid #333; margin: 0px 0;">

                <div class="d-flex align-items-center mb-3">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <th style="padding: 10px; width: 40%; text-align: left;">Client Name</th>
                                <th style="padding: 10px; width: 15%; text-align: left;">Care Type Plan</th>
                                <th style="padding: 10px; width: 10%; text-align: left;">Start Date</th>
                                <th style="padding: 10px; width: 10%; text-align: left;">End Date</th>
                                <th style="padding: 10px; width: 10%; text-align: left;">Total Cost</th>
                                <th style="padding: 10px; width: 15%; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($carePlans as $plan)
                                <tr style="border-bottom: 1px solid #ddd;">
                                    <td style="padding: 10px;">
                                        <a href="#" class="care-plan-link custom-link" 
                                            data-name="{{ $plan['name'] }}" 
                                            data-type="{{ $plan['care_type'] }}" 
                                            data-start="{{ $plan['start_date'] }}" 
                                            data-end="{{ $plan['end_date'] }}" 
                                            data-cost="{{ $plan['cost'] }}" 
                                            data-status="{{ \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($plan['end_date'])) ? 'Active' : 'Expired' }}"
                                            data-services="{{ json_encode($plan['services']) }}">
                                            {{ $plan['name'] }}
                                        </a>
                                    </td>
                                    <td style="padding: 10px;">{{ $plan['care_type'] }}</td>
                                    <td style="padding: 10px;">{{ \Carbon\Carbon::parse($plan['start_date'])->format('d-M-Y') }}</td>
                                    <td style="padding: 10px;">{{ \Carbon\Carbon::parse($plan['end_date'])->format('d-M-Y') }}</td>
                                    <td style="padding: 10px;">RM{{ $plan['cost'] }}</td>
                                    <td style="padding: 10px;">
                                        @if (\Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($plan['end_date'])))
                                            Active
                                        @else
                                            Expired
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding: 10px; text-align: center;">No care plans available for this caregiver.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Modal -->
                    <div class="modal fade" id="carePlanModal" tabindex="-1" aria-labelledby="carePlanModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="carePlanModalLabel">Care Plan Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 8px; font-weight: bold;">Name:</td>
                                            <td style="padding: 8px;"><span id="modalPlanName"></span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px; font-weight: bold;">Type:</td>
                                            <td style="padding: 8px;"><span id="modalPlanType"></span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px; font-weight: bold;">Start Date:</td>
                                            <td style="padding: 8px;"><span id="modalStartDate"></span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px; font-weight: bold;">End Date:</td>
                                            <td style="padding: 8px;"><span id="modalEndDate"></span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px; font-weight: bold;">Total Cost:</td>
                                            <td style="padding: 8px;">RM<span id="modalCost"></span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px; font-weight: bold;">Status:</td>
                                            <td style="padding: 8px;"><span id="modalStatus"></span></td>
                                        </tr>
                                    </table>

                                    <!-- Services Details Table -->
                                    <h5 style="margin-top: 20px; font-weight: bold;">Services:</h5>
                                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                                        <thead>
                                            <tr>
                                                <th style="padding: 8px; font-weight: bold;">Service</th>
                                                <th style="padding: 8px; font-weight: bold;">Description</th>
                                                <th class="cost-column" style="padding: 8px; font-weight: bold;">Sub Cost</th>
                                                <th style="padding: 8px; font-weight: bold;">Frequency</th>
                                            </tr>
                                        </thead>
                                        <tbody id="servicesTableBody">
                                            <!-- Dynamic service rows will be inserted here -->
                                        </tbody>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
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
    // Event listener for care plan links
    const carePlanLinks = document.querySelectorAll('.care-plan-link');
    carePlanLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Get the data from the clicked link
            const planName = this.getAttribute('data-name');
            const planType = this.getAttribute('data-type');
            const startDate = this.getAttribute('data-start');
            const endDate = this.getAttribute('data-end');
            const cost = this.getAttribute('data-cost');
            const status = this.getAttribute('data-status');
            const services = JSON.parse(this.getAttribute('data-services')); // Assuming the services are passed as JSON

            // Fill in the plan details
            document.getElementById('modalPlanName').textContent = planName;
            document.getElementById('modalPlanType').textContent = planType;
            document.getElementById('modalStartDate').textContent = startDate;
            document.getElementById('modalEndDate').textContent = endDate;
            document.getElementById('modalCost').textContent = cost;
            document.getElementById('modalStatus').textContent = status;

            // Clear previous service rows
            const servicesTableBody = document.getElementById('servicesTableBody');
            servicesTableBody.innerHTML = '';

            // Add each service to the table
            services.forEach(service => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="padding: 8px;">${service.service}</td>
                    <td style="padding: 8px;">${service.description}</td>
                    <td style="padding: 8px;">RM ${service.cost}</td>
                    <td style="padding: 8px;">${service.frequency}</td>
                `;
                servicesTableBody.appendChild(row);
            });

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('carePlanModal'));
            modal.show();
        });
    });
});



</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>





