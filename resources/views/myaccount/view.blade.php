@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h3>My Account</h3>
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

        <form>
            @csrf
            <!-- <table class="form-table">
                <tr>
                    <td><label for="role">Role:</label></td>
                    <td>
                        <select name="role" id="role" class="form-control" disabled>
                            <option value="1" {{ $user['role'] == 1 ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ $user['role'] == 2 ? 'selected' : '' }}>Caregiver</option>
                            <option value="3" {{ $user['role'] == 3 ? 'selected' : '' }}>Client</option>
                        </select>
                    </td>
                </tr>
            </table> -->

            <!-- Common Sections -->
            <div class="form-container">
                <h4>Role's Profile</h4>
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $user['name'] }}" disabled>

                <label for="name">Role:</label>
                <select name="role" id="role" class="form-control" disabled>
                            <option value="1" {{ $user['role'] == 1 ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ $user['role'] == 2 ? 'selected' : '' }}>Caregiver</option>
                            <option value="3" {{ $user['role'] == 3 ? 'selected' : '' }}>Client</option>
                </select>

                <label for="ic_no">IC Number:</label>
                <input type="text" name="ic_no" id="ic_no" class="form-control" value="{{ $user['ic_no'] }}" disabled>

                <label for="phone_no">Phone Number:</label>
                <input type="text" name="phone_no" id="phone_no" class="form-control" value="{{ $user['phone_no'] }}" disabled>

                <label for="dob">Date of Birth:</label>
                <input type="text" name="dob" id="dob" class="form-control" value="{{ $user['formatted_dob'] }}" disabled>

                <label for="home_address">Home Address:</label>
                <textarea name="home_address" id="home_address" class="form-control" disabled>{{ $user['home_address'] }}</textarea>

                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control" disabled>
                    <option value="1" {{ $user['status'] == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $user['status'] == 0 ? 'selected' : '' }}>Inactive</option>
                </select>

                <label for="gender">Gender:</label>
                <select name="gender" id="gender" class="form-control" disabled>
                    <option value="male" {{ $user['gender'] == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $user['gender'] == 'female' ? 'selected' : '' }}>Female</option>
                </select>

                <label for="profile_photo">Profile Photo:</label>
                @if ($imageUrl)
                <img src="{{ $imageUrl }}" alt="Profile Image" style="display: block; margin: auto;">
                @else
                <!-- Optionally, display a placeholder or nothing -->
                <p>No profile image available.</p>
                @endif
            </div>

            <div class="form-container">
                <h4>App Authentication</h4>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="{{ $user['username'] }}" class="form-control" disabled>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" value="{{ $user['password'] }}" class="form-control" disabled>

                <label for="password_confirmation">Re-enter Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" value="{{ $user['password'] }}" class="form-control" disabled>
            </div>

            <div class="form-container">
                <h4>Authorized Contact Person</h4>
              <!-- <pre>{{ print_r($user, true) }}</pre> -->

                <label for="contact_name">Name:</label>
                <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{ $user['emergency_contact']['name'] ?? 'N/A' }}" disabled>

                <label for="contact_ic">IC Number:</label>
                <input type="text" name="contact_ic" id="contact_ic" class="form-control" value="{{ $user['emergency_contact']['ic_no'] ?? 'N/A' }}" disabled>

                <label for="contact_relationship">Relationship:</label>
                <input type="text" name="contact_relationship" id="contact_relationship" class="form-control" value="{{ $user['emergency_contact']['relationship'] ?? 'N/A' }}" disabled>

                <label for="contact_phone_no">Phone Number:</label>
                <input type="text" name="contact_phone_no" id="contact_phone_no" class="form-control" value="{{ $user['emergency_contact']['phone_no'] ?? 'N/A' }}" disabled>
            </div>

            <!-- Medical & Health Information Section -->
            @if ($user['role'] == 3)
            <div id="clientSection">
                <h4>Medical & Health Information</h4>
                <label for="blood_type">Blood Type:</label>
                <input type="text" name="blood_type" id="blood_type" class="form-control" value="{{ $user['medical_info']['blood_type'] ?? 'N/A' }}" disabled>

                <label for="allergic">Allergic:</label>
                <select name="allergic" id="allergic" class="form-control" disabled>
                    <option value="yes" {{ isset($user['medical_info']['allergic']) ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ !isset($user['medical_info']['allergic']) ? 'selected' : '' }}>No</option>
                </select>

                <div id="allergyFields" style="{{ isset($user['medical_info']['allergic']) ? 'display: block;' : 'display: none;' }}">
                    <label for="food_allergy">Food Allergy:</label>
                    <input type="text" name="food_allergy" id="food_allergy" class="form-control" value="{{ $user['medical_info']['allergic']['food'] ?? 'N/A' }}" disabled>

                    <label for="medicine_allergy">Medicine Allergy:</label>
                    <input type="text" name="medicine_allergy" id="medicine_allergy" class="form-control" value="{{ $user['medical_info']['allergic']['medicine'] ?? 'N/A' }}" disabled>
                </div>

