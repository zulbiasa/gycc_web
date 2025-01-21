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
                    <label for="allergic">Allergic:</label>
                    <select name="allergic" id="allergic" class="form-control" onchange="toggleAllergyFields()">
                        <option value="no" {{ old('allergic', isset($user['medical_info']['allergic']) ? 'yes' : 'no') === 'no' ? 'selected' : '' }}>No</option>
                        <option value="yes" {{ old('allergic', isset($user['medical_info']['allergic']) ? 'yes' : 'no') === 'yes' ? 'selected' : '' }}>Yes</option>
                    </select>

                    <div id="allergyFields" style="{{ old('allergic', isset($user['medical_info']['allergic']) ? 'yes' : 'no') === 'yes' ? 'display: block;' : 'display: none;' }}">
                        <label for="food_allergy">Food Allergy:</label>
                        <input type="text" name="food_allergy" id="food_allergy" class="form-control" value="{{ old('food_allergy', $user['medical_info']['allergic']['food'] ?? '') }}">

                        <label for="medicine_allergy">Medicine Allergy:</label>
                        <input type="text" name="medicine_allergy" id="medicine_allergy" class="form-control" value="{{ old('medicine_allergy', $user['medical_info']['allergic']['medicine'] ?? '') }}">
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
                            @if (isset($user['medical_info']['healthConditions']))
                                @foreach ($user['medical_info']['healthConditions'] as $condition)
                                    <li data-id="{{ $condition['id'] }}">
                                        <span>{{ $condition['name'] }} ({{ $condition['desc'] }})</span>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove(); updateHiddenInput();">Remove</button>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <!-- Hidden Input for Health Conditions -->
                        <input type="hidden" name="health_conditions" id="health_conditions_input" value="{{ isset($user['medical_info']['healthConditions']) ? json_encode($user['medical_info']['healthConditions']) : '' }}">
                    </div>

                    <!-- Medications -->
                    <div class="form-group">
                        <label for="medications">Medications:</label>
                        <div style="display: flex; align-items: center;">
                            <select id="medication_dropdown" class="form-control" style="width: 70%;">
                                <option value="">-- Select Medication --</option>
                                @foreach ($medications as $medication)
                                    <option value="{{ $medication['id'] }}" data-name="{{ $medication['name'] }}" data-purpose="{{ $medication['use'] }}">
                                        {{ $medication['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" onclick="addMedication()" style="margin-left: 10px;">Add Medication</button>
                        </div>
                        <!-- Medications List -->
                        <ul id="medication_list" style="margin-top: 15px; list-style: none; padding-left: 0;">
                            @foreach ($user['medical_info']['medications'] ?? [] as $medicationId => $medication)
                                @if (!empty($medication) && isset($medication['name'], $medication['purpose'], $medication['dosage'], $medication['frequency']))
                                    <li data-id="{{ $medicationId }}">
                                        <h5>{{ $medication['name'] }}</h5>
                                        <input type="hidden" name="medications[{{ $medicationId }}][name]" value="{{ $medication['name'] }}">
                                        <label>Purpose:</label>
                                        <input type="text" name="medications[{{ $medicationId }}][purpose]" value="{{ $medication['purpose'] }}" class="form-control">
                                        <label>Dosage:</label>
                                        <input type="text" name="medications[{{ $medicationId }}][dosage]" value="{{ $medication['dosage'] }}" class="form-control">
                                        <label>Frequency:</label>
                                        <input type="text" name="medications[{{ $medicationId }}][frequency]" value="{{ $medication['frequency'] }}" class="form-control">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove();">Remove</button>
                                    </li>
                                @endif
                            @endforeach
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
        listItem.style.padding = '10px';
        listItem.style.background = '#ffffff';
        listItem.style.border = '1px solid #ddd';
        listItem.style.borderRadius = '8px';
        listItem.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
        listItem.style.fontSize = '14px';
        listItem.style.color = '#333';

        const label = document.createElement('span');
        label.textContent = `${conditionName} (${conditionDesc})`;
        label.style.flexGrow = '1';
        label.style.fontWeight = '500';

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

    // Function to update hidden input for health conditions
    function updateHiddenInput() {
        const selectedItems = document.querySelectorAll('#selected_health_conditions li');
        const selectedData = Array.from(selectedItems).map(item => {
            const text = item.querySelector('span').textContent;
            const name = text.split('(')[0].trim();
            const desc = text.split('(')[1]?.replace(')', '').trim() || '';
            return {
                id: item.getAttribute('data-id'),
                name: name,
                desc: desc
            };
        });
        document.getElementById('health_conditions_input').value = JSON.stringify(selectedData);
    }

    // Function to add Medication
    function addMedication() {
        const dropdown = document.getElementById('medication_dropdown');
        const selectedOption = dropdown.options[dropdown.selectedIndex];
        const medicationId = selectedOption.value;
        const medicationName = selectedOption.getAttribute('data-name');
        const medicationPurpose = selectedOption.getAttribute('data-purpose');

        // Validate selection
        if (!medicationId) {
            alert('Please select a valid medication.');
            return;
        }

        const existingItem = document.querySelector(`#medication_list li[data-id="${medicationId}"]`);
        if (existingItem) {
            alert('This medication is already added.');
            return;
        }

        // Create a new list item
        const listItem = document.createElement('li');
        listItem.setAttribute('data-id', medicationId);
        listItem.style.display = 'block';
        listItem.style.marginBottom = '15px';
        listItem.style.padding = '15px';
        listItem.style.backgroundColor = '#f8f9fa';
        listItem.style.border = '1px solid #ddd';
        listItem.style.borderRadius = '8px';
        listItem.style.boxShadow = '0px 2px 4px rgba(0, 0, 0, 0.1)';

        // Medication Name Title
        const title = document.createElement('h5');
        title.textContent = medicationName;
        title.style.fontSize = '16px';
        title.style.marginBottom = '10px';

        // Hidden Input for Medication Name
        const nameInput = document.createElement('input');
        nameInput.type = 'hidden';
        nameInput.value = medicationName;
        nameInput.name = `medications[${medicationId}][name]`;

        // Purpose Field
        const purposeLabel = document.createElement('label');
        purposeLabel.textContent = 'Purpose:';
        purposeLabel.style.display = 'block';
        purposeLabel.style.fontSize = '14px';
        purposeLabel.style.marginBottom = '5px';

        const purposeInput = document.createElement('input');
        purposeInput.type = 'text';
        purposeInput.value = medicationPurpose;
        purposeInput.name = `medications[${medicationId}][purpose]`;
        purposeInput.classList.add('form-control');
        purposeInput.readOnly = true;

        // Dosage Field
        const dosageLabel = document.createElement('label');
        dosageLabel.textContent = 'Dosage:';
        dosageLabel.style.display = 'block';
        dosageLabel.style.fontSize = '14px';
        dosageLabel.style.marginTop = '10px';
        dosageLabel.style.marginBottom = '5px';

        const dosageInput = document.createElement('input');
        dosageInput.type = 'text';
        dosageInput.name = `medications[${medicationId}][dosage]`;
        dosageInput.classList.add('form-control');
        dosageInput.placeholder = 'Enter dosage';

        // Frequency Field
        const frequencyLabel = document.createElement('label');
        frequencyLabel.textContent = 'Frequency:';
        frequencyLabel.style.display = 'block';
        frequencyLabel.style.fontSize = '14px';
        frequencyLabel.style.marginTop = '10px';
        frequencyLabel.style.marginBottom = '5px';

        const frequencyInput = document.createElement('input');
        frequencyInput.type = 'text';
        frequencyInput.name = `medications[${medicationId}][frequency]`;
        frequencyInput.classList.add('form-control');
        frequencyInput.placeholder = '3 Times a Day...';

        // Remove Button
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.textContent = 'Remove';
        removeButton.classList.add('btn', 'btn-danger', 'btn-sm');
        removeButton.style.marginTop = '10px';
        removeButton.onclick = function () {
            listItem.remove();
        };

        // Append all fields to the list item
        listItem.appendChild(title);
        listItem.appendChild(nameInput); // Hidden input for name
        listItem.appendChild(purposeLabel);
        listItem.appendChild(purposeInput);
        listItem.appendChild(dosageLabel);
        listItem.appendChild(dosageInput);
        listItem.appendChild(frequencyLabel);
        listItem.appendChild(frequencyInput);
        listItem.appendChild(removeButton);

        // Append the list item to the medication list
        const medicationList = document.getElementById('medication_list');
        medicationList.appendChild(listItem);

        // Reset the dropdown selection
        dropdown.value = '';
    }

    // Function to toggle sections based on role
    function toggleSections() {
        const role = document.getElementById('role').value;
        const medicalSection = document.getElementById('medicalSection');

        if (role === 'Client') {
            medicalSection.style.display = 'block';
        } else {
            medicalSection.style.display = 'none';
        }
    }

    // Function to toggle allergy fields
    function toggleAllergyFields() {
        const allergic = document.getElementById('allergic').value;
        const allergyFields = document.getElementById('allergyFields');
        
        if (allergic === 'yes') {
            allergyFields.style.display = 'block'; // Show fields if "Yes" is selected
        } else {
            allergyFields.style.display = 'none'; // Hide fields if "No" is selected
        }
    }
    // Initialize the form based on existing data
    document.addEventListener('DOMContentLoaded', function() {
        toggleSections();
        toggleAllergyFields();
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
