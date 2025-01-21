@extends('layouts.app')

@section('title', 'Edit Careplan')

@section('content')
<style>
    .edit-container {
        width: 80%;
        margin: 20px auto;
        padding: 20px;
        background: #f5f5f5;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .edit-container h2 {
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }
</style>

<div class="edit-container">
    <h2>Edit Care Plan for {{ $carePlan['clientName'] }}</h2>

    <form action="{{ route('careplan.update', [$carePlan['userId'], $carePlan['planId']]) }}" method="POST">
        @csrf
        @method('POST')

        <div class="form-group">
            <label for="planType">Plan Type:</label>
            <input type="text" id="planType" name="planType" value="{{ old('planType', $carePlan['planType']) }}" required>
        </div>

        <div class="form-group">
            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" name="startDate" value="{{ old('startDate', $carePlan['startDate']) }}" required>
        </div>

        <div class="form-group">
            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" name="endDate" value="{{ old('endDate', $carePlan['endDate']) }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" id="status" name="status" value="{{ old('status', $carePlan['status']) }}" required>
        </div>

        <div class="form-group">
            <label for="totalServices">Total Services:</label>
            <input type="number" id="totalServices" name="totalServices" value="{{ old('totalServices', $carePlan['totalServices']) }}" required>
        </div>

        <div class="form-group">
            <label for="caregiverName">Assigned Caregiver:</label>
            <input type="text" id="caregiverName" name="caregiverName" value="{{ old('caregiverName', $carePlan['caregiverName']) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('careplan.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>


@endsection
