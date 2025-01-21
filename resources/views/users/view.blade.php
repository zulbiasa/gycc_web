@extends('layouts.app')

@section('title', 'View All Users')

@section('content')
<div class="container">

    <!-- Blue Board -->
    <div class="blue-board">
        <div>
            <h1>User Management : Total User <span style="font-size: 28px;">{{ count($users) }}</span></h1>
        </div>
        <div>
            <a href="{{ route('users.create') }}" class="btn">Register User</a>
            <a href="#" class="btn">Care Plan</a>
            <!-- <a href="#" class="btn">Trend Services</a> -->
        </div>
    </div>
 
       <!-- Search Bar -->
 <!-- Filter Form -->
<div class="filter-bar">
    <form action="{{ route('users.view') }}" method="GET" class="filter-form">
        <div class="filter-row">
            <div class="form-group compact-search-by">
                    <label for="searchBy">Search:</label>
                    <select name="searchBy" id="searchBy" class="form-control compact-select">
                        <option value="name" {{ $searchBy === 'name' ? 'selected' : '' }}>Name</option>
                        <option value="role" {{ $searchBy === 'role' ? 'selected' : '' }}>Role</option>
                        <option value="status" {{ $searchBy === 'status' ? 'selected' : '' }}>Status</option>
                    </select>
            </div>

            <!-- Name Filter -->
            <div class="form-group" id="nameFilter" style="{{ $searchBy === 'name' ? '' : 'display: none;' }}">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" value="{{ $selectedName }}">
            </div>

            <!-- Role Filter -->
            <div class="form-group" id="roleFilter" style="{{ $searchBy === 'role' ? '' : 'display: none;' }}">
                <label for="role">Role:</label>
                <select name="role" id="role" class="form-control">
                    <option value="">-- Select Role --</option>
                    <option value="Admin" {{ $selectedRole === 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Caregiver" {{ $selectedRole === 'Caregiver' ? 'selected' : '' }}>Caregiver</option>
                    <option value="Client" {{ $selectedRole === 'Client' ? 'selected' : '' }}>Client</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="form-group" id="statusFilter" style="{{ $searchBy === 'status' ? '' : 'display: none;' }}">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="">-- Select Status --</option>
                    <option value="1" {{ $selectedStatus === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $selectedStatus === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="filter-buttons">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('users.view') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>
</div>

    <!-- List of Users -->
    <div class="table-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>IC No.</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user['name'] }}</td>
                    <td>{{ $user['ic_no'] }}</td>
                    <td>{{ $user['role_name'] ?? 'Unknown' }}</td>
                    <td>
                        @if ($user['status'])
                            <span style="color: green; font-weight: bold;">Active</span>
                        @else
                            <span style="color: red; font-weight: bold;">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('users.viewUser', $user['id']) }}" class="action-btn">
                            View Details
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No users found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


    <script>
        // Show/Hide Filters Based on Search By
        const searchBySelect = document.getElementById('searchBy');
        const nameFilter = document.getElementById('nameFilter');
        const roleFilter = document.getElementById('roleFilter');
        const statusFilter = document.getElementById('statusFilter');

        searchBySelect.addEventListener('change', function () {
            const searchBy = this.value;

            // Toggle filters based on selected option
            nameFilter.style.display = searchBy === 'name' ? '' : 'none';
            roleFilter.style.display = searchBy === 'role' ? '' : 'none';
            statusFilter.style.display = searchBy === 'status' ? '' : 'none';
        });
    </script>
 

<style>

.filter-bar {
        margin-bottom: 20px;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        width: 100%;
        margin-bottom: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
        flex: 1;
        min-width: 150px;
    }

    .form-group label {
        font-weight: bold;
        font-size: 14px;
        color: #333;
    }

    .form-control {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    .filter-buttons {
        display: flex;
        gap: 15px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
    
    .search-bar .btn-primary:hover {
        background-color: #0056b3;
    }

      /* Blue Board Styling */
      .blue-board {
            background-color: #003366;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .blue-board h1 {
            margin: 0;
            font-size: 24px;
        }

        .blue-board .btn {
            background-color: #ff6666;
            border: none;
            padding: 10px 15px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            margin-left: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .blue-board .btn:hover {
            background-color: #e05555;
        }

    .table-container {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #ddd;
        margin-top: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Arial', sans-serif;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    thead {
        background-color: #003366;
        color: white;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        display: inline-block;
    }

    .status-badge.active {
        background-color: #28a745;
        color: white;
    }

    .status-badge.inactive {
        background-color: #dc3545;
        color: white;
    }

    .action-btn {
        background-color: #007bff;
        color: white;
        padding: 8px 12px;
        border-radius: 5px;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .action-btn:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }
    /* Compact Search By */
    .compact-search-by {
        max-width: 150px;
    }

    .compact-select {
        padding: 8.8px 10px;
        font-size: 12px;
    }

    .table-container {
        max-height: 400px; /* Set the max height for the table */
        overflow-y: auto; /* Enable vertical scrolling */
        border: 1px solid #ddd; /* Add a border around the table */
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed; /* Ensures consistent column width */
    }

    .table-container thead th {
        position: sticky;
        top: 0;
        background-color:#003366; /* Set a background color for the header */
        z-index: 10;
        text-align: left;
    }

    .table-container th, .table-container td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .table-container tbody tr:hover {
        background-color:rgb(203, 231, 255); /* Highlight the row on hover */
    }
</style>
@endsection
