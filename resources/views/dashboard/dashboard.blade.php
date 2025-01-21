@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
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
        width: 30%;
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
        color: #4CAF50; /* Green color for the value */
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

    /* Table Styling */
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
                <p>{{ $activeServices }}</p>
            </div>
            <div class="card">
                <h3>Pending Tasks</h3>
                <p>
                    {{ count(array_filter($careLogs, function($careLog) {
                        return isset($careLog['status']) && strtolower($careLog['status']) != 'complete';
                    })) }}
                </p>

            </div>

        </div>

        <!-- Recent Activity Section -->
        <div class="recent-activity">
            <h2>Recent Activities</h2>

            <!-- Admin's Care Logs Table -->
            <table>
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Activity</th>
                        <th>Date</th>
                        <th>Notes</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($careLogs as $careLog)
                        <tr>
                            <td>{{ $careLog['userName'] }}</td>
                            <td>{{ $careLog['activity'] }}</td>
                            <td>{{ $careLog['date'] }}</td>
                            <td>{{ $careLog['notes'] }}</td>

                            <td class="{{ $careLog['status'] === 'complete' ? 'status-complete' : 'status-incomplete' }}">
                                {{ $careLog['status'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Caregiver Dashboard -->
@if ($role === 'Caregiver')
    <div class="container">

        <!-- Overview Cards Section -->
        <div class="cards-container">
            <div class="card">
                <h3>Total Users</h3>
                <p>{{ $totalUsersWithRole2 }}</p>
            </div>
            <div class="card">
                <h3>Active Services</h3>
                <p>{{ $activeServices }}</p>
            </div>
            <div class="card">
                <h3>Pending Tasks</h3>
                <p>12 cobaan</p>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="recent-activity">
            <h2>Recent Activities</h2>

            <!-- Caregiver's Care Logs Table -->
            <table>
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Activity</th>
                        <th>Date</th>
                        <th>Notes</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($careLogs as $careLog)
                        <tr>
                            <td>{{ $careLog['userName'] }}</td>
                            <td>{{ $careLog['activity'] }}</td>
                            <td>{{ $careLog['date'] }}</td>
                            <td>{{ $careLog['notes'] }}</td>
                            <td class="{{ $careLog['status'] === 'complete' ? 'status-complete' : 'status-incomplete' }}">
                                {{ $careLog['status'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Caregiver Specific Section -->
        <div class="caregiver-specific">
            <h3>Your Tasks</h3>
            <ul>
                <li>Assist with patient A's daily medication</li>
                <li>Check vitals for patient B</li>
                <li>Prepare meals for patients C & D</li>
                <li>Update patient progress in system</li>
            </ul>
        </div>

    </div>
@endif

@endsection
