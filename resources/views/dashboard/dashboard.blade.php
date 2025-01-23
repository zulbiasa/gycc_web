@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .cards-container {
        display: flex;
        justify-content: space-between;
        width: auto;
        margin-bottom: 30px;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    @media (max-width: 768px) {
        .cards-container {
            grid-template-columns: 1fr;
            width: 100%;
        }

        .card {
            height: auto;
        }
    }

    /* Individual Card Style */
    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        padding: 20px;
        text-align: center;
    }

    .card h3 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 10px;
    }

    .card p {
        font-size: 2rem;
        font-weight: bold;
        color: #4CAF50;
    }

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

    .popup-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        width: 80%;
        max-width: 600px;
    }

    .popup-content h3 {
        text-align: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .popup-table {
        width: 100%;
        border-collapse: collapse;
    }

    .popup-table th,
    .popup-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .popup-close-btn {
        display: block;
        margin: 20px auto;
        background-color: red;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    th {
        background-color: #f4f4f4;
        color: #555;
        font-weight: bold;
        text-transform: uppercase;
        border-bottom: 2px solid #ddd;
    }

    td {
        background-color: #fafafa;
        border-bottom: 1px solid #ddd;
    }

    tr:nth-child(even) td {
        background-color: #f9f9f9;
    }

    tr:hover td {
        background-color: #f1f1f1;
    }

    /* Color Based on Status */
    .status-complete {
        color: green;
        font-weight: bold;
    }

    .status-incomplete {
        color: red;
        font-weight: bold;
    }

     /* Add scroll bar to the table body */
     tbody {
        display: block;
        max-height: 300px; /* Adjust the height as needed */
        overflow-y: auto;
        width: 100%;
    }

    thead, tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
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

    .popup-table tbody {
    max-height: 300px; /* Adjust as needed */
    overflow-y: auto;
    display: block;
}
.popup-table thead, .popup-table tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}

</style>
@php
    use Carbon\Carbon;
@endphp


@if ($role === 'Admin')
<div class="container mt-5">
    <div class="row">
    <div class="recent-activity" style="background-color: #003366;">
        <h1 style=" font-family: 'Roboto', sans-serif; text-align: center; color:rgb(255, 255, 255);">Welcome to Admin Dashboard</h1>
    </div>

    <div class="recent-activity">
        <div style="text-align: center; margin: 10px;">

            <button onclick="window.location.href='{{ route('users.create') }}'" 
                    style="background-color:rgb(0, 8, 228); color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Register User
            </button>
            <button onclick="scrollToUsers()" 
                style="background-color:rgb(165, 0, 173); color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    Interactive User Statistics
            </button>
            <button onclick="scrollToActivities()" 
                style="background-color: #FF9800; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                View Recent Activities
            </button>
        </div>

        <div style="margin-top: 15px; text-align: center; font-size: 1rem;">
            <strong>Admin:</strong> {{ $totalUsersWithRole1 }}  | 
            <strong>Active Caregiver:</strong> {{ $totalUsersWithRole2 }} | 
            <strong>Active Client:</strong> {{ $totalUsersWithRole3}}
        </div>
    </div>

    <!-- RECENT ACTIVITIES -->
    <div class="recent-activity">
    <div class="recent-activities">
        <h2 style="font-family: 'Roboto', sans-serif; text-align: center; color: #4CAF50;">Recent Activities</h2>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('recentActivities.filter') }}" style="margin-bottom: 20px; text-align: center;">
            <label for="filter_month" style="margin-right: 10px;">Month:</label>
            <select name="filter_month" id="filter_month" style="margin-right: 20px;">
                <option value="">All Months</option>
                @foreach (range(1, 12) as $month)
                    <option value="{{ $month }}" {{ request('filter_month') == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                    </option>
                @endforeach
            </select>

            <label for="filter_year" style="margin-right: 10px;">Year:</label>
            <select name="filter_year" id="filter_year" style="margin-right: 20px;">
                <option value="">All Years</option>
                @foreach (range(date('Y') - 10, date('Y')) as $year)
                    <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>

            <button type="submit" style="background-color: #4CAF50; color: white; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Filter
            </button>
        </form>

        <!-- Table -->
        <div class="table-container" style="overflow-x: auto; border-radius: 10px; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); margin-top: 20px;">
            <table style="width: 100%; border-collapse: collapse; font-family: 'Roboto', sans-serif; background: white;">
                <thead>
                    <tr style="background: #f9f9f9; text-align: left;">
                        <!-- Reduced width for the # column -->
                        <th style="padding: 10px; width: 5%; text-align: center;">#</th>
                        <!-- Adjusted width for Type column -->
                        <th style="padding: 10px; width: 15%;">Type</th>
                        <!-- Increased width for Activity column -->
                        <th style="padding: 10px; width: 50%;">Activity</th>
                        <!-- Adjusted widths for Date, Time, and Status -->
                        <th style="padding: 10px; width: 15%;">Date</th>
                        <th style="padding: 10px; width: 15%;">Time</th>
                        <th style="padding: 10px; width: 10%; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentActivities as $index => $activity)
                        <tr style="background: {{ $index % 2 === 0 ? '#fcfcfc' : '#fff' }};">
                            <!-- Adjusted # column width -->
                            <td style="text-align: center; padding: 10px; width: 5%;">{{ $loop->iteration }}</td>
                            <!-- Type column -->
                            <td style="padding: 10px; width: 15%;">{{ $activity['type'] }}</td>
                            <!-- Adjusted Activity column -->
                            <td style="padding: 10px; width: 50%;">{!! $activity['activity'] !!}</td>
                            <!-- Date column -->
                            <td style="padding: 10px; width: 15%;">{{ Carbon::parse($activity['date'])->format('j F Y') }}</td>
                            <!-- Time column -->
                            <td style="padding: 10px; width: 10%;">{{ Carbon::parse($activity['date'])->format('h:i A') }}</td>
                            <!-- Status column -->
                            <td style="text-align: center; padding: 10px; width: 10%;">
                                <span style="padding: 5px 10px; border-radius: 5px; background: {{ $activity['status'] === 'complete' ? '#4CAF50' : '#FF5722' }}; color: white;">
                                    {{ ucfirst($activity['status']) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center;">No activities found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

                            <!-- Custom Pagination -->
                @if ($recentActivities->hasPages())
                    <div style="margin-top: 20px; text-align: center; font-family: 'Roboto', sans-serif;">
                        <ul style="display: inline-flex; list-style: none; padding: 0;">
                            {{-- Previous Page Link --}}
                            @if ($recentActivities->onFirstPage())
                                <li style="margin: 0 5px; padding: 8px 15px; border: 1px solid #e0e0e0; color: #aaa; cursor: not-allowed; background: #f9f9f9; border-radius: 5px;">
                                    Previous
                                </li>
                            @else
                                <a href="{{ $recentActivities->previousPageUrl() }}" style="text-decoration: none;">
                                    <li style="margin: 0 5px; padding: 8px 15px; border: 1px solid #4CAF50; color: #4CAF50; cursor: pointer; background: white; border-radius: 5px;">
                                        Previous
                                    </li>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($recentActivities->links()->elements as $element)
                                {{-- "Three Dots" Separator --}}
                                @if (is_string($element))
                                    <li style="margin: 0 5px; padding: 8px 15px; border: 1px solid #e0e0e0; color: #aaa; background: #f9f9f9; border-radius: 5px;">
                                        {{ $element }}
                                    </li>
                                @endif

                                {{-- Array Of Links --}}
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $recentActivities->currentPage())
                                            <li style="margin: 0 5px; padding: 8px 15px; border: 1px solid #4CAF50; color: white; background: #4CAF50; border-radius: 5px;">
                                                {{ $page }}
                                            </li>
                                        @else
                                            <a href="{{ $url }}" style="text-decoration: none;">
                                                <li style="margin: 0 5px; padding: 8px 15px; border: 1px solid #4CAF50; color: #4CAF50; cursor: pointer; background: white; border-radius: 5px;">
                                                    {{ $page }}
                                                </li>
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($recentActivities->hasMorePages())
                                <a href="{{ $recentActivities->nextPageUrl() }}" style="text-decoration: none;">
                                    <li style="margin: 0 5px; padding: 8px 15px; border: 1px solid #4CAF50; color: #4CAF50; cursor: pointer; background: white; border-radius: 5px;">
                                        Next
                                    </li>
                                </a>
                            @else
                                <li style="margin: 0 5px; padding: 8px 15px; border: 1px solid #e0e0e0; color: #aaa; cursor: not-allowed; background: #f9f9f9; border-radius: 5px;">
                                    Next
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
        </div> 
    </div>

  <!-- USER Interactive -->
    <div class="recent-activity" style="backgroud-color:rgb(118, 209, 255)">
        <div class="user-container">
                <div style="width:max-content; margin: 0 auto; text-align: center;">
                    <h2>Interactive User Statistics</h2>
                </div>
            <div class="cards-container">
                <div style="width:max-content; margin: 0 auto; text-align: center;">
                    <h3>User Type Distribution</h3>
                    <canvas id="userTypeChart"></canvas>
                </div>

            <div style="width: 100%; margin-top: 30px;">
                <h3 style="text-align: center;">List of All Users</h3>
                <div id="hoveredRole" style="text-align: center; margin-top: 20px; font-size: 18px; font-weight: bold; color: #555;"></div>
                
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">User Name</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Role</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endif


<!-- Caregiver Dashboard -->
@if ($role === 'Caregiver')
    <div class="container">

        <!-- Overview Cards Section -->
        <div class="cards-container">
            <div class="card">
                <h3>My Clients</h3>
                <p>{{ $totalUsersWithRoleForCaregiver }}</p>
            </div>
            <div class="card">
                <h3>Missed Tasks</h3>
                <p style="color: red;">{{ $totalMissedReminders }}</p>
            </div>
            <div class="card">
                <h3>Pending Tasks</h3>
                <p style="color: orange;">{{ $totalPendingReminders }}</p>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="recent-activity">
            <h2>Recent Activities</h2>

            <!-- Caregiver's Care Logs Table -->
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Assigned Date</th>
                        <th>Assigned Time</th>
                        <th>Action Date</th>
                        <th>Action Time</th>
                        <th>Medicine Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usersCareLog as $userId => $userData)
                        @if (isset($userData['reminders']) && !empty($userData['reminders']))
                            {{-- Sort the reminders by actionDate in descending order --}}
                            @php
                                    // Filter out reminders without a 'date' and then sort the reminders in descending order based on 'date'
                                    $sortedReminders = collect($userData['reminders'])
                                        ->filter(function ($reminder) {
                                            return isset($reminder['date']); // Only keep reminders that have a 'date' field
                                        })
                                        ->sortByDesc(function ($reminder) {
                                            return strtotime($reminder['date']); // Sort based on 'date'
                                        });
                                @endphp

                            @foreach ($sortedReminders as $reminderId => $reminder)
                                <tr>
                                    <!-- Directly display Name without dropdown -->
                                    <td>{{ $userData['userDetails']['name'] ?? 'N/A' }}</td>

                                    <!-- Assigned Date -->
                                    <td>    @if(isset($reminder['date']))
                                                {{ \Carbon\Carbon::parse($reminder['date'])->format('d-M-Y') }}
                                            @endif</td>

                                    <!-- Assigned Time -->
                                    <td>{{ isset($reminder['time']) ? \Carbon\Carbon::parse($reminder['time'])->format('H:i') : 'N/A' }}</td>

                                    <!-- Action Date -->
                                    <td>{{ isset($reminder['actionDate']) ? \Carbon\Carbon::parse($reminder['actionDate'])->format('d-M-Y') : 'N/A' }}</td>

                                    <!-- Action Time -->
                                    <td>{{ isset($reminder['actionTime']) ? \Carbon\Carbon::parse($reminder['actionTime'])->format('H:i') : 'N/A' }}</td>

                                    <!-- Medicine Name -->
                                    <td>{{ $reminder['medicineName'] ?? 'N/A' }}</td>

                                    <!-- Status with color coding -->
                                    <td style="color: {{ 
                                        $reminder['status'] === 'missed' ? 'red' : 
                                        ($reminder['status'] === 'taken' ? 'green' : 
                                        ($reminder['status'] === 'pending' ? 'orange' : 'black')) 
                                    }};">
                                        {{ $reminder['status'] ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <!-- If no reminders, show a row indicating this -->
                            <tr>
                                <td colspan="7" class="text-center">No reminders available for this user.</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    </div>
@endif

<script>

function scrollToUsers() {
    const activitiesSection = document.querySelector('.user-container');
    if (activitiesSection) {
        activitiesSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });
    }
}

