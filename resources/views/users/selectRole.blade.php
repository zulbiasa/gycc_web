@extends('layouts.app')

@section('title', 'Select Role')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <!-- Role Selection -->
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <label for="role" class="font-weight-bold">User Type:</label>
                <select id="role" name="role" class="form-control d-inline-block w-auto mb-4" onchange="showForm()">
                <option value="">Choose</option>
                <option value="Admin">Admin</option>
                <option value="Client">Client</option>
                <option value="Staff">Staff</option>
                </select>
            </form>


            <!-- Form Container -->
            <div id="formContainer" class="form-container" style="display: none;">
                <!-- Dynamic Form Content -->
            </div>
        </div>
    </div>
</div>

<style>
    /* General Styles */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: auto;
        margin: 20px auto;
        padding: 2px;
    }

    .card {
        border-radius: 6px;
        box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* .card-header {
        border-radius: 10px;
        background-color: #007bff;
        color: #fff;
        font-size: 1.5rem;
        padding: 7px;
        margin: 4px;
    } */

    .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 10px;
        width: 100%; /* Ensures full width */
        margin-bottom: 10px; /* Adds uniform spacing */
    }

    .form-container {
        margin-top: 20px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h5 {
        font-size: 1.3rem;
        margin-bottom: 15px;
        color: #333;
        border-bottom: 2px solid #007bff;
        padding-bottom: 5px;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 15px;
        gap: 15px; /* Adds spacing between inputs */
    }

    .form-row .form-group {
        flex: 1 1 calc(50% - 10px); /* Input fields take 50% width */
    }

    .form-row .form-group.full-width {
        flex: 1 1 100%; /* For fields that need full width */
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
        display: inline-block;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 1rem;
        width: 100%;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: scale(1.02);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-container {
            padding: 15px;
        }
        h5 {
            font-size: 1.2rem;
        }
    }
</style>

<script>
    function showForm() {
        const role = document.getElementById('role').value;
        const formContainer = document.getElementById('formContainer');
        formContainer.style.display = role ? 'block' : 'none';

        let formContent = '';

        const commonProfileSection = `
            <h5>${role}'s Profile</h5>
            <div class="form-row">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="fullName" class="form-control" placeholder="Enter full name">
                <label for="icNumber">IC Number</label>
                <input type="number" id="icNumber" name="icNumber" class="form-control" placeholder="Enter IC number">
                <label for="phoneNumber">Phone Number</label>
                <input type="tel" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="Enter phone number">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" class="form-control">
                <label for="homeAddress">Home Address</label>
                <textarea id="homeAddress" name="homeAddress" class="form-control" placeholder="Enter home address"></textarea>
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <label for="gender">Gender</label>
                <select id="gender" name="gender" class="form-control">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <label for="profilePhoto">Profile Photo</label>
                <input type="file" id="profilePhoto" name="profilePhoto" accept="image/png, image/jpeg" class="form-control">
            </div>
        `;

        const appAuthenticationSection = `
            <h5>App Authentication</h5>
            <div class="form-row">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter a username">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter a password">
                <label for="confirmPassword">Re-enter Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Re-enter your password">
            </div>
        `;

        const authorizedContactSection = `
            <h5>Authorized Contact Person</h5>
            <div class="form-row">
                <label for="contactName">Name</label>
                <input type="text" id="contactName" name="contactName" class="form-control">
                <label for="contactIC">IC Number</label>
                <input type="text" id="contactIC" name="contactIC" class="form-control">
                <label for="contactRelationship">Relationship</label>
                <input type="text" id="contactRelationship" name="contactRelationship" class="form-control">
                <label for="contactPhone">Phone Number</label>
                <input type="text" id="contactPhone" name="contactPhone" class="form-control">
            </div>
        `;

        const medicalSection = `
            <h5>Medical & Health Information</h5>
            <div class="form-row">
                <label for="bloodType">Blood Type</label>
                <input type="text" id="bloodType" name="bloodType" class="form-control">
                <label for="allergic">Allergic</label>
                <select id="allergic" name="allergic" class="form-control" onchange="toggleAllergyInput()">
                    <option value="no">No</option>
                    <option value="yes">Yes</option>
                </select>
                <div id="allergyInputs" style="display: none;">
                    <label for="foodAllergy">Food Allergy</label>
                    <input type="text" id="foodAllergy" name="foodAllergy" class="form-control">
                    <label for="medicineAllergy">Medicine Allergy</label>
                    <input type="text" id="medicineAllergy" name="medicineAllergy" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <label for="healthCondition">Health Condition</label>
                <input type="text" id="healthCondition" name="healthCondition" class="form-control" placeholder="Enter health conditions">
                <button type="button" class="btn btn-sm btn-primary mt-2">Add More</button>
            </div>
            <div class="form-row">
                <label for="drPrescription">Dr Prescription (PDF)</label>
                <input type="file" id="drPrescription" name="drPrescription" accept="application/pdf" class="form-control">
            </div>
        `;

        if (role === 'Admin' || role === 'Staff') {
            formContent = commonProfileSection + appAuthenticationSection + authorizedContactSection;
        } else if (role === 'Client') {
            formContent = commonProfileSection + appAuthenticationSection + medicalSection + authorizedContactSection;
        }

        formContent += `<form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
<button type="submit" class="btn-primary">Save</button></form>`;

        formContainer.innerHTML = formContent;
    }

    function toggleAllergyInput() {
        const allergic = document.getElementById('allergic').value;
        const allergyInputs = document.getElementById('allergyInputs');
        allergyInputs.style.display = allergic === 'yes' ? 'block' : 'none';
    }
</script>

@endsection
