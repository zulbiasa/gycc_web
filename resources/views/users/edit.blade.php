@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h3>Edit User</h3>
    </div>
    <div class="form-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user['id']) }}" method="POST">
            @csrf
            @method('PUT') <!-- Method Spoofing for PUT -->
            
            <!-- Hidden field for user_id -->
            <input type="hidden" name="user_id" value="{{ $user['id'] }}">

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const roleField = document.getElementById('role');
                    if (roleField.value === 'Client') {
                        roleField.disabled = true;
                    }
                });
            </script>
            <table class="form-table">
                <!-- Role Selection -->
                <tr>
                    <td><label for="role">Role:</label></td>
                    <td>
                        <select name="role" id="role" class="form-control" {{ $user['role_name'] === 'Client' ? 'readonly' : '' }} required>
                            <option value="Admin" {{ $user['role_name'] === 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Caregiver" {{ $user['role_name'] === 'Caregiver' ? 'selected' : '' }}>Caregiver</option>
                            <option value="Client" {{ $user['role_name'] === 'Client' ? 'selected' : '' }}>Client</option>
                        </select>
                        @if ($user['role_name'] === 'Client')
                            <input type="hidden" name="role" value="{{ $user['role_name'] }}">
                        @endif
                        <!-- Add a hidden input to submit the role value -->
                        <!-- <input type="hidden" name="role" value="{{ $user['role_name'] }}"> -->
                    </td>
                </tr>
            </table>

            <!-- Common Sections -->
            <div id="commonSections">
                <div class="form-container">
                    <h4>Role's Profile</h4>
                    <label for="name">Full Name:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user['name']) }}" readonly required>

                    <label for="ic_no">IC Number:</label>
                    <input type="text" name="ic_no" id="ic_no" class="form-control" value="{{ old('ic_no', $user['ic_no']) }}" readonly required>

                    <label for="phone_no">Phone Number:</label>
                    <input type="text" name="phone_no" id="phone_no" class="form-control" value="{{ old('phone_no', $user['phone_no']) }}" required>

                    <label for="dob_display">Date of Birth:</label>
                    <input type="text" id="dob_display" class="form-control" value="{{ $user['formatted_dob'] }}" readonly>
                    <input type="hidden" name="dob" value="{{ $user['dob'] }}">

                    <label for="home_address">Home Address:</label>
                    <textarea name="home_address" id="home_address" class="form-control" required>{{ old('home_address', $user['home_address']) }}</textarea>

                    <label for="status">Status:</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="1" {{ old('status', $user['status']) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !old('status', $user['status']) ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <label for="gender">Gender:</label>
                    <select name="gender" id="gender" class="form-control" readonly required>
                        <option value="male" {{ old('gender', $user['gender']) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $user['gender']) === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="form-container">
                    <h4>App Authentication</h4>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="{{ $user['username'] }}" class="form-control" disabled>

                    <label for="password">Password:</label>
                    <input type="text" name="password" id="password" value="{{ $user['password'] }}" class="form-control" >

                    <label for="password_confirmation">Re-enter Password:</label>
                    <input type="text" name="password_confirmation" id="password_confirmation" value="{{ $user['password'] }}" class="form-control" >
                </div>

                <!-- Authorized Contact Person -->
                <div class="form-container">
                    <h4>Authorized Contact Person</h4>
                    <label for="contact_name">Name:</label>
                    <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{ old('contact_name', $user['emergency_contact']['name'] ?? '') }}">

                    <label for="contact_ic">IC Number:</label>
                    <input type="text" name="contact_ic" id="contact_ic" class="form-control" value="{{ old('contact_ic', $user['emergency_contact']['ic_no'] ?? '') }}">

                    <label for="contact_relationship">Relationship:</label>
                    <input type="text" name="contact_relationship" id="contact_relationship" class="form-control" value="{{ old('contact_relationship', $user['emergency_contact']['relationship'] ?? '') }}">

                    <label for="contact_phone_no">Phone Number:</label>
                    <input type="text" name="contact_phone_no" id="contact_phone_no" class="form-control" value="{{ old('contact_phone_no', $user['emergency_contact']['phone_no'] ?? '') }}">
                </div>
            </div>

            <!-- Medical Information (For Clients Only) -->
            <div id="medicalSection" style="{{ $user['role_name'] === 'Client' ? '' : 'display: none;' }}">
                <div class="form-container">
                    <h4>Medical & Health Information</h4>
                    
                    <!-- Blood Type Dropdown -->
                    <label for="blood_type">Blood Type:</label>
                    <select name="blood_type" id="blood_type" class="form-control">
                        <option value="">-- Select Blood Type --</option>
                        <option value="A+" {{ old('blood_type', $user['medical_info']['blood_type'] ?? '') === 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ old('blood_type', $user['medical_info']['blood_type'] ?? '') === 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ old('blood_type', $user['medical_info']['blood_type'] ?? '') === 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ old('blood_type', $user['medical_info']['blood_type'] ?? '') === 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="AB+" {{ old('blood_type', $user['medical_info']['blood_type'] ?? '') === 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ old('blood_type', $user['medical_info']['blood_type'] ?? '') === 'AB-' ? 'selected' : '' }}>AB-</option>
                        <option value="O+" {{ old('blood_type', $user['medical_info']['blood_type'] ?? '') === 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ old('blood_type', $user['medical_info']['blood_type'] ?? '') === 'O-' ? 'selected' : '' }}>O-</option>
                    </select>

                    <!-- Weight Field -->
                    <label for="weight">Weight (kg):</label>
                    <input type="number" step="0.1" name="weight" id="weight" class="form-control" value="{{ old('weight', $user['medical_info']['weight'] ?? '') }}">

                    <!-- Height Field -->
                    <label for="height">Height (cm):</label>
                    <input type="number" step="0.1" name="height" id="height" class="form-control" value="{{ old('height', $user['medical_info']['height'] ?? '') }}">

                   <!-- Allergic -->
                    <label for="allergic">Has Allergies:</label>
                    <select name="allergic" id="allergic" class="form-control" onchange="toggleAllergyFields()">
                        <option value="no" {{ old('allergic', isset($user['medical_info']['allergic']) ? 'yes' : 'no') === 'no' ? 'selected' : '' }}>No</option>
                        <option value="yes" {{ old('allergic', isset($user['medical_info']['allergic']) ? 'yes' : 'no') === 'yes' ? 'selected' : '' }}>Yes</option>
                    </select>

                    <div id="allergyFields" style="{{ old('allergic', isset($user['medical_info']['allergic']) ? 'yes' : 'no') === 'yes' ? 'display: block;' : 'display: none;' }}">
                        <label for="food_allergy">Food Allergies:</label>
                        <input type="text" name="food_allergy" id="food_allergy" class="form-control" value="{{ old('food_allergy', $user['medical_info']['allergic']['food'] ?? '') }}">

                        <label for="medicine_allergy">Medicine Allergies:</label>
                        <input type="text" name="medicine_allergy" id="medicine_allergy" class="form-control" value="{{ old('medicine_allergy', $user['medical_info']['allergic']['medicine'] ?? '') }}">
                    </div>
                    <br>
                    
                    <!-- Health Conditions Dropdown -->
                    <div class="form-group">
                        <label for="health_conditions">Health Conditions:</label>
                        <div style="display: flex; align-items: center;">
                            <select id="health_condition_dropdown" class="form-control" style="width: 70%;">
                                <option value="">-- Select Health Condition --</option>
                                @foreach ($allConditions as $condition)
                                    <option value="{{ $condition['id'] }}" data-name="{{ $condition['name'] }}" data-desc="{{ $condition['desc'] }}">
                                        {{ $condition['name'] }} ({{ $condition['desc'] }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" onclick="addHealthCondition()" style="margin-left: 10px;">+</button>
                        </div>
                        <!-- Selected Health Conditions List -->
                        <ul id="selected_health_conditions" style="margin-top: 15px; list-style: none; padding-left: 0;">
                            @foreach ($healthConditions as $condition)
                                <li data-id="{{ $condition['id'] }}">
                                    <span>{{ $condition['name'] }} ({{ $condition['desc'] }})</span>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove(); updateHiddenInput();">Remove</button>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Hidden Input for Health Conditions -->
                        <input type="hidden" name="health_conditions" id="health_conditions_input" value="{{ json_encode($healthConditions) }}">
                    </div>

                    <!-- Medications -->
                    <div class="form-group">
                        <label for="medications">Medications:</label>
                        <div style="display: flex; align-items: center;">
                            <select id="medication_dropdown" class="form-control" style="width: 70%;" onchange="checkForCustomMedication()">
                                <option value="">-- Select Medication --</option>
                                @foreach ($medications as $medication)
                                    <option value="{{ $medication['id'] }}" data-name="{{ $medication['name'] }}" data-purpose="{{ $medication['use'] }}">
                                        {{ $medication['name'] }}
                                    </option>
                                @endforeach
                                <option value="other">Other</option>
                            </select>
                            <button type="button" class="btn btn-primary" onclick="addMedication()" style="margin-left: 10px;">Add Medication</button>                        </div>
                    </div>

                        <!-- Custom Medication Input Fields -->
                        <div id="customMedicationFields" style="display: none; margin-top: 15px;">
                            <label for="custom_medication_name">Custom Medication Name:</label>
                            <input type="text" id="custom_medication_name" class="form-control" placeholder="Enter custom medication name">

                            <label for="custom_medication_purpose">Custom Medication Purpose:</label>
                            <input type="text" id="custom_medication_purpose" class="form-control" placeholder="Enter custom medication purpose">
                        </div>

                        <ul id="medication_list" style="margin-top: 15px; list-style: none; padding-left: 0;">
                            <!-- Dynamically added medication items will appear here -->
                              <!-- Hidden Input for Health Conditions -->
                        <input hidden>
                        </ul>
                    </div>

                    <!-- Physical Condition Dropdown -->
                    <label for="physical_condition">Physical Condition:</label>
                    <select name="physical_condition" id="physical_condition" class="form-control" >
                        <option value="">-- Select Physical Condition --</option>
                        <option value="Good" {{ old('physical_condition', $user['medical_info']['physical_condition'] ?? '') === 'Good' ? 'selected' : '' }}>Good</option>
                        <option value="Weak" {{ old('physical_condition', $user['medical_info']['physical_condition'] ?? '') === 'Weak' ? 'selected' : '' }}>Weak</option>
                        <option value="Bedridden" {{ old('physical_condition', $user['medical_info']['physical_condition'] ?? '') === 'Bedridden' ? 'selected' : '' }}>Bedridden</option>
                    </select>

                    <!-- Basic Needs Dropdown -->
                    <label for="basic_needs">Basic Needs:</label>
                    <select name="basic_needs" id="basic_needs" class="form-control">
                        <option value="None" {{ old('basic_needs', $user['medical_info']['basic_needs'] ?? '') === 'None' ? 'selected' : '' }}>None</option>
                        <option value="Wheelchair" {{ old('basic_needs', $user['medical_info']['basic_needs'] ?? '') === 'Wheelchair' ? 'selected' : '' }}>Wheelchair</option>
                        <option value="Hearing Aid" {{ old('basic_needs', $user['medical_info']['basic_needs'] ?? '') === 'Hearing Aid' ? 'selected' : '' }}>Hearing Aid</option>
                        <option value="Walking Stick" {{ old('basic_needs', $user['medical_info']['basic_needs'] ?? '') === 'Walking Stick' ? 'selected' : '' }}>Walking Stick</option>
                        <option value="Walker" {{ old('basic_needs', $user['medical_info']['basic_needs'] ?? '') === 'Walker' ? 'selected' : '' }}>Walker</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-3">Save Changes</button>
        </form>
    </div>
</div>

<script>

    // Function to add Health Condition
    function addHealthCondition() {
    const dropdown = document.getElementById('health_condition_dropdown');
    const selectedOption = dropdown.options[dropdown.selectedIndex];
    const conditionId = selectedOption.value;
    const conditionName = selectedOption.getAttribute('data-name');
    const conditionDesc = selectedOption.getAttribute('data-desc');

    if (!conditionId) {
        alert('Please select a valid health condition.');
        return;
    }

    const existingItem = document.querySelector(`#selected_health_conditions li[data-id="${conditionId}"]`);
    if (existingItem) {
        alert('This health condition is already added.');
        return;
    }

    const listItem = document.createElement('li');
    listItem.setAttribute('data-id', conditionId);
    listItem.innerHTML = `
        <span>${conditionName} (${conditionDesc})</span>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove(); updateHiddenInput();">Remove</button>
    `;

    const selectedList = document.getElementById('selected_health_conditions');
    selectedList.appendChild(listItem);

    updateHiddenInput();
    dropdown.value = ''; // Reset dropdown
}

function updateHiddenInput() {
    const selectedItems = document.querySelectorAll('#selected_health_conditions li');
    const selectedData = Array.from(selectedItems).map(item => ({
        id: item.getAttribute('data-id'),
        name: item.querySelector('span').textContent.split(' (')[0].trim(),
        desc: item.querySelector('span').textContent.split(' (')[1]?.replace(')', '').trim()
    }));
    document.getElementById('health_conditions_input').value = JSON.stringify(selectedData);
}

    document.addEventListener('DOMContentLoaded', function () {
        // Prepopulate existing health conditions
        const existingConditions = @json($user['medical_info']['healthConditions'] ?? []);
        const selectedList = document.getElementById('selected_health_conditions');

        existingConditions.forEach(condition => {
            const listItem = document.createElement('li');
            listItem.setAttribute('data-id', condition.id);
            listItem.style.display = 'flex';
            listItem.style.alignItems = 'center';
            listItem.style.marginBottom = '10px';

            const label = document.createElement('span');
            label.textContent = `${condition.name} (${condition.desc})`;
            label.style.flexGrow = '1';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = 'Remove';
            removeButton.classList.add('btn', 'btn-danger', 'btn-sm');
            removeButton.style.marginLeft = '10px';
            removeButton.onclick = function () {
                listItem.remove();
                updateHiddenInput();
            };

            listItem.appendChild(label);
            listItem.appendChild(removeButton);

            selectedList.appendChild(listItem);
        });

        // Update the hidden input with pre-populated data
        updateHiddenInput();
    });

    function addHealthCondition() {
        const dropdown = document.getElementById('health_condition_dropdown');
        const selectedOption = dropdown.options[dropdown.selectedIndex];
        const conditionId = selectedOption.value;
        const conditionName = selectedOption.getAttribute('data-name');
        const conditionDesc = selectedOption.getAttribute('data-desc');

        // Validate selection
        if (!conditionId) {
            alert('Please select a valid health condition.');
            return;
        }

        const existingItem = document.querySelector(`#selected_health_conditions li[data-id="${conditionId}"]`);
        if (existingItem) {
            alert('This health condition is already added.');
            return;
        }

        // Create a new list item
        const listItem = document.createElement('li');
        listItem.setAttribute('data-id', conditionId);
        listItem.style.display = 'flex';
        listItem.style.alignItems = 'center';
        listItem.style.marginBottom = '10px';

        const label = document.createElement('span');
        label.textContent = `${conditionName} (${conditionDesc})`;
        label.style.flexGrow = '1';

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.textContent = 'Remove';
        removeButton.classList.add('btn', 'btn-danger', 'btn-sm');
        removeButton.style.marginLeft = '10px';
        removeButton.onclick = function () {
            listItem.remove();
            updateHiddenInput();
        };

        listItem.appendChild(label);
        listItem.appendChild(removeButton);

        const selectedList = document.getElementById('selected_health_conditions');
        selectedList.appendChild(listItem);

        updateHiddenInput();

        // Reset the dropdown selection
        dropdown.value = '';
    }

    function updateHiddenInput() {
        const selectedItems = document.querySelectorAll('#selected_health_conditions li');
        const selectedData = Array.from(selectedItems).map(item => {
            const name = item.querySelector('span').textContent.split('(')[0].trim();
            const desc = item.querySelector('span').textContent.split('(')[1]?.replace(')', '').trim();
            return {
                id: item.getAttribute('data-id'),
                name: name,
                desc: desc
            };
        });
        document.getElementById('health_conditions_input').value = JSON.stringify(selectedData);
    }
    
    document.addEventListener('DOMContentLoaded', function () {
        // Prepopulate existing medications
        const existingMedications = @json($user['medical_info']['medications'] ?? []);
        existingMedications.forEach((medication, index) => {
            addExistingMedication(medication, index);
        });
    });
    
    function checkForCustomMedication() {
        const dropdown = document.getElementById('medication_dropdown');
        const customFields = document.getElementById('customMedicationFields');
        customFields.style.display = dropdown.value === 'other' ? 'block' : 'none';
    }

    function addExistingMedication(medication, index) {
        const medicationList = document.getElementById('medication_list');
        const medicationId = Date.now() + index; // Unique ID for each medication entry

        const listItem = document.createElement('li');
        listItem.setAttribute('data-id', medicationId);
        listItem.style.marginBottom = '15px';
        listItem.style.padding = '15px';
        listItem.style.border = '1px solid #ddd';
        listItem.style.borderRadius = '8px';

        const isCustom = medication.custom_name && medication.custom_purpose;

        listItem.innerHTML = `
            <h5>${isCustom ? 'Custom Medication' : medication.name}</h5>
            <p>${isCustom ? `Name: ${medication.custom_name}` : `Purpose: ${medication.use}`}</p>
            ${isCustom ? `
                <input type="hidden" name="medications[${medicationId}][custom_name]" value="${medication.custom_name}">
                <input type="hidden" name="medications[${medicationId}][custom_purpose]" value="${medication.custom_purpose}">
            ` : `
                <input type="hidden" name="medications[${medicationId}][id]" value="${medication.id}">
                <input type="hidden" name="medications[${medicationId}][name]" value="${medication.name}">
                <input type="hidden" name="medications[${medicationId}][use]" value="${medication.use}">
            `}
            <label>Dosoooooge (e.g., 500mg):</label>
            <input type="text" name="medications[${medicationId}][dosage_info]" class="form-control" value="${medication.dosage_info}" required>

            <label>Total Pills:</label>
            <input type="number" name="medications[${medicationId}][total_pills]" class="form-control" value="${medication.total_pills}" required onchange="calculateEndDate(${medicationId})">

            <label>Pill Intake (per time):</label>
            <input type="number" name="medications[${medicationId}][pill_intake]" class="form-control" value="${medication.pill_intake}" required onchange="calculateEndDate(${medicationId})">

            <label>Frequency (times per day):</label>
            <select name="medications[${medicationId}][frequency]" class="form-control" onchange="adjustTimings(${medicationId}); calculateEndDate(${medicationId});">
                <option value="1" ${medication.frequency == 1 ? 'selected' : ''}>Once a day</option>
                <option value="2" ${medication.frequency == 2 ? 'selected' : ''}>Twice a day</option>
                <option value="3" ${medication.frequency == 3 ? 'selected' : ''}>Three times a day</option>
                <option value="4" ${medication.frequency == 4 ? 'selected' : ''}>Four times a day</option>
            </select>

            <div id="timings_${medicationId}" style="margin-top: 10px;"></div>

            <label>Start Date:</label>
            <input type="date" name="medications[${medicationId}][start_date]" class="form-control" value="${medication.start_date}" required onchange="calculateEndDate(${medicationId})">

            <p id="end_date_${medicationId}" style="margin-top: 10px;">Estimated End Date: ${medication.end_date || ''}</p>
            <input type="hidden" name="medications[${medicationId}][end_date]" value="${medication.end_date || ''}">

            <button type="button" class="btn btn-danger mt-2" onclick="this.parentElement.remove()">Remove</button>
        `;

        medication.times?.forEach((time, i) => {
            const timingContainer = document.createElement('div');
            timingContainer.innerHTML = `
                <label>Time ${i + 1}:</label>
                <input type="time" name="medications[${medicationId}][times][${i}]" class="form-control" value="${time}" required>
            `;
            listItem.querySelector(`#timings_${medicationId}`).appendChild(timingContainer);
        });

        medicationList.appendChild(listItem);

    // Disable the selected medication in the dropdown if the dates are valid
        const startDateInput = listItem.querySelector(`input[name="medications[${medicationId}][start_date]"]`);
        const endDateInput = listItem.querySelector(`input[name="medications[${medicationId}][end_date]"]`);

        startDateInput.addEventListener('change', function () {
            validateMedicationDates(startDateInput, endDateInput, selectedOption);
        });

        endDateInput.addEventListener('change', function () {
            validateMedicationDates(startDateInput, endDateInput, selectedOption);
        });

        validateMedicationDates(startDateInput, endDateInput, selectedOption);
    }


    function addMedication() {
        const dropdown = document.getElementById('medication_dropdown');
        const medicationList = document.getElementById('medication_list');
        const medicationId = Date.now(); // Unique ID for each medication entry

        const selectedOption = dropdown.options[dropdown.selectedIndex];
        const selectedMedicationId = dropdown.value; // Get the selected medication ID
        const selectedMedicationName = selectedOption.getAttribute('data-name');
        const selectedMedicationPurpose = selectedOption.getAttribute('data-purpose');

        if (!selectedMedicationId) {
            alert('Please select a medication or choose "Other" for custom medication.');
            return;
        }

        // Create a new medication list item
        const listItem = document.createElement('li');
        listItem.setAttribute('data-id', medicationId);
        listItem.style.marginBottom = '15px';
        listItem.style.padding = '15px';
        listItem.style.border = '1px solid #ddd';
        listItem.style.borderRadius = '8px';

        if (selectedMedicationId === 'other') {
            // If "Other" is selected, show custom medication fields
            const customName = document.getElementById('custom_medication_name').value.trim();
            const customPurpose = document.getElementById('custom_medication_purpose').value.trim();

            if (!customName || !customPurpose) {
                alert('Please enter the custom medication name and purpose.');
                return;
            }

            listItem.innerHTML = `
                <h5>Custom Medication</h5>
                <p>Name: ${customName}</p>
                <p>Purpose: ${customPurpose}</p>
                <input type="hidden" name="medications[${medicationId}][custom_name]" value="${customName}">
                <input type="hidden" name="medications[${medicationId}][custom_purpose]" value="${customPurpose}">
            `;
        } else {
            // If a medication is selected from the dropdown
            listItem.innerHTML = `
                <h5>${selectedMedicationName}</h5>
                <p>Purpose: ${selectedMedicationPurpose}</p>
                <input type="hidden" name="medications[${medicationId}][id]" value="${selectedMedicationId}">
            `;
        }

        // Add common medication fields (dosage, total pills, frequency, etc.)
        listItem.innerHTML += `
            <label>Dosage (e.g., 500mg):</label>
            <input type="text" name="medications[${medicationId}][dosage_info]" class="form-control" required>

            <label>Total Pills:</label>
            <input type="number" name="medications[${medicationId}][total_pills]" class="form-control" required onchange="calculateEndDate(${medicationId})">

            <label>Pill Intake (per time):</label>
            <input type="number" name="medications[${medicationId}][pill_intake]" class="form-control" required onchange="calculateEndDate(${medicationId})">

            <label>Frequency (times per day):</label>
            <select name="medications[${medicationId}][frequency]" class="form-control" onchange="adjustTimings(${medicationId}); calculateEndDate(${medicationId});">
                <option value="1">Once a day</option>
                <option value="2">Twice a day</option>
                <option value="3">Three times a day</option>
                <option value="4">Four times a day</option>
            </select>

            <div id="timings_${medicationId}" style="margin-top: 10px;"></div>

            <label>Start Date:</label>
            <input type="date" name="medications[${medicationId}][start_date]" class="form-control" required onchange="calculateEndDate(${medicationId})">

            <p id="end_date_${medicationId}" style="margin-top: 10px;"></p>

            <button type="button" class="btn btn-danger mt-2" onclick="this.parentElement.remove()">Remove</button>
        `;

        medicationList.appendChild(listItem);
        dropdown.value = ''; // Reset the dropdown
        document.getElementById('customMedicationFields').style.display = 'none'; // Hide custom fields
    
        
        // Disable the selected medication in the dropdown if the dates are valid
        const startDateInput = listItem.querySelector(`input[name="medications[${medicationId}][start_date]"]`);
        const endDateInput = listItem.querySelector(`input[name="medications[${medicationId}][end_date]"]`);

        startDateInput.addEventListener('change', function () {
            validateMedicationDates(startDateInput, endDateInput, selectedOption);
        });

        endDateInput.addEventListener('change', function () {
            validateMedicationDates(startDateInput, endDateInput, selectedOption);
        });
    }


function validateMedicationDates(startDateInput, endDateInput, selectedOption) {
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);
    const currentDate = new Date();

    if (startDate && endDate && startDate <= endDate && startDate >= currentDate) {
        selectedOption.disabled = true;
    } else {
        selectedOption.disabled = false;
    }
}

    function adjustTimings(medicationId) {
        const timingContainer = document.getElementById(`timings_${medicationId}`);
        const frequencyInput = document.querySelector(`select[name="medications[${medicationId}][frequency]"]`);
        const frequency = parseInt(frequencyInput.value);

        timingContainer.innerHTML = ''; // Clear existing timings

        for (let i = 0; i < frequency; i++) {
            const timingLabel = document.createElement('label');
            timingLabel.textContent = `Time ${i + 1}:`;
            timingLabel.style.display = 'block';

            const timingInput = document.createElement('input');
            timingInput.type = 'time';
            timingInput.name = `medications[${medicationId}][times][${i}]`;
            timingInput.classList.add('form-control');

            timingContainer.appendChild(timingLabel);
            timingContainer.appendChild(timingInput);
        }
    }

   
    function calculateEndDate(medicationId) {
        const totalPillsInput = document.querySelector(`input[name="medications[${medicationId}][total_pills]"]`);
        const pillIntakeInput = document.querySelector(`input[name="medications[${medicationId}][pill_intake]"]`);
        const frequencyInput = document.querySelector(`select[name="medications[${medicationId}][frequency]"]`);
        const startDateInput = document.querySelector(`input[name="medications[${medicationId}][start_date]"]`);
        const endDateField = document.getElementById(`end_date_${medicationId}`);

        const totalPills = parseInt(totalPillsInput.value) || 0;
        const pillIntake = parseInt(pillIntakeInput.value) || 1; // Default to 1 pill per time
        const frequency = parseInt(frequencyInput.value) || 1;
        const startDate = new Date(startDateInput.value);

        if (totalPills > 0 && pillIntake > 0 && frequency > 0 && startDateInput.value) {
            const dosesPerDay = frequency * pillIntake; // Total pills taken per day
            const daysAvailable = Math.floor(totalPills / dosesPerDay); // Calculate number of days the medication will last
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + daysAvailable);

            endDateField.textContent = `Estimated End Date: ${endDate.toDateString()}`;
        } else {
            endDateField.textContent = ''; // Clear if inputs are incomplete
        }
    }


function adjustTimings(medicationId) {
    const timingContainer = document.getElementById(`timings_${medicationId}`);
    const frequencyInput = document.querySelector(`select[name="medications[${medicationId}][frequency]"]`);
    const frequency = parseInt(frequencyInput.value);

    timingContainer.innerHTML = ''; // Clear existing timings

    for (let i = 0; i < frequency; i++) {
        const timingLabel = document.createElement('label');
        timingLabel.textContent = `Time ${i + 1}:`;
        timingLabel.style.display = 'block';

        const timingInput = document.createElement('input');
        timingInput.type = 'time';
        timingInput.name = `medications[${medicationId}][times][${i}]`;
        timingInput.classList.add('form-control');

        timingContainer.appendChild(timingLabel);
        timingContainer.appendChild(timingInput);
    }
}

    function toggleSections() {
        const role = document.getElementById('role').value;
        const commonSections = document.getElementById('commonSections');
        const clientSection = document.getElementById('clientSection');

        if (role === 'Admin' || role === 'Caregiver') {
            console.log('Selected Role: Admin or Caregiver');
            commonSections.style.display = 'block';
            clientSection.style.display = 'none';
        } else if (role === 'Client') {
            console.log('Selected Role: Client');
            commonSections.style.display = 'block';
            clientSection.style.display = 'block';
        } else {
            console.log('Selected Role: None');
            commonSections.style.display = 'none';
            clientSection.style.display = 'none';
        }
    }

    function toggleAllergyFields() {
        const allergic = document.getElementById('allergic').value;
        document.getElementById('allergyFields').style.display = allergic === 'yes' ? 'block' : 'none';
    }

    // Show the Purpose of the medication when it's selected
    function showMedicationPurpose() {
        const medicationId = document.getElementById('medications').value;
        const purposeField = document.getElementById('medication_purpose');
        const medicationDetails = document.getElementById('medicationDetails');

        // Fetch the medication data based on the selected medication
        const medications = @json($medications);
        const selectedMedication = medications.find(medication => medication.id == medicationId);

        if (selectedMedication) {
            // Fill the Purpose
            purposeField.value = selectedMedication.purpose;

            // Show the Dosage and Frequency fields
            medicationDetails.style.display = 'block';
        } else {
            purposeField.value = '';
            medicationDetails.style.display = 'none';
        }
    }

 
    let cropper;
    const profilePhotoInput = document.getElementById('profile_photo');
    const cropperContainer = document.getElementById('cropper-container');
    const cropImage = document.getElementById('crop-image');
    const cropActions = document.getElementById('crop-actions');
    const cropBtn = document.getElementById('crop-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const croppedImagePathInput = document.getElementById('cropped_image_path');

    profilePhotoInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                cropImage.src = e.target.result;
                cropperContainer.style.display = 'block';
                cropActions.style.display = 'block';

                // Initialize Cropper.js
                if (cropper) cropper.destroy(); // Destroy any existing cropper instance
                cropper = new Cropper(cropImage, {
                    aspectRatio: 1, // 1:1 aspect ratio
                    viewMode: 1,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    cropBtn.addEventListener('click', function () {
        if (cropper) {
            cropper.getCroppedCanvas().toBlob(function (blob) {
                const formData = new FormData();
                formData.append('cropped_image', blob, 'cropped_image.jpg');

                fetch('{{ route("upload.cropped.image") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Cropped image uploaded successfully!');
                            croppedImagePathInput.value = data.image_path; // Save the uploaded image path
                        } else {
                            alert('Failed to upload cropped image.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while uploading the cropped image.');
                    });
            });
        }
    });

    cancelBtn.addEventListener('click', function () {
        cropperContainer.style.display = 'none';
        cropActions.style.display = 'none';
        cropper.destroy();
        cropper = null;
        profilePhotoInput.value = '';
    });


    

</script>

 <style>
        table {
                width: 100%;
                margin-top: 20px;
                border-collapse: collapse;
                font-family: Arial, sans-serif;
            }

            th {
                background-color: #f8f9fa;
                color: #333;
                font-weight: bold;
                text-align: left;
                padding: 10px;
                border-bottom: 2px solid #ddd;
            }

            td {
                padding: 10px;
                border-bottom: 1px solid #ddd;
            }

            tbody tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tbody tr:hover {
                background-color: #f1f1f1;
            }

            p {
                font-style: italic;
                color: #777;
            }

            /* Form Container */
            .form-container {
                max-width: 800px;
                margin: 40px auto;
                background: #f8f9fa; /* Light gray background */
                padding: 20px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                border: 1px solid #e3e3e3;
                font-family: 'Arial', sans-serif;
            }

            /* Form Header */
            .form-header {
                background: #0056b3; /* Darker blue */
                color: white;
                padding: 15px;
                text-align: center;
                border-radius: 10px 10px 0 0;
                font-size: 18px;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            /* Form Control */
            .form-control {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 14px;
                color: #333;
                background-color: white;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
                transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            }

            .form-control:focus {
                border-color: #007bff;
                box-shadow: 0 0 4px rgba(0, 123, 255, 0.25);
            }

            /* Buttons */
            .btn-primary {
                background-color: #007bff;
                border: none;
                padding: 8px 15px;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
                transition: background-color 0.2s ease-in-out;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .btn-primary:hover {
                background-color: #0056b3;
            }

            .btn-success {
                background-color: #28a745;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                color: white;
                cursor: pointer;
                font-size: 14px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: background-color 0.2s ease-in-out;
            }

            .btn-success:hover {
                background-color: #218838;
            }

            .btn-danger {
                background-color: #dc3545;
                border: none;
                color: white;
                padding: 5px 10px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 13px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: background-color 0.2s ease-in-out;
            }

            .btn-danger:hover {
                background-color: #c82333;
            }

            /* Table */
            .form-table {
                width: 100%;
                margin-bottom: 20px;
                border-collapse: collapse;
            }

            .form-table td {
                padding: 10px;
                vertical-align: top;
            }

            /* Labels */
            label {
                font-size: 14px;
                font-weight: 600;
                color: #333;
                margin-bottom: 5px;
                display: block;
            }

            /* Selected Items (Health Conditions List) */
            #selected_health_conditions li {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 10px;
                padding: 10px;
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                font-size: 14px;
                color: #333;
            }

            #selected_health_conditions li span {
                flex-grow: 1;
                font-size: 14px;
                color: #333;
                font-weight: 500;
            }

            /* Dropdown Styling */
            #health_condition_dropdown,
            #medications {
                background: #ffffff;
                color: #333;
                font-size: 14px;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
                transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            }

            #health_condition_dropdown:focus,
            #medications:focus {
                border-color: #007bff;
                box-shadow: 0 0 4px rgba(0, 123, 255, 0.25);
            }

            /* Section Titles */
            h4 {
                font-size: 16px;
                font-weight: bold;
                color: #0056b3;
                margin-bottom: 10px;
                border-bottom: 2px solid #ccc;
                padding-bottom: 5px;
            }

            /* General Spacing */
            .mt-3 {
                margin-top: 15px;
            }

            #medication_list li {
                display: block;
                margin-bottom: 15px;
                padding: 15px;
                background-color: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            }

            #medication_list h5 {
                font-size: 16px;
                font-weight: bold;
                color: #333;
                margin-bottom: 10px;
            }

            #medication_list label {
                font-size: 14px;
                font-weight: 600;
                color: #555;
            }
</style>
@endsection
