@extends('layouts.app')

@section('title', 'Careplan')

@section('content')
<style>
.table-container {
    width: 100%;
    margin: 20px auto;
    padding: 20px;
    background: #f5f5f5;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.table-controls {
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
}

.search-box {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    flex: 1;
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    text-align: center;
}

.table-custom th,
.table-custom td {
    padding: 12px;
    border: 1px solid #ddd;
}

.table-custom th.sortable {
    cursor: pointer;
    position: relative;
    user-select: none;
}

.table-custom th.sortable:after {
    content: '⇅';
    font-size: 12px;
    color: black;
    position: absolute;
    right: 10px;
}

.table-custom thead tr {
    background-color: #007bff;
    color: white;
}

.table-custom tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table-custom tbody tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

.icon-btn i {
    font-size: 16px;
    cursor: pointer;
}

.icon-btn[title="View"] i {
    color: #28a745;
}

.icon-btn[title="Edit"] i {
    color: #ffc107;
}

.icon-btn[title="Delete"] i {
    color: #dc3545;
}
</style>

<div class="table-container">
    <div class="table-controls">
        <input type="text" id="searchBox" class="search-box" placeholder="Search by Client or Caregiver">
    </div>
    <table class="table-custom" id="carePlanTable">
        <thead>
            <tr>
                <th onclick="sortTable(0)" class="sortable">Client</th>
                <th onclick="sortTable(1)" class="sortable">Care Plan Type</th>
                <th onclick="sortTable(2)" class="sortable">Plan Start</th>
                <th onclick="sortTable(3)" class="sortable">Plan End</th>
                <th onclick="sortTable(4)" class="sortable">Status</th>
                <th onclick="sortTable(5)" class="sortable">Total Services</th>
                <th onclick="sortTable(6)" class="sortable">Assigned Caregiver</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($carePlans) && count($carePlans) > 0)
                @foreach($carePlans as $plan)
                    <tr>
                        <td>{{ $plan['clientName'] }}</td>
                        <td>{{ $plan['planType'] }}</td>
                        <td>{{ $plan['startDate'] }}</td>
                        <td>{{ $plan['endDate'] }}</td>
                        <td>{{ $plan['status'] }}</td>
                        <td>{{ $plan['totalServices'] }}</td>
                        <td>{{ $plan['caregiverName'] }}</td>
                        <td>
                        <a href="{{ route('careplan.view', [$plan['userId'], $plan['planId']]) }}" class="icon-btn" title="View">
    <i class="fas fa-eye"></i>
</a>
                            <a href="{{ route('careplan.edit', [$plan['userId'], $plan['planId']]) }}" class="icon-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('careplan.delete', [$plan['userId'], $plan['planId']]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this care plan?')">
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
    let sortDirections = {};

    function sortTable(columnIndex) {
        const table = document.getElementById('carePlanTable');
        const rows = Array.from(table.rows).slice(1); // Skip the header row
        const isAscending = !sortDirections[columnIndex]; // Toggle direction

        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[columnIndex].textContent.trim();
            const cellB = rowB.cells[columnIndex].textContent.trim();

            if (!isNaN(cellA) && !isNaN(cellB)) {
                return isAscending ? cellA - cellB : cellB - cellA;
            } else {
                return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
            }
        });

        sortDirections[columnIndex] = isAscending; // Save the new direction

        rows.forEach(row => table.tBodies[0].appendChild(row));
    }

    document.getElementById('searchBox').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#carePlanTable tbody tr');

        rows.forEach(row => {
            const clientName = row.cells[0].textContent.toLowerCase();
            const caregiverName = row.cells[6].textContent.toLowerCase();
            if (clientName.includes(filter) || caregiverName.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

</script>

@endsection
