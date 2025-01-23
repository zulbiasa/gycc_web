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
         /* Additional Styles */
       .btn-warning {
            background-color: #ffc107;
            border: none;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

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
         font-size: 12px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
           display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

   .action-btn:hover {
       background-color: #0056b3;
      transform: scale(1.05);
   }
   .action-btn i{
        font-size: 16px;
   }

    .action-btn-text{
      display: inline;
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
        
        /* Styles for Blue Board*/

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
        
         /* Container */
         .container {
           max-width: 1200px; /* Adjust as needed */
           margin: 20px auto; /* Center the container */
           padding: 0 20px; /* Keep padding on side for small screen */
        }

        /* Filter Bar*/
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
             align-items: center;
            gap: 10px;
             margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
             border-radius: 8px;
            border: 1px solid #ddd;
             box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
             flex-direction: column; /* Stack items on smaller screens */
        }
       .filter-form {
          display: flex;
        flex-direction: column;
        gap: 15px;
       }
        .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
        }

        .filter-container select,
        .filter-container button {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
           transition: box-shadow 0.3s ease;
           min-width: 120px;
        }

        .form-group {
           display: flex;
            flex-direction: column;
            gap: 5px;
            flex: 1;
            min-width: 100px;
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

    /* Table with Scrollbar */
    .table-container {
          border-radius: 8px;
         overflow-x: auto;
         border: 1px solid #ddd;
         margin-top: 20px;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
     }
      .filter-button-row {
            margin-top: 10px;
             text-align: left;
        }

    /* Table Styling */

     table {
            width: 100%;
            border-collapse: collapse;
           font-family: 'Arial', sans-serif;
       }

        th, td {
            padding: 10px;
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

        /* Mobile Specific Adjustments */
    @media (max-width: 768px) {
        /* Container */
          .container {
                padding: 0 10px;
            }

        /* Blue Board */
           .blue-board {
                 flex-direction: column;
                 gap: 10px;
               padding: 10px;
          }
          .blue-board > div:first-child h1 {
                font-size: 18px;
                 margin-bottom: 5px;
           }
         .blue-board .btn {
               padding: 8px 12px;
                 font-size: 12px;
         }


         /* Filter Bar */
        .filter-bar {
          padding: 10px;
          }
          .filter-row {
             gap: 10px;
             margin-bottom: 10px;
        }
         .form-group {
          min-width: 100px;
        }
          .form-group label {
             font-size: 12px;
        }

            .form-control {
            padding: 6px 10px;
            font-size: 12px;
       }

       /* Filter Container */
       .filter-container {
           padding: 10px;
            flex-direction: column;
        }

       .filter-container select,
        .filter-container button {
           padding: 6px;
            font-size: 12px;
         }

            .filter-button-row {
           margin-top: 5px;
            }

          .action-btn-text{
            display: none;
           }
          .action-btn {
             padding: 5px 6px;
               font-size: 12px;
            }
             .action-btn i{
                font-size: 16px;
           }
          th, td {
              padding: 8px;
             font-size: 12px;
            }
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
            <a href="{{ route('careplan.index') }}" class="btn">Care Plan</a>
            <!-- <a href="#" class="btn">Trend Services</a> -->
        </div>
    </div>

    <div class="filter-bar">
    <form method="GET" action="{{ route('services.view') }}" class="filter-form">
        <div class="filter-row" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <!-- Search By Dropdown -->
            <div class="form-group">
                <label for="searchBy">Search By:</label>
                <select id="searchBy" name="searchBy" class="form-control" onchange="toggleFilterOptions()">
                    <option value="">Choose</option>
                    <option value="search" {{ request('searchBy') === 'search' ? 'selected' : '' }}>Search Service Name</option>
                    <option value="service" {{ request('searchBy') === 'service' ? 'selected' : '' }}>Service</option>
                    <option value="category" {{ request('searchBy') === 'category' ? 'selected' : '' }}>Category        </option>
                    <option  value="status" {{ request('searchBy') === 'status' ? 'selected' : '' }}>Status        </option>
                </select>
            </div>
            <!-- Search Service Name -->
            <div class="form-group" id="searchServiceName" style="display: none; flex: 1;">
                <label for="searchFilter">Service Name:</label>
                <input 
                    type="text" 
                    id="searchFilter" 
                    name="searchFilter" 
                    class="form-control" 
                    placeholder="Enter Service Name" 
                    value="{{ request('searchFilter') }}" 
                    style="width: 100%;"
                >
            </div>

            <!-- Service Dropdown -->
            <div class="form-group" id="serviceDropdown" style="display: none;">
                <label for="service">Service:</label>
                <select name="service" class="form-control" style="width: 100%;">
                    <option value="">All Services</option>
                    @foreach ($services as $service)
                        <option value="{{ $service['service'] }}" {{ request('service') === $service['service'] ? 'selected' : '' }}>
                            {{ $service['service'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Category Dropdown -->
            <div class="form-group" id="categoryDropdown" style="display: none;">
                <label for="category">Category:</label>
                <select name="category" class="form-control" style="width: 100%;">
                    <option value="">All Categories</option>
                    @foreach ($serviceCategories as $category)
                        <option value="{{ $category['category'] }}" {{ request('category') === $category['category'] ? 'selected' : '' }}>
                            {{ $category['category'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Dropdown -->
            <div class="form-group" id="statusDropdown" style="display: none;">
                <label for="status">Status:</label>
                <select name="status" class="form-control" style="width: 100%;">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <!-- Search Button Row -->
        <div class="filter-button-row" style="margin-top: 10px; text-align: left;">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
</div>

    <!-- Table with Scrollbar -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Name</th>
                    <th style="width: 15%;">Description</th>
                    <th style="width: 10%;">Cost</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th style="width: 7%;">Status</th>
                    <th style="width: 10%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $index => $service)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $service['service'] }}</td>
                    <td>{{ $service['description'] }}</td>
                    <td>RM {{ $service['cost'] }}</td>
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
                        <a href="{{ route('services.edit', $service['id']) }}" class="action-btn" style="text-decoration:none">
                             <i class="fas fa-edit"></i><span class="action-btn-text">Update</span>
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

        // Hide all filters initially
        document.getElementById('searchServiceName').style.display = 'none';
        document.getElementById('serviceDropdown').style.display = 'none';
        document.getElementById('categoryDropdown').style.display = 'none';
        document.getElementById('statusDropdown').style.display = 'none';

        // Show the relevant filter based on selection
        if (searchBy === 'search') {
            document.getElementById('searchServiceName').style.display = 'block';
        } else if (searchBy === 'service') {
            document.getElementById('serviceDropdown').style.display = 'block';
        } else if (searchBy === 'category') {
            document.getElementById('categoryDropdown').style.display = 'block';
        } else if (searchBy === 'status') {
            document.getElementById('statusDropdown').style.display = 'block';
        }
    }

    // Initialize the filters based on the current selection
    document.addEventListener('DOMContentLoaded', toggleFilterOptions);
    </script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</body>
</html>
@endsection