@extends('layouts.app')

@section('title', 'Careplan')

@section('content')
<style>
/* Filter Bar Styles */
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
    gap: 10px;
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

/* Table Styles */
.table-container {
    margin-top: 20px;
    border-radius: 8px;
    border: 1px solid #ddd;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.table-container table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Arial', sans-serif;
}

.table-container th,
.table-container td {
    padding: 10px 15px;
    text-align: left;
    border: 1px solid #ddd;
}

.table-container thead th {
    background-color: #003366;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
}

.table-container tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table-container tbody tr:hover {
    background-color: rgb(203, 231, 255);
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

/* Action Buttons */
.action-btn {
    border: none;
    background: none;
    color: #007bff;
    font-size: 18px;
    cursor: pointer;
    margin-right: 10px;
    transition: color 0.3s ease, transform 0.2s ease;
}

.action-btn:hover {
    color: #0056b3;
    transform: scale(1.2);
}

.action-btn.delete {
    color: #dc3545;
}

.action-btn.delete:hover {
    color: #a71d2a;
}
</style>

<!-- Filter Form -->
<div class="filter-bar">
    <form action="{{ route('careplan.index') }}" method="GET" class="filter-form">
        <div class="filter-row">
            <div class="form-group compact-search-by"  style="flex: 0 0 15%;">
                <label for="searchBy">Search:</label>
                <select name="searchBy" id="searchBy" class="form-control compact-select">
                    <option value="clientName" {{ $searchBy === 'clientName' ? 'selected' : '' }}>Client Name</option>
                    <option value="caregiverName" {{ $searchBy === 'caregiverName' ? 'selected' : '' }}>Caregiver Name</option>
                    <!-- <option value="status" {{ $searchBy === 'status' ? 'selected' : '' }}>Status</option> -->
                </select>
            </div>

            <!-- Client Name Filter -->
            <div class="form-group" id="clientNameFilter" style="{{ $searchBy === 'clientName' ? '' : 'display: none;' }}">
                <label for="clientName">Client Name:</label>
                <input type="text" name="clientName" id="clientName" class="form-control" placeholder="Enter Client Name" value="{{ $selectedClientName }}">
            </div>

            <!-- Caregiver Name Filter -->
            <div class="form-group" id="caregiverNameFilter" style="{{ $searchBy === 'caregiverName' ? '' : 'display: none;' }}">
                <label for="caregiverName">Caregiver Name:</label>
                <input type="text" name="caregiverName" id="caregiverName" class="form-control" placeholder="Enter Caregiver Name" value="{{ $selectedCaregiverName }}">
            </div>

            <!-- Status Filter -->
            <div class="form-group" id="statusFilter" style="{{ $searchBy === 'status' ? '' : 'display: none;' }}">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="">-- Select Status --</option>
                    <option value="Active" {{ $selectedStatus === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ $selectedStatus === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="filter-buttons">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('careplan.index') }}" class="btn btn-secondary">Clear</a>
            <a href="{{ route('careplan.index', ['view' => isset($view) && $view === 'history' ? 'active' : 'history']) }}" 
                class="btn"
                style="
                    background-color: {{ isset($view) && $view === 'history' ? '#ffcc80' : '#c8e6c9' }};
                    color: {{ isset($view) && $view === 'history' ? '#6d4c41' : '#2e7d32' }};
                    font-weight: bold; 
                    border: none; 
                    padding: 10px 20px; 
                    text-transform: uppercase; 
                    transition: all 0.3s ease;"
                onmouseover="this.style.backgroundColor='{{ isset($view) && $view === 'history' ? '#ffb74d' : '#a5d6a7' }}'; this.style.color='#000';"
                onmouseout="this.style.backgroundColor='{{ isset($view) && $view === 'history' ? '#ffcc80' : '#c8e6c9' }}'; this.style.color='{{ isset($view) && $view === 'history' ? '#6d4c41' : '#2e7d32' }}';">
                    {{ isset($view) && $view === 'history' ? 'View Care Plan' : 'Care Plan History' }}
            </a>
        </div>
    </form>
</div>

<!-- Care Plan Table -->
<div class="table-container">
    <table id="carePlanTable">
        <thead>
            <tr>
                <th>Client</th>
                <th>Care Plan Type</th>
                <th>Plan Start</th>
                <th>Plan End</th>
                <th>Status</th>
                <th>Total Services</th>
                <th>Assigned Caregiver</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($carePlans) && count($carePlans) > 0)
                @foreach($carePlans as $plan)
                    <tr>
                        <td>{{ $plan['clientName'] }}</td>
                        <td>{{ $plan['planType'] }}</td>
                        <td>{{ date('d M Y', strtotime($plan['startDate'])) }}</td>
                        <td>{{ date('d M Y', strtotime($plan['endDate'])) }}</td>
                        <td>
                            <span class="status-badge {{ $plan['status'] === 'Active' ? 'active' : 'inactive' }}">
                                {{ $plan['status'] }}
                            </span>
                        </td>
                        <td>{{ $plan['totalServices'] }}</td>
                        <td>{{ $plan['caregiverName'] }}</td>
                        <td>
                            <a href="{{ route('careplan.editCaregiver', [$plan['userId'], $plan['planId']]) }}" class="action-btn" title="View Care Plan">
                                <i class="fas fa-eye"></i>
                            </a>
                            <!-- <a href="{{ route('careplan.editCaregiver', [$plan['userId'], $plan['planId']]) }}" class="action-btn" title="Edit Caregiver">
                                <i class="fas fa-edit"></i>
                            </a> -->
                            <form action="{{ route('careplan.editCaregiver', [$plan['userId'], $plan['planId']]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Delete Care Plan" onclick="return confirm('Are you sure you want to delete this care plan?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">No Care Plans Found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<script>
    // Toggle filter fields based on selected search criteria
    document.getElementById('searchBy').addEventListener('change', function () {
        const searchBy = this.value;
        document.getElementById('clientNameFilter').style.display = searchBy === 'clientName' ? '' : 'none';
        document.getElementById('caregiverNameFilter').style.display = searchBy === 'caregiverName' ? '' : 'none';
        document.getElementById('statusFilter').style.display = searchBy === 'status' ? '' : 'none';
    });
</script>

@endsection
