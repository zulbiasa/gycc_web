@extends('layouts.app')

@section('title', 'Edit Careplan')

@section('content')
<style>
    body {
        width: 100%;
    }

    .edit-container {
        width: 100%;
        background: #ffffff;
        border-radius: 12px;
        padding: 20px 30px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .edit-container h2 {
        font-size: 28px;
        color: #343a40;
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .accordion-button {
        font-weight: bold;
        color: #007bff;
    }

    .accordion-button:focus {
        box-shadow: none;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
    }

    .form-control {
        border-radius: 8px;
    }

    .btn-primary, .btn-secondary {
        width: 150px;
        margin: 10px;
    }

    .btn-group {
        text-align: center;
    }
</style>

<div class="edit-container">
    <h2>Edit Care Plan</h2>
    <form action="{{ route('careplan.update', [$carePlan['userId'], $carePlan['planId']]) }}" method="POST">
        @csrf
        @method('POST')

        <div class="accordion" id="careplanAccordion">
            <!-- Details Section -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDetails">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetails" aria-expanded="true" aria-controls="collapseDetails">
                        Details Section
                    </button>
                </h2>
                <div id="collapseDetails" class="accordion-collapse collapse show" aria-labelledby="headingDetails" data-bs-parent="#careplanAccordion">
                    <div class="accordion-body">
                        <div class="form-group">
                            <label for="clientName">Client Name:</label>
                            <input type="text" id="clientName" name="clientName" class="form-control" value="{{ old('clientName', $carePlan['clientName']) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="planType">Care Plan Type:</label>
                            <input type="text" id="planType" name="planType" class="form-control" value="{{ old('planType', $carePlan['planType']) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="startDate">Plan Start:</label>
                            <input type="date" id="startDate" name="startDate" class="form-control" value="{{ old('startDate', $carePlan['startDate']) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">Plan End:</label>
                            <input type="date" id="endDate" name="endDate" class="form-control" value="{{ old('endDate', $carePlan['endDate']) }}" required>
                        </div>
                        <div class="form-group">
    <label for="status">Status:</label>
    <select id="status" name="status" class="form-control" required>
        <option value="Active" {{ old('status', $carePlan['status']) === 'Active' ? 'selected' : '' }}>Active</option>
        <option value="Inactive" {{ old('status', $carePlan['status']) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</div>

<div class="form-group">
    <label for="caregiverSearch">Assigned Caregiver:</label>
    <div class="mb-2">
        <strong>Current Caregiver: </strong>
        <span id="currentCaregiver">
            {{ $carePlan['caregiverName'] ?? 'No caregiver assigned' }}
        </span>
    </div>

    <input type="text" id="caregiverSearch" class="form-control" placeholder="Search caregiver by name..." autocomplete="off">
    <input type="hidden" id="caregiverId" name="caregiverId" value="{{ old('caregiverId', $carePlan['caregiverName']) }}">

    <div id="caregiverList" class="list-group mt-1" style="display: none; max-height: 150px; overflow-y: auto;"></div>
</div>
                </div>
            </div>

            <!-- Service Section -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingServices">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseServices" aria-expanded="false" aria-controls="collapseServices">
                        Service Section
                    </button>
                </h2>
                <div id="collapseServices" class="accordion-collapse collapse" aria-labelledby="headingServices" data-bs-parent="#careplanAccordion">
                    <div class="accordion-body">
                        <div class="form-group">
                            <label for="services">List of Services:</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cost Section -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCost">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCost" aria-expanded="false" aria-controls="collapseCost">
                        Cost Section
                    </button>
                </h2>
                <div id="collapseCost" class="accordion-collapse collapse" aria-labelledby="headingCost" data-bs-parent="#careplanAccordion">
                    <div class="accordion-body">
                        <div class="form-group">
                            <label for="cost">Total Cost:</label>
                            <input type="number" id="cost" name="cost" class="form-control" value="{{ old('cost', $carePlan['userId']) }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Buttons -->
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('careplan.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<!-- Include Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('caregiverSearch').addEventListener('input', function () {
        const query = this.value.trim();
        const caregiverList = document.getElementById('caregiverList');
        caregiverList.innerHTML = '';
        caregiverList.style.display = query ? 'block' : 'none';

        if (query.length < 2) return; // Only search for 2+ characters

        fetch(`/search-caregiver?q=${query}`)
            .then(response => response.json())
            .then(data => {
                caregiverList.innerHTML = '';

                if (data.length === 0) {
                    caregiverList.innerHTML = '<div class="list-group-item">No caregivers found</div>';
                } else {
                    data.forEach(caregiver => {
                        const item = document.createElement('div');
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = caregiver.name;
                        item.dataset.id = caregiver.id;
                        caregiverList.appendChild(item);

                        item.addEventListener('click', () => {
                            document.getElementById('caregiverSearch').value = caregiver.name;
                            document.getElementById('caregiverId').value = caregiver.id;

                            // Update the current caregiver display
                            document.getElementById('currentCaregiver').textContent = caregiver.name;

                            caregiverList.style.display = 'none';
                        });
                    });
                }
            })
            .catch(err => {
                caregiverList.innerHTML = '<div class="list-group-item text-danger">Error fetching caregivers</div>';
            });
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('caregiverSearch').contains(e.target) &&
            !document.getElementById('caregiverList').contains(e.target)) {
            document.getElementById('caregiverList').style.display = 'none';
        }
    });
</script>
@endsection
