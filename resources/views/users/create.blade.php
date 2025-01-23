@extends('layouts.app')

@section('title', 'Register New User')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="form-container">
    <div class="form-header">
        <h3>Register New User</h3>
    </div>
    <div class="form-body">
        @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: '{{ $errors->first() }}', // Display the first error message
                confirmButtonText: 'OK'
            }).then(() => {
                // Find the first invalid field
                const errorField = document.querySelector('[name="{{ array_key_first($errors->toArray()) }}"]');
                
                // Clear the field value if found
                if (errorField) {
                    errorField.value = ''; // Clear the value
                    errorField.focus(); // Focus on the field for user attention
                }
            });
        </script>
        @endif

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <table class="form-table">
                <!-- Role Selection -->
                <tr>
                    <td><label for="role">Role:</label></td>
                    <td>
                        <select name="role" id="role" class="form-control" onchange="toggleSections()" required>
                            <option value="">-- Select Role --</option>
                            <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Caregiver" {{ old('role') === 'Caregiver' ? 'selected' : '' }}>Caregiver</option>
                            <option value="Client" {{ old('role') === 'Client' ? 'selected' : '' }}>Client</option>
                        </select>
                    </td>
                </tr>
            </table>

            <!-- Common Sections (Role's Profile, App Authentication, Authorized Contact Person) -->
    
    <div id="commonSections" style="display: none;">
        <div class="form-container">
            <br><br>
                <h4>Role's Profile</h4>
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>

                <label for="ic_no">IC Number:</label>
                <input type="text" name="ic_no" id="ic_no" class="form-control" value="{{ old('ic_no') }}" required>

                <label for="phone_no">Phone Number:</label>
                <input type="text" name="phone_no" id="phone_no" class="form-control" value="{{ old('phone_no') }}" required>

                <label for="dob">Date of Birth:</label>
                <input type="text" name="dob" id="dob" class="form-control" style="background-color: transparent; box-shadow: none; border: none;" value="{{ old('dob') }}" readonly>

                <div class="mb-3">
                <label for="poscode" class="form-label">Postcode</label>
                <input type="text" class="form-control" id="poscode" name="poscode" value="{{ old('poscode') }}" required>
                </div>

                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" style="background-color: transparent; box-shadow: none; border: none;" id="city" name="city" value="{{ old('city') }}" readonly>
                </div>
                
                <div class="mb-3">
                    <label for="state" class="form-label">State</label>
                    <input type="text" class="form-control" style="background-color: transparent; box-shadow: none; border: none;" id="state" name="state" value="{{ old('state') }}" readonly>
                </div>

                <div class="mb-3">
                <label for="Address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                </div>

                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>

                <label for="gender">Gender:</label>
                <input name="gender" id="gender" class="form-control" style="background-color: transparent; box-shadow: none; border: none;" value="{{ old('gender') }}" readonly>
                </input>

                <label for="profile_photo">Profile Photo:</label>
                <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept="image/*">

                <!-- Image Preview and Cropping Area -->
                <div id="cropper-container" style="display: none;">
                    <img id="crop-image" style="max-width: 100%;" alt="Profile Photo">
                </div>

                <!-- Buttons for Cropping -->
                <div id="crop-actions" style="display: none;">
                    <button id="crop-btn" type="button" class="btn btn-primary">Crop & Upload</button>
                    <button id="cancel-btn" type="button" class="btn btn-secondary">Cancel</button>
                </div>

                <!-- Hidden Input to Store Uploaded Image Path -->
                <input type="hidden" name="cropped_image_path" id="cropped_image_path">
        </div>
        
        <div class="form-container">
                <h4>App Authentication</h4>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>

                <label for="password_confirmation">Re-enter Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="form-container">
            <h4>Authorized Contact Person</h4>
            <label for="contact_name">Name:</label>
            <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{ old('contact_name') }}">

            <label for="contact_ic">IC Number:</label>
            <input type="text" name="contact_ic" id="contact_ic" class="form-control" value="{{ old('contact_ic') }}">

            <label for="contact_relationship">Relationship:</label>
            <input type="text" name="contact_relationship" id="contact_relationship" class="form-control" value="{{ old('contact_relationship') }}">

            <label for="contact_phone_no">Phone Number:</label>
            <input type="text" name="contact_phone_no" id="contact_phone_no" class="form-control" value="{{ old('contact_phone_no') }}">
        </div>

        <div id="clientSection" style="display: none;">
            <div class="form-container">
                <h4>Medical & Health Information</h4>
                <!-- Blood Type Dropdown -->
                <label for="blood_type">Blood Type:</label>
                <select name="blood_type" id="blood_type" class="form-control">
                    <option value="">-- Select Blood Type --</option>
                    <option value="A+" {{ old('blood_type') === 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="A-" {{ old('blood_type') === 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="B+" {{ old('blood_type') === 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="B-" {{ old('blood_type') === 'B-' ? 'selected' : '' }}>B-</option>
                    <option value="AB+" {{ old('blood_type') === 'AB+' ? 'selected' : '' }}>AB+</option>
                    <option value="AB-" {{ old('blood_type') === 'AB-' ? 'selected' : '' }}>AB-</option>
                    <option value="O+" {{ old('blood_type') === 'O+' ? 'selected' : '' }}>O+</option>
                    <option value="O-" {{ old('blood_type') === 'O-' ? 'selected' : '' }}>O-</option>
                </select>

                  <!-- Weight Field -->
                <label for="weight">Weight (kg):</label>
                <input type="number" step="0.1" name="weight" id="weight" class="form-control" value="{{ old('weight') }}">

                <!-- Height Field -->
                <label for="height">Height (cm):</label>
                <input type="number" step="0.1" name="height" id="height" class="form-control" value="{{ old('height') }}">

                <!-- Allergic -->  
                <label for="allergic">Has Allergies:</label>
                <select name="allergic" id="allergic" class="form-control" onchange="toggleAllergyFields()">
                    <option value="no" {{ old('allergic') === 'no' ? 'selected' : '' }}>No</option>
                    <option value="yes" {{ old('allergic') === 'yes' ? 'selected' : '' }}>Yes</option>
                </select>

                <div id="allergyFields" style="display: none;">
                    <label for="food_allergy">Food Allergies:</label>
                    <input type="text" name="food_allergy" id="food_allergy" class="form-control" value="{{ old('food_allergy') }}">

                    <label for="medicine_allergy">Medicine Allergies:</label>
                    <input type="text" name="medicine_allergy" id="medicine_allergy" class="form-control" value="{{ old('medicine_allergy') }}">
                </div>
                <br>
              <!-- Health Conditions Dropdown -->
                <div class="form-group">
                    <label for="health_conditions">Health Conditions:</label>
                    <div style="display: flex; align-items: center;">
                        <select id="health_condition_dropdown" class="form-control" style="width: 70%;">
                            <option value="">-- Select Health Condition --</option>
                            @foreach ($healthConditions as $condition)
                                <option value="{{ $condition['id'] }}" data-name="{{ $condition['name'] }}" data-desc="{{ $condition['desc'] }}">
                                    {{ $condition['name'] }} ({{ $condition['desc'] }})
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" onclick="addHealthCondition()" style="margin-left: 10px;">+</button>
                    </div>
                    <!-- Selected Health Conditions List -->
                    <ul id="selected_health_conditions" style="margin-top: 15px; list-style: none; padding-left: 0;">
                        <!-- Dynamically added conditions will appear here -->
                    </ul>
                    <!-- Hidden Input for Health Conditions -->
                    <input type="hidden" name="health_conditions" id="health_conditions_input">
                </div>
                <script>
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
                            return {
                                id: item.getAttribute('data-id'),
                                name: item.textContent.split('(')[0].trim(),
                                desc: item.textContent.split('(')[1]?.replace(')', '').trim()
                            };
                        });
                        document.getElementById('health_conditions_input').value = JSON.stringify(selectedData);
                    }
                </script>
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
                            <button type="button" class="btn btn-primary" onclick="addMedication()" style="margin-left: 10px;">Add Medication</button>
                        </div>
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
                        </ul>
                    </div>
                <!-- Physical Condition Dropdown -->
                <label for="physical_condition">Physical Condition:</label>
                <select name="physical_condition" id="physical_condition" class="form-control" >
                    <option value="">-- Select Physical Condition --</option>
                    <option value="Good" {{ old('physical_condition') === 'Good' ? 'selected' : '' }}>Good</option>
                    <option value="Weak" {{ old('physical_condition') === 'Weak' ? 'selected' : '' }}>Weak</option>
                    <option value="Bedridden" {{ old('physical_condition') === 'Bedridden' ? 'selected' : '' }}>Bedridden</option>
                </select>
                <!-- Basic Needs Dropdown -->
                <label for="basic_needs">Basic Needs:</label>
                <select name="basic_needs" id="basic_needs" class="form-control">
                    <option value="None" {{ old('basic_needs') === 'None' ? 'selected' : '' }}>None</option>
                    <option value="Wheelchair" {{ old('basic_needs') === 'Wheelchair' ? 'selected' : '' }}>Wheelchair</option>
                    <option value="Hearing Aid" {{ old('basic_needs') === 'Hearing Aid' ? 'selected' : '' }}>Hearing Aid</option>
                    <option value="Walking Stick" {{ old('basic_needs') === 'Walking Stick' ? 'selected' : '' }}>Walking Stick</option>
                    <option value="Walker" {{ old('basic_needs') === 'Walker' ? 'selected' : '' }}>Walker</option>
                </select>
            </div>
        </div>
    </div>
    
            <!-- Medical & Health Information Section (for Client only) -->
        
       

            <button type="submit" class="btn btn-success mt-3">Save</button>
        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/cropperjs/dist/cropper.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/cropperjs"></script>
<script>
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
            return {
                id: item.getAttribute('data-id'),
                name: item.textContent.split('(')[0].trim(),
                desc: item.textContent.split('(')[1]?.replace(')', '').trim()
            };
        });
        document.getElementById('health_conditions_input').value = JSON.stringify(selectedData);
    }

    function checkForCustomMedication() {
        const dropdown = document.getElementById('medication_dropdown');
        const customFields = document.getElementById('customMedicationFields');
        customFields.style.display = dropdown.value === 'other' ? 'block' : 'none';
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


    function toggleCustomFields(medicationId) {
    const dropdown = document.querySelector(`select[name="medications[${medicationId}][id]"]`);
    const customFields = document.getElementById(`customFields_${medicationId}`);
    
    if (dropdown.value === 'custom') {
        customFields.style.display = 'block';
    } else {
        customFields.style.display = 'none';
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

    document.getElementById('ic_no').addEventListener('input', function () {
    const icNo = this.value.replace(/-/g, '');
    if (icNo.length >= 6) {
        const year = parseInt(icNo.substring(0, 2), 10);
        const month = parseInt(icNo.substring(2, 4), 10);
        const day = parseInt(icNo.substring(4, 6), 10);

        const fullYear = year > new Date().getFullYear() % 100 ? 1900 + year : 2000 + year;
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ];

        const isValidDate = !isNaN(new Date(`${fullYear}-${month}-${day}`).getTime());
        if (isValidDate && month >= 1 && month <= 12 && day >= 1 && day <= 31) {
            document.getElementById('dob').value = `${day} ${monthNames[month - 1]} ${fullYear}`;
        } else {
            document.getElementById('dob').value = '';
        }
    } else {
        document.getElementById('dob').value = '';
    }
});
</script>

<script>
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

    document.getElementById('ic_no').addEventListener('input', function() {
    const icNo = this.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters

    if (icNo.length >= 12) {
        const lastDigit = parseInt(icNo.charAt(11), 10); // Get the last digit
        const genderSelect = document.getElementById('gender');

        // Set gender based on the last digit ONLY if it hasn't been set manually
        if (genderSelect.value === '' || genderSelect.value === 'Male' || genderSelect.value === 'Female') { 
            genderSelect.value = lastDigit % 2 === 0 ? 'Female' : 'Male';
        }
    }
});
document.getElementById('poscode').addEventListener('input', function () {
    const poscode = this.value.trim(); // Remove leading/trailing spaces
    const cityField = document.getElementById('city');
    const stateField = document.getElementById('state');

    // Stop if the postcode is empty or less than 5 digits
    if (poscode.length < 5) {
        cityField.value = '';
        stateField.value = '';
        return;
    }

    // Fetch the JSON data
    fetch('/poscodes')
        .then(response => response.json())
        .then(data => {
            let found = false;

            // Search for the postcode in the JSON
            data.state.forEach(state => {
                state.city.forEach(city => {
                    if (city.postcode.includes(poscode)) {
                        cityField.value = city.name;
                        stateField.value = state.name;
                        found = true;
                    }
                });
            });

            if (!found) {
                // Clear fields if no match found
                cityField.value = '';
                stateField.value = '';
            }
        })
        .catch(error => console.error('Error fetching postcode data:', error));
});

</script>

<style>
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
