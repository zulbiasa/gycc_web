@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="form-container">
    <div class="form-header">
        <h3>Edit My Account</h3>
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

        <form action="{{ route('myaccount.update', $user['id']) }}" method="POST">
            @csrf
            @method('PUT') <!-- Method Spoofing for PUT -->
            
            <!-- Hidden field for user_id -->
            <input type="hidden" name="user_id" value="{{ $user['id'] }}">

            <table class="form-table">
                <!-- Role Selection -->
                <tr>
                    <td><label for="role">Role:</label></td>
                    <td>
                        <select name="role" id="role" class="form-control" onchange="toggleSections()" required>
                            <option value="Admin" {{ $user['role_name'] === 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Caregiver" {{ $user['role_name'] === 'Caregiver' ? 'selected' : '' }}>Caregiver</option>
                            <!-- <option value="Client" {{ $user['role_name'] === 'Client' ? 'selected' : '' }}>Client</option> -->
                        </select>
                    </td>
                </tr>
            </table>

            <!-- Common Sections -->
            <div id="commonSections">
                <div class="form-container">
                    <h4>Role's Profile</h4>
                    <label for="name">Full Name:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user['name']) }}" required>

                    <label for="ic_no">IC Number:</label>
                    <input type="text" name="ic_no" id="ic_no" class="form-control" value="{{ old('ic_no', $user['ic_no']) }}" required>

                    <label for="phone_no">Phone Number:</label>
                    <input type="text" name="phone_no" id="phone_no" class="form-control" value="{{ old('phone_no', $user['phone_no']) }}" required>

                    <label for="dob">Date of Birth:</label>
                    <input type="text" name="dob" id="dob" class="form-control" value="{{ $user['formatted_dob'] }}" disabled>

                    <label for="home_address">Home Address:</label>
                    <textarea name="home_address" id="home_address" class="form-control" required>{{ old('home_address', $user['home_address']) }}</textarea>

                    <label for="status">Status:</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="1" {{ old('status', $user['status']) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !old('status', $user['status']) ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <label for="gender">Gender:</label>
                    <select name="gender" id="gender" class="form-control" required>
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


            <button type="submit" class="btn btn-success mt-3">Save Changes</button>
        </form>
    </div>
</div>

<script>
  
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
