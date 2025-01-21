@extends('layouts.app')

@section('title', 'Care Log')

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


<!-- Caregiver CareLog -->

@if ($role === 'Caregiver')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h3>Care Log</h3>

        <div class="cards-container">
            <div class="card">
                <h4>List of Care Logs</h4> <br>
                <hr style="border: 1px solid #333; margin: 0px 0;">

                <div class="d-flex align-items-center mb-3">
                @if (!empty($users)) <!-- Check if users are not empty -->
                <table class="table table-striped">
                    <thead>
                    </thead>
                    <tbody>
                        @foreach ($users as $userId => $userData)
                            @if (isset($userData['reminders']) && !empty($userData['reminders']))
                                {{-- Sort the reminders by actionDate in descending order --}}
                                @php
                                    // Sort the reminders in descending order based on actionDate
                                    $sortedReminders = collect($userData['reminders'])->sortByDesc(function ($reminder) {
                                        return strtotime($reminder['date']);
                                    });
                                @endphp

                                <!-- Grouping reminders by user and displaying the user's name -->
                                <tr class="accordion-header" id="user-{{ $userId }}">
                                    <td colspan="7" class="accordion-toggle" data-bs-toggle="collapse" data-bs-target="#reminderDetails-{{ $userId }}">
                                        <strong>{{ $userData['userDetails']['name'] ?? 'N/A' }}</strong>
                                    </td>
                                </tr>

                                <!-- Reminder Details Dropdown (visible on clicking name) -->
                                <tr id="reminderDetails-{{ $userId }}" class="accordion-collapse collapse">
                                    <td colspan="7">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Assigned Date</th>
                                                    <th>Assigned Time</th>
                                                    <th>Action Date</th>
                                                    <th>Action Time</th>
                                                    <th>Medicine Name</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sortedReminders as $reminderId => $reminder)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($reminder['date'])->format('d-M-Y') ?? 'N/A' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($reminder['time'])->format('H:i') ?? 'N/A' }}</td>
                                                        <td>{{ isset($reminder['actionDate']) ? \Carbon\Carbon::parse($reminder['actionDate'])->format('d-M-Y') : 'N/A' }}</td>
                                                        <td>{{ isset($reminder['actionTime']) ? \Carbon\Carbon::parse($reminder['actionTime'])->format('H:i') : 'N/A' }}</td>
                                                        <td>{{ $reminder['medicineName'] ?? 'N/A' }}</td>
                                                        <td style="color: {{ 
                                                            $reminder['status'] === 'missed' ? 'red' : 
                                                            ($reminder['status'] === 'taken' ? 'green' : 
                                                            ($reminder['status'] === 'pending' ? 'orange' : 'black')) 
                                                        }};">
                                                            {{ $reminder['status'] ?? 'N/A' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @else
                                <!-- If no reminders, show a row indicating this -->
                                <tr>
                                    <td colspan="7" class="text-center">No reminders available for this user.</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

@else
    <p>No users found.</p>
@endif



                    

            </div>
        </div>
    </div>
@endif

@endsection


<script>

</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>