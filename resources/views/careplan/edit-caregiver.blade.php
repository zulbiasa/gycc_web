@extends('layouts.app')

@section('title', 'Edit Care Plan')

@section('content')
<style>
    .container {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        font-family: Arial, sans-serif;
    }
    .services-table table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .services-table th,
    .services-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .services-table th {
        background-color: #f4f4f4;
        font-weight: bold;
        text-transform: uppercase;
    }

    .services-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .services-table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .summary table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .summary th,
    .summary td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
        font-size: 16px;
    }

    .summary th {
        background-color: #e9ecef;
        font-weight: bold;
        text-transform: uppercase;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
        font-size: 24px;
        font-weight: bold;
        color:rgb(255, 255, 255);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
        font-size: 16px;
        color: #333333;
        display: block;
    }

    .form-control {
        padding: 10px;
        font-size: 14px;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 100%;
        background-color: #f8f9fa;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    /* Table Styles */
    .services-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .services-table th,
    .services-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .services-table th {
        background-color: #f4f4f4;
        font-weight: bold;
        text-transform: uppercase;
    }

    .services-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .services-table tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>

<div class="container">
    <div class="header" style="text-color:rgb(255, 255, 255);">
        Edit Care Plan
    </div>

    <form method="POST" action="{{ route('careplan.editCaregiver', [$carePlan['userId'], $carePlan['planId']]) }}">
        @csrf

        <!-- Client Name -->
        <div class="form-group">
            <label for="clientName">Client Name</label>
            <input type="text" id="clientName" class="form-control" value="{{ $carePlan['clientName'] ?? 'Unknown' }}" readonly>
        </div>

        <!-- Assign Caregiver -->
        <div class="form-group">
            <label for="caregiver_id">Assign Caregiver</label>
            <select name="caregiver_id" id="caregiver_id" class="form-control" required>
                <option value="" {{ is_null($carePlan['caregiverID']) ? 'selected' : '' }}>-- Select Caregiver --</option>
                @foreach ($caregivers as $id => $caregiver)
                    <option value="{{ $id }}" {{ $carePlan['caregiverID'] == $id ? 'selected' : '' }}>
                        {{ $caregiver['name'] ?? 'Unknown' }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Care Plan Type -->
        <div class="form-group">
            <label for="planType">Care Plan Type</label>
            <input type="text" id="planType" class="form-control" value="{{ $carePlan['planType'] ?? 'N/A' }}" readonly>
        </div>

       <!-- Start Date -->
        <div class="form-group">
            <label for="startDate">Start Date</label>
            <input type="text" id="startDate" class="form-control" value="{{ $carePlan['startDate'] }}" readonly>
        </div>

        <!-- End Date -->
        <div class="form-group">
            <label for="endDate">End Date</label>
            <input type="text" id="endDate" class="form-control" value="{{ $carePlan['endDate'] }}" readonly>
        </div>


        <!-- Status -->
        <div class="form-group">
            <label for="status">Status</label>
            <input type="text" id="status" class="form-control" value="{{ $carePlan['status'] ?? 'Inactive' }}" readonly>
        </div>

        <!-- Total Services -->
        <div class="form-group">
            <label for="totalServices">Total Services</label>
            <input type="text" id="totalServices" class="form-control" value="{{ $carePlan['totalServices'] ?? 0 }}" readonly>
        </div>

        <!-- Services Table -->
        <div class="container">
            <div>
                <h3>Registered Services</h3>
                <table class="services-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Frequency</th>
                            <th>Sessions</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($services))
                            @foreach ($services as $index => $service)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $service['service'] ?? 'Unknown' }}</td>
                                    <td>{{ $service['description'] ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($service['frequency']) }}</td>
                                    <td>{{ $service['session'] }}</td>
                                    <td>RM{{ number_format($service['cost'] ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No Services Registered</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
<br>
        
        <!-- Costs Section -->
        <!-- <div class="container mt-4">
            <h3>Quotation</h3>
            <div class="services-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Service Name</th>
                            <th>Frequency</th>
                            <th>Cost Per Session (RM)</th>
                            <th>Number of Sessions</th>
                            <th>Total Cost (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($services))
                            @foreach ($services as $index => $service)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $service['service'] ?? 'Unknown' }}</td>
                                    <td>{{ ucfirst($service['frequency'] ?? 'N/A') }}</td>
                                    <td>RM{{ number_format($service['cost'] ?? 0, 2) }}</td>
                                    <td>{{ $service['session'] ?? 0 }}</td>
                                    <td>RM{{ number_format(($service['cost'] ?? 0) * ($service['session'] ?? 0), 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No Services Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div> -->

            <!-- Summary Section -->
            <!-- <div class="summary mt-4">
                <table>
                    <thead>
                        <tr>
                            <th>Total Service Cost</th>
                            <th>Discount</th>
                            <th>Grand Total</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>RM{{ number_format($totalCost, 2) }}</td>
                            <td>RM{{ number_format($discount ?? 0, 2) }}</td>
                            <td>RM{{ number_format($grandTotal, 2) }}</td>
                            <td>{{ ucfirst($paymentStatus ?? 'Pending') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div> -->
        </div>

        <br>

        <button type="submit" class="btn btn-primary">Update Caregiver</button>
        <a href="{{ route('careplan.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
