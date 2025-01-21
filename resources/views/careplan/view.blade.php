@extends('layouts.app')

@section('title', 'View Careplan')

@section('content')
<style>
    .view-container {
        width: 80%;
        margin: 20px auto;
        padding: 20px;
        background: #f5f5f5;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .view-container h2 {
        margin-bottom: 20px;
    }
    .view-details {
        margin-bottom: 10px;
    }
</style>

<div class="view-container">
    <h2>Care Plan Details for {{ $carePlan->clientName }}</h2>
    <div class="view-details">
        <strong>Plan Type:</strong> {{ $carePlan->planType }}
    </div>
    <div class="view-details">
        <strong>Start Date:</strong> {{ $carePlan->startDate }}
    </div>
    <div class="view-details">
        <strong>End Date:</strong> {{ $carePlan->endDate }}
    </div>
    <div class="view-details">
        <strong>Status:</strong> {{ $carePlan->status }}
    </div>
    <div class="view-details">
        <strong>Total Services:</strong> {{ $carePlan->totalServices }}
    </div>
    <div class="view-details">
        <strong>Assigned Caregiver:</strong> {{ $carePlan->caregiverName }}
    </div>
    <a href="{{ route('careplan.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>

@endsection
