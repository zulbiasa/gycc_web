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

        /* Filter Section Styling */
        .filter-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            gap: 10px;
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

        /* Table Container */
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        thead {
            background-color: #f4f4f4;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        th {
            background-color: #e2e2e2;
        }

        /* Action Button Styling */
        .action-btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 6px 12px;
            font-size: 12px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .action-btn i {
            margin-right: 5px;
        }

        .action-btn:hover {
            background-color: #218838;
            transform: scale(1.05);
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