<div class="form-container">
                <label for="health_conditions">Health Conditions:</label>
                @if (isset($user['medical_info']['healthConditions']) && is_array($user['medical_info']['healthConditions']))
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Condition Name</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user['medical_info']['healthConditions'] as $index => $condition)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $condition['name'] }}</td>
                                    <td>{{ str_replace('Remove', '', $condition['desc']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No health conditions available.</p>
                @endif
</div>
<div class="form-container">
                <label for="medications">Medications:</label>
                    @if(isset($user['medical_info']['medications']) && is_array($user['medical_info']['medications']))
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Medication Name</th>
                                    <th>Purpose</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user['medical_info']['medications'] as $index => $medication)
                                    @if(!empty($medication['name']) && !empty($medication['purpose']))
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $medication['name'] }}</td>
                                            <td>{{ $medication['purpose'] }}</td>
                                            <td>{{ $medication['dosage'] ?? 'N/A' }}</td>
                                            <td>{{ $medication['frequency'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No medications available.</p>
                    @endif
                    </div>
                    @endif

                       
             <!-- List of Clients Section -->
             <!-- Caregiver-Specific Sections -->
        @if ($role === 'Caregiver')
            <div class="form-container">
                <h4>Assigned Clients</h4>
                @php
                    $activeClients = array_filter($clients, function($client) {
                        return $client['status'] === 'Active';
                    });
                @endphp
                @if (!empty($activeClients))
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client Name</th>
                                <th>Care Plan Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeClients as $index => $client)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $client['name'] }}</td>
                                <td>{{ $client['care_type'] }}</td>
                                <td>{{ $client['start_date'] }}</td>
                                <td>{{ $client['end_date'] }}</td>
                                <td> <td><i class="fa fa-eye"></i>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No active care plans available.</p>
                @endif
            </div>

            <!-- Inactive Clients Section -->
            <div class="form-container">
                <h4>History Care Plans</h4>
                @php
                    $inactiveClients = array_filter($clients, function($client) {
                        return $client['status'] === 'Inactive';
                    });
                @endphp
                @if (!empty($inactiveClients))
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client Name</th>
                                <th>Care Plan Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inactiveClients as $index => $client)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $client['name'] }}</td>
                                <td>{{ $client['care_type'] }}</td>
                                <td>{{ $client['start_date'] }}</td>
                                <td>{{ $client['end_date'] }}</td>
                                <td><i class="fa fa-eye"></i>
                                    
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No history care plans available.</p>
                @endif
            </div>
        @endif

        </form>

</div>

            <!-- Edit Button -->
            <button type="button" onclick="window.location.href='{{ route('myaccount.edit') }}'" class="btn btn-success mt-3">
    Edit
</button>


        </form>
    </div>
</div>


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
