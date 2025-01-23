@extends('layouts.app')

@section('title', 'View All Users')

@section('content')
<div class="container">
    <!-- Blue Board -->
    <div class="blue-board">
        <div>
            <h1>User Management : Total User <span style="font-size: 28px;">{{ count($users) }}</span></h1>
        </div>
        <div class="blue-board-actions">
             <a href="{{ route('users.create') }}" class="btn btn-primary">Register User</a>
             <a href="{{ route('careplan.index') }}" class="btn btn-primary">Care Plan</a>
        </div>
    </div>

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

            <!-- Filter Buttons -->
        <div class="filter-buttons">
            <button type="submit" class="btn btn-primary">Filter</button>
             <a href="{{ route('users.view') }}" class="btn btn-secondary">Clear</a>
           <a href="{{ route('users.view', ['searchBy' => 'status', 'status' => isset($selectedStatus) && $selectedStatus == 0 ? 1 : 0]) }}" 
                class="btn"
                style="
                    background-color: {{ isset($selectedStatus) && $selectedStatus == 0 ? '#c8e6c9' : '#ffcc80' }};
                    color: {{ isset($selectedStatus) && $selectedStatus == 0 ?  '#2e7d32' : '#6d4c41' }};
                    font-weight: bold; 
                    border: none; 
                    padding: 10px 20px; 
                    text-transform: uppercase; 
                    transition: all 0.3s ease;"
                onmouseover="this.style.backgroundColor='{{ isset($selectedStatus) && $selectedStatus == 0 ? '#a5d6a7' : '#ffb74d' }}'; this.style.color='#000';"
                onmouseout="this.style.backgroundColor='{{ isset($selectedStatus) && $selectedStatus == 0 ?  '#c8e6c9' : '#ffcc80'}}'; this.style.color='{{ isset($selectedStatus) && $selectedStatus == 0 ?  '#2e7d32' : '#6d4c41' }}';">
                {{ isset($selectedStatus) && $selectedStatus == 0 ? 'Valid Users' : 'Invalid Users' }}
            </a>
        </div>
        </form>
    </div>

   <!-- List of Users -->
    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="table-header-sticky" style="width: 5%;">No.</th>
                     <th class="table-header-sticky" style="width: 30%;">Full Name</th>
                    <th class="table-header-sticky">IC No.</th>
                    <th class="table-header-sticky">Role</th>
                    <th class="table-header-sticky">Status</th>
                    <th class="table-header-sticky">Actions</th>
                </tr>
            </thead>
            <tbody>
                 @forelse ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="width: 30%;">{{ $user['name'] }}</td>
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
                            <a href="{{ route('users.viewUser', $user['id']) }}" class="action-btn" style="text-decoration:none">
                                 <i class="fas fa-eye"></i><span class="action-btn-text">View Details</span>
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

     .btn-warning {
         background-color: #ffc107;
         border: none;
         color: #212529;
     }

     .btn-warning:hover {
         background-color: #e0a800;
     }

    /* Container */
     .container {
       max-width: 1200px; /* Adjust as needed */
       margin: 20px auto; /* Center the container */
       padding: 0 20px; /* Keep padding on side for small screen */
    }

    /* Blue Board */
   .blue-board {
    background-color: #003366;
    color: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

   .blue-board > div:first-child h1 {
        font-size: 20px;
        margin-bottom: 10px;
     }

   .blue-board-actions {
         display: flex;
         flex-wrap: wrap;
         gap: 10px;
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
         cursor: pointer;
         transition: background-color 0.3s ease;
     }

       .blue-board .btn:hover {
         background-color: #e05555;
     }

    /* Filter Bar */
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
        flex-direction: column;
        gap: 15px;
     }

     .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
       flex: 1;
        min-width: 120px;
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
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-start;
    }


       .btn {
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 12px;
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

     /* Table Container */
    .table-container {
        border-radius: 8px;
         overflow-x: auto;
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
        max-width: 120px;
    }

    .compact-select {
      padding: 8px 10px;
      font-size: 12px;
    }

 /* Sticky table header */
    .table-header-sticky {
          position: sticky;
        top: 0;
        background-color: #003366;
      }

        /* Mobile Specific Adjustments */
    @media (max-width: 768px) {

        /* Container */
         .container {
             padding: 0 1px; /* Reduced padding for smaller screens */
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
          .blue-board-actions {
              justify-content: flex-start; /* Or center if you prefer */
               gap: 5px;
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

         .filter-buttons {
           gap: 5px;
        }

         .btn {
            padding: 8px 10px;
            font-size: 11px;
        }

        /* Table Container */
          .table-container {
             border-radius: 8px;
              overflow-x: auto; /* enable horizontal scroll on small screens */
           }
         .table-container table {
               width: 100%; /* ensure table take full container width */
           }

           th, td {
               padding: 8px;
               font-size: 12px;
           }
          .action-btn-text {
            display: none;
          }

          .action-btn {
             padding: 5px 6px;
             font-size: 12px;
            }
             .action-btn i{
                font-size: 16px;
           }
    }
  </style>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection