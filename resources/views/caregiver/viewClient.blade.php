@extends('layouts.app')

@section('title', 'View Client')

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
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if ($role === 'Caregiver')

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h3>Client management</h3>
        <div class="cards-container">
            <div class="card">
                <h4 style>List of Clients</h4>
                <!-- Divider -->
                <!--<hr style="border: 1px solid #333; margin: 15px 0;">
                <form method="GET" action="{{ route('searchClients') }}">
                    <div class="d-flex align-items-center mb-3">
                        <input type="text" class="form-control" name="search" placeholder="Search by name" style="width: 500px">
                        <p class="mb-0 me-3" style="width: 100px; padding: 15px;">Status:</p>
                        <select class="form-select" id="status" name="status" style="width: 200px;">
                            <option value="" disabled selected>Select status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="margin: 15px;">Search</button>
                    </div>
                </form>-->
                <!-- Divider -->
                <hr style="border: 1px solid #333; margin: 0px 0;">
                
                <div class="d-flex align-items-center mb-3">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid #ddd; text-align: left;">
                                <th style="padding: 10px; width: 35%;">Name</th>
                                <th style="padding: 10px; width: 15%;">IC Number</th>
                                <th style="padding: 10px; width: 20%;">Phone Number</th>
                                <th style="padding: 10px; width: 10%;">Status</th>
                                <th style="padding: 10px; width: 10%;">Action</th>
                                <th style="padding: 10px; width: 10%;">Care Plan</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if (!empty($clients))
                            @foreach ($clients as $id => $client)
                                @php
                                    // Initialize a variable to check if care plans exist for the current client
                                    $hasCarePlan = false;
                                @endphp

                                <tr id="row-{{ $id }}">
                                    <td style="padding: 10px;">
                                        <span class="view-mode">{{ $client['name'] ?? 'N/A' }}</span>
                                        <input type="text" class="edit-mode form-control" name="name" value="{{ $client['name'] }}" style="display: none;">
                                    </td>
                                    <td style="padding: 10px;">
                                        <span class="view-mode">{{ $client['ic_no'] ?? 'N/A' }}</span>
                                        <input type="text" class="edit-mode form-control" name="ic_no" value="{{ $client['ic_no'] }}" style="display: none;">
                                    </td>
                                    <td style="padding: 10px;">
                                        <span class="view-mode">{{ $client['phone_no'] ?? 'N/A' }}</span>
                                        <input type="text" class="edit-mode form-control" name="phone_no" value="{{ $client['phone_no'] }}" style="display: none;">
                                    </td>
                                    <td style="padding: 10px;">
                                        <span class="view-mode">
                                            {{ $client['status'] === true ? 'Active' : ($client['status'] === false ? 'Inactive' : 'N/A') }}
                                        </span>
                                        <select class="edit-mode form-select" name="status" style="display: none;">
                                            <option value="1" {{ $client['status'] === true ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $client['status'] === false ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </td>
                                    <td style="padding: 10px;">
                                        <button class="btn btn-primary btn-sm edit-btn" data-id="{{ $id }}">Edit</button>
                                        <button class="btn btn-success btn-sm save-btn" data-id="{{ $id }}" style="display: none;">Save</button>
                                        <button class="btn btn-secondary btn-sm cancel-btn" data-id="{{ $id }}" style="display: none;">Cancel</button>
                                    </td>

                                    <td style="padding: 10px;">
                                        @php
                                            $clientCarePlans = [];
                                            // Iterate over the care plans and check if the clientId matches
                                            foreach ($carePlans as $plan) {
                                                if ($plan['clientId'] == $id) {
                                                    $clientCarePlans[] = $plan;
                                                }
                                            }
                                        @endphp

                                        @if (empty($clientCarePlans))
                                            <!-- If no care plan exists for this client, disable the button -->
                                            <button class="btn btn-primary btn-sm" disabled>Show</button>
                                        @else
                                            @foreach ($clientCarePlans as $plan)
                                                <button class="btn btn-primary btn-sm care-plan-link"
                                                    data-name="{{ $plan['name'] }}" 
                                                    data-type="{{ $plan['care_type'] }}" 
                                                    data-start="{{ $plan['start_date'] }}" 
                                                    data-end="{{ $plan['end_date'] }}" 
                                                    data-cost="{{ $plan['cost'] }}" 
                                                    data-status="{{ \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($plan['end_date'])) ? 'Active' : 'Expired' }}"
                                                    data-services="{{ json_encode($plan['services']) }}"
                                                >Show</button>
                                            @endforeach
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 10px;">No data available</td>
                            </tr>
                        @endif



                    </tbody>
                    </table>
                </div>

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
@endif

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const rowId = this.dataset.id;
            toggleEditMode(rowId, true);
        });
    });

    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.addEventListener('click', function () {
            const rowId = this.dataset.id;
            toggleEditMode(rowId, false);
        });
    });

    function toggleEditMode(rowId, isEditMode) {
        const row = document.getElementById(`row-${rowId}`);
        row.querySelectorAll('.view-mode').forEach(el => el.style.display = isEditMode ? 'none' : '');
        row.querySelectorAll('.edit-mode').forEach(el => el.style.display = isEditMode ? '' : 'none');
        row.querySelector('.edit-btn').style.display = isEditMode ? 'none' : '';
        row.querySelector('.save-btn').style.display = isEditMode ? '' : 'none';
        row.querySelector('.cancel-btn').style.display = isEditMode ? '' : 'none';
    }

    document.body.addEventListener('click', function (event) {
    if (event.target.classList.contains('save-btn')) {
        console.log("Delegated listener: Save button clicked!");
        const rowId = event.target.dataset.id;
        console.log(`Row ID: ${rowId}`);
        saveChanges(rowId);
    }
    });
    
    function saveChanges(rowId) {
    // Locate the row by ID
    
    const row = document.getElementById(`row-${rowId}`);
    if (!row) {
        console.error(`Row with ID row-${rowId} not found.`);
        return;
    }

    // Extract input values
    const name = row.querySelector('input[name="name"]').value;
    const ic_no = row.querySelector('input[name="ic_no"]').value;
    const phone_no = row.querySelector('input[name="phone_no"]').value;
    const status = row.querySelector('select[name="status"]').value === "1";

    // Debugging: Ensure all values are being retrieved
    console.log(`Saving changes for ${rowId}:`, { name, ic_no, phone_no, status });

    // API call to save changes
    fetch(`/clients/${rowId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ name, ic_no, phone_no, status })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Save successful:', data);

            // Optional: Update UI based on success
            alert('Changes saved successfully.');
            location.reload();
        })
        .catch(error => {
            console.error('Error saving changes:', error);
            alert(`Failed to save changes: ${error.message || 'Unknown error'}`);
        });
    }



});

document.addEventListener('DOMContentLoaded', function () {
    // Event listener for care plan buttons
    const carePlanButtons = document.querySelectorAll('.care-plan-link');
    carePlanButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            // Get the data from the clicked button
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

            if (modalStatus.textContent === 'Expired') {
                modalStatus.style.color = 'red';
            } else {
                modalStatus.style.color = '';  // Reset the color if it's not 'Expired'
            }
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