function scrollToServiceTrends() {
    const activitiesSection = document.querySelector('.chart-container');
    if (activitiesSection) {
        activitiesSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });
    }
}

function scrollToActivities() {
    const activitiesSection = document.querySelector('.recent-activities');
    if (activitiesSection) {
        activitiesSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Donut Chart Configuration
    const ctx1 = document.getElementById('userTypeChart').getContext('2d');
    const hoveredRoleElement = document.getElementById('hoveredRole');
    const userTableBody = document.getElementById('userTableBody');
   
    // Pass all users data to JavaScript
    const allUsers = @json($users); // Laravel variable containing all users data


    // Define role mappings
    const roleMapping = {
        1: 'Admin',
        2: 'Active Caregiver',
        3: 'Active Client'
    };

    const userTypeData = {
        labels: ['Admin', 'Active Caregiver', 'Active Client'],
        datasets: [{
            data: [
                {{ $totalUsersWithRole1 ?? 0 }},
                {{ $totalUsersWithRole2 ?? 0 }},
                {{ $totalUsersWithRole3 ?? 0 }}
            ],
            backgroundColor: ['#4CAF50', '#FF9800', '#2196F3'],
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    };

    const userTypeConfig = {
        type: 'doughnut',
        data: userTypeData,
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(2);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            onHover: function (event, elements) {
                if (elements.length > 0) {
                    const index = elements[0].index; // Hovered segment index
                    const role = index + 1; // Map index to role (1, 2, 3)

                    // Temporary update of "Currently Viewing" text
                    hoveredRoleElement.textContent = `Currently Viewing: ${roleMapping[role]}`;

                    // Dynamically update the table based on hover
                    updateTable(role);
                } else {
                    // Reset to previously clicked role if no hover
                    hoveredRoleElement.textContent = `Currently Viewing: ${currentRole ? roleMapping[currentRole] : 'All Users'}`;

                    // Reset the table to the last clicked role or all users
                    updateTable(currentRole);
                }
            },
            onClick: function (event, elements) {
                if (elements.length > 0) {
                    const index = elements[0].index; // Clicked segment index
                    const role = index + 1; // Map index to role

                    // Fix "Currently Viewing" text to clicked role
                    hoveredRoleElement.textContent = `Currently Viewing: ${roleMapping[role]}`;

                    // Permanently update the table based on clicked role
                    currentRole = role; // Save the clicked role globally
                    updateTable(role);
                }
            }
        }
    };

    let currentRole = null;

    const userTypeChart = new Chart(ctx1, userTypeConfig);

    /**
     * Function to update the table based on the selected role.
     * @param {number|null} role - Role to filter by (1, 2, 3). Pass null to show all users.
     */
    function updateTable(role) {
            // Clear existing table rows
            userTableBody.innerHTML = '';

            // Filter users based on role
            const filteredUsers = role
                ? Object.entries(allUsers).filter(([userId, user]) => user.role === role && user.status === true)
                : Object.entries(allUsers).filter(([userId, user]) => user.status === true);

            // Generate table rows
            filteredUsers.forEach(([userId, user]) => {
                const row = document.createElement('tr');
                const nameCell = document.createElement('td');
                nameCell.textContent = user.name || 'Unknown';
                row.appendChild(nameCell);

                const roleCell = document.createElement('td');
                roleCell.textContent = roleMapping[user.role] || 'Unknown';
                row.appendChild(roleCell);

                const actionCell = document.createElement('td');
                const viewDetailsLink = document.createElement('a');
                viewDetailsLink.href = `/users/users/${userId}/view`; // Use the correct route
                viewDetailsLink.textContent = 'View Details';
                viewDetailsLink.style.color = '#4CAF50';
                viewDetailsLink.style.textDecoration = 'none';
                actionCell.appendChild(viewDetailsLink);
                row.appendChild(actionCell);

                userTableBody.appendChild(row);
            });

            // Show a message if no users match the filter
            if (filteredUsers.length === 0) {
                const noDataRow = document.createElement('tr');
                const noDataCell = document.createElement('td');
                noDataCell.colSpan = 3;
                noDataCell.textContent = 'No users found for this role.';
                noDataCell.style.textAlign = 'center';
                userTableBody.appendChild(noDataRow);
            }
        }


    // Add custom legend below the donut chart
    const legendContainer = document.createElement('div');
        legendContainer.style.textAlign = 'center';
        legendContainer.style.marginTop = '15px';
        legendContainer.style.fontSize = '14px';

    const totalUsers = userTypeData.datasets[0].data.reduce((a, b) => a + b, 0);

    userTypeData.labels.forEach((label, index) => {
        const count = userTypeData.datasets[0].data[index];
        const percentage = ((count / totalUsers) * 100).toFixed(2);

        const legendItem = document.createElement('div');
        legendItem.style.display = 'flex';
        legendItem.style.justifyContent = 'center';
        legendItem.style.alignItems = 'center';
        legendItem.style.marginBottom = '8px';

        const colorBox = document.createElement('span');
        colorBox.style.display = 'inline-block';
        colorBox.style.width = '15px';
        colorBox.style.height = '15px';
        colorBox.style.backgroundColor = userTypeData.datasets[0].backgroundColor[index];
        colorBox.style.marginRight = '10px';

        const legendText = document.createElement('span');
        legendText.textContent = `${label}: ${count} (${percentage}%)`;
        legendText.style.color = '#333';

        legendItem.appendChild(colorBox);
        legendItem.appendChild(legendText);
        legendContainer.appendChild(legendItem);
    });

    document.querySelector('#userTypeChart').parentElement.appendChild(legendContainer);

     // Initialize the table to show all active users
     updateTable(null);
   
});

</script>
@endsection
