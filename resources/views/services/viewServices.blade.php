@extends('layouts.app')

@section('title', 'Services')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
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
        
        .filter-bar {
                margin-bottom: 20px;
                background-color: #f8f9fa;
                padding: 15px;
                border-radius: 8px;
                border: 1px solid #ddd;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            

        /* Filter Section Styling */
        .filter-container {
            display: flex;
            align-items: center;s
            gap: 10px;

            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .filter-container select,
        .filter-container button {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: box-shadow 0.3s ease;
        }

        .filter-container select:focus,
        .filter-container button:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .filter-container button {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .filter-container button:hover {
            background-color: #0056b3;
        }

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
</head>
<body>
    <!-- Blue Board -->
    <div class="blue-board">
        <div>
            <h1>Service Available <span style="font-size: 28px;">{{ count($services) }}</span></h1>
        </div>
        <div>
            <a href="{{ route('services.create') }}" class="btn">Add Services</a>
            <a href="#" class="btn">Care Plan</a>
            <a href="#" class="btn">Trend Services</a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-container">
        <form method="GET" action="{{ route('services.view') }}">
            <label for="searchBy">Search By:</label>
            <select id="searchBy" name="searchBy" onchange="toggleFilterOptions()">
                <option value="">Choose</option>
                <option value="service" {{ request('searchBy') === 'service' ? 'selected' : '' }}>Service</option>
                <option value="category" {{ request('searchBy') === 'category' ? 'selected' : '' }}>Category</option>
                <option value="status" {{ request('searchBy') === 'status' ? 'selected' : '' }}>Status</option>
            </select>

            <select id="serviceDropdown" name="service" style="display: none;">
                <option value="">All Services</option>
                @foreach ($services as $service)
                    <option value="{{ $service['service'] }}" 
                        {{ request('service') === $service['service'] ? 'selected' : '' }}>
                        {{ $service['service'] }}
                    </option>
                @endforeach
            </select>

            <select id="categoryDropdown" name="category" style="display: none;">
                <option value="">All Categories</option>
                @foreach ($serviceCategories as $category)
                    <option value="{{ $category['category'] }}" 
                        {{ request('category') === $category['category'] ? 'selected' : '' }}>
                        {{ $category['category'] }}
                    </option>
                @endforeach
            </select>

            <select id="statusDropdown" name="status" style="display: none;">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="btn-view">Search</button>
        </form>
    </div>

    <!-- Table with Scrollbar -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Service ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Cost</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                <tr>
                    <td>{{ $service['id'] }}</td>
                    <td>{{ $service['service'] }}</td>
                    <td>{{ $service['description'] }}</td>
                    <td>{{ $service['cost'] }}</td>
                    <td>{{ $service['location'] }}</td>
                    <td>{{ $service['category'] }}</td>
                    <td>
                        @if ($service['status'])
                            <span style="color: green; font-weight: bold;">Active</span>
                        @else
                            <span style="color: red; font-weight: bold;">Inactive</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('services.edit', $service['id']) }}" class="action-btn">
                            <i class="fas fa-edit"></i>UPDATE
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function toggleFilterOptions() {
            const searchBy = document.getElementById('searchBy').value;
            document.getElementById('serviceDropdown').style.display = searchBy === 'service' ? 'inline-block' : 'none';
            document.getElementById('categoryDropdown').style.display = searchBy === 'category' ? 'inline-block' : 'none';
            document.getElementById('statusDropdown').style.display = searchBy === 'status' ? 'inline-block' : 'none';
        }

        // Trigger on page load to display the correct dropdown
        toggleFilterOptions();
    </script>
</body>
</html>
@endsection
