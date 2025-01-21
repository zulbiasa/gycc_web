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
</style>
@if ($role === 'Admin')
<div class="container mt-5">
    <div class="row">
    <div class="recent-activity" style="background-color: #003366;">
        <h1 style=" font-family: 'Roboto', sans-serif; text-align: center; color:rgb(255, 255, 255);">Welcome to Admin Dashboard</h1>
    </div>
    <div class="recent-activity">
    <div class="recent-activities">
                        <h2 style="font-family: 'Roboto', sans-serif; text-align: center; color: #4CAF50;">Recent Activities</h2>
                        <div class="table-container" style="overflow-x: auto; border-radius: 10px; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); margin-top: 20px;">
                            <table style="width: 100%; border-collapse: collapse; font-family: 'Roboto', sans-serif; background: white;">
                                <thead>
                                    <tr style="background: #f9f9f9; text-align: left;">
                                        <th style="padding: 10px; border-bottom: 2px solid #e0e0e0; color: #555;">#</th>
                                        <th style="padding: 10px; border-bottom: 2px solid #e0e0e0; color: #555;">Type</th>
                                        <th style="padding: 10px; border-bottom: 2px solid #e0e0e0; color: #555;">Activity</th>
                                        <th style="padding: 10px; border-bottom: 2px solid #e0e0e0; color: #555;">Date</th>
                                        <th style="padding: 10px; border-bottom: 2px solid #e0e0e0; color: #555;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentActivities as $index => $activity)
                                        <tr style="text-align: left; background: {{ $index % 2 === 0 ? '#fcfcfc' : '#fff' }};">
                                            <td style="padding: 10px; border-bottom: 1px solid #e0e0e0;">{{ $loop->iteration }}</td>
                                            <td style="padding: 10px; border-bottom: 1px solid #e0e0e0; font-weight: bold; color:rgb(0, 2, 114);">
                                                {{ $activity['type'] }}
                                            </td>
                                            <td style="padding: 10px; border-bottom: 1px solid #e0e0e0;">{{ $activity['activity'] }}</td>
                                            <td style="padding: 10px; border-bottom: 1px solid #e0e0e0;">{{ $activity['date'] }}</td>
                                            <td style="padding: 10px; border-bottom: 1px solid #e0e0e0;">
                                                <span style="padding: 5px 10px; border-radius: 5px; background: {{ in_array($activity['status'], ['complete', 'taken']) ? '#4CAF50' : ($activity['status'] == 'pending' ? '#FFC107' : '#FF5722') }}; color: white;">
                                                    {{ in_array($activity['status'], ['complete', 'taken']) ? 'Complete' : ucfirst($activity['status']) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" style="padding: 20px; text-align: center; color: #999;">
                                                No recent activities found.
                                            </td>
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
                <br>
    </div>
    <!-- <div class="cards-container">
        <div class="card">
            <h3>Total Active Users</h3>
            <p>{{ $totalActiveUsers }}</p>
        </div>
        <div class="card">
            <h3>Admin</h3>
            <p>{{ $totalUsersWithRole1 }}</p>
        </div>
        <div class="card">
            <h3>Active Caregiver</h3>
            <p>{{ $totalUsersWithRole2 }}</p>
        </div>
        <div class="card">
            <h3>Active Client</h3>
            <p>{{ $totalUsersWithRole3 }}</p>
        </div>
    </div> -->
    
    <div class="recent-activity">
        <div class="cards-container">
                    <div style="width:max-content; margin: 0 auto; text-align: center;">
                        <h2>User Type Distribution</h2>
                        <canvas id="userTypeChart"></canvas>
                    </div>
       
                    <div style="width:max-content; margin: 0 auto; text-align: center;">
                        <h2>Number of User</h2><br>
                        <div class="cards-container" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px;">

                            <div class="card" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px; text-align: center;">
                                <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 10px;">Total Active Users</h3>
                                <p style="font-size: 2rem; font-weight: bold; color: #4CAF50;">{{ $totalActiveUsers }}</p>
                            </div>

                            <div class="card" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px; text-align: center;">
                                <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 10px;">Admin</h3>
                                <p style="font-size: 2rem; font-weight: bold; color: #4CAF50;">{{ $totalUsersWithRole1 }}</p>
                            </div>

                            <div class="card" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px; text-align: center;">
                                <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 10px;">Active Caregiver</h3>
                                <p style="font-size: 2rem; font-weight: bold; color: #4CAF50;">{{ $totalUsersWithRole2 }}</p>
                            </div>

                            <div class="card" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px; text-align: center;">
                                <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 10px;">Active Client</h3>
                                <p style="font-size: 2rem; font-weight: bold; color: #4CAF50;">{{ $totalUsersWithRole3 }}</p>
                            </div>
                            </div>
                        </div>
            </div>
    </div>

    <div class="recent-activity">
        <div class="chart-container">
            <h2>Service Subscription Trends</h2>
            <canvas id="serviceTrendsChart"></canvas>
        </div>
    </div>

    <div id="popup" class="popup-container" style="display: none;">
        <div class="popup-content">
            <h3 id="popup-title"></h3>
            <table class="popup-table">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="popup-client-list"></tbody>
            </table>
            <button onclick="closePopup()" class="popup-close-btn">Close</button>
        </div>
    </div>
    <div id="popup-overlay" class="popup-overlay" style="display: none;" onclick="closePopup()"></div>
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
                    @foreach ($users as $userId => $userData)
                        @if (isset($userData['reminders']) && !empty($userData['reminders']))
                            {{-- Sort the reminders by actionDate in descending order --}}
                            @php
                                // Sort the reminders in descending order based on actionDate
                                $sortedReminders = collect($userData['reminders'])->sortByDesc(function ($reminder) {
                                    return strtotime($reminder['date']);
                                });
                            @endphp

                            @foreach ($sortedReminders as $reminderId => $reminder)
                                <tr>
                                    <td>{{ $userData['userDetails']['name'] ?? 'N/A' }}</td>
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

        <!-- Caregiver Specific Section 
        <div class="caregiver-specific">
            <h3>Your Tasks</h3>
            <ul>
                <li>Assist with patient A's daily medication</li>
                <li>Check vitals for patient B</li>
                <li>Prepare meals for patients C & D</li>
                <li>Update patient progress in system</li>
            </ul>
        </div>-->

    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx1 = document.getElementById('userTypeChart').getContext('2d');
        const ctx2 = document.getElementById('serviceTrendsChart').getContext('2d');

        const userTypeConfig = {
            type: 'doughnut',
            data: {
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
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(2);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '50%'
            }
        };

        const serviceNames = @json(array_values($serviceNames));
        const serviceIds = @json(array_keys($serviceNames));
        const yearlyTrends = @json($serviceTrendsByYear);
        const overallTrends = @json($overallTrends);
        const serviceClients = @json($serviceClients);

        const yearlyDatasets = Object.keys(yearlyTrends).map(year => {
            return {
                label: `Year ${year}`,
                data: serviceIds.map(id => yearlyTrends[year][id] || 0),
                backgroundColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.5)`
            };
        });

        const overallDataset = {
            label: 'Overall Subscriptions',
            data: serviceIds.map(id => overallTrends[id] || 0),
            backgroundColor: 'rgba(54, 162, 235, 0.8)'
        };

        const serviceTrendsConfig = {
            type: 'bar',
            data: {
                labels: serviceNames,
                datasets: [...yearlyDatasets, overallDataset]
            },
            options: {
                responsive: true,
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const serviceId = serviceIds[index];
                        const clients = serviceClients[serviceId] || [];
                        showPopup(serviceNames[index], clients);
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const dataset = tooltipItem.dataset.label;
                                const count = tooltipItem.raw;
                                return `${dataset}: ${count} subscriptions`;
                            }
                        }
                    }
                },
                scales: {
                    x: { stacked: true },
                    y: { stacked: true, beginAtZero: true }
                }
            }
        };

        new Chart(ctx1, userTypeConfig);
        new Chart(ctx2, serviceTrendsConfig);
    });

    function showPopup(serviceName, clients) {
        document.getElementById('popup-title').textContent = `Clients Subscribed to ${serviceName}`;
        const clientList = document.getElementById('popup-client-list');
        clientList.innerHTML = '';

        clients.forEach(client => {
            const row = document.createElement('tr');

            const nameCell = document.createElement('td');
            nameCell.textContent = client.name;

            const actionCell = document.createElement('td');
            const viewLink = document.createElement('a');
            viewLink.href = `/careplan/${client.clientId}/${client.planId}/edit-caregiver`;
            viewLink.innerHTML = '<i class="fas fa-edit"></i>';
            actionCell.appendChild(viewLink);

            row.appendChild(nameCell);
            row.appendChild(actionCell);
            clientList.appendChild(row);
        });

        document.getElementById('popup').style.display = 'block';
        document.getElementById('popup-overlay').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('popup-overlay').style.display = 'none';
    }
</script>
@endsection
