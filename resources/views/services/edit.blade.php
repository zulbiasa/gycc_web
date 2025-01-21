@extends('layouts.app')

@section('title', 'Edit Service')

@section('content')
<style>
    /* Custom CSS for a stunning form */
    .form-container {
        max-width: 800px;
        margin: 40px auto;
        background: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        overflow: hidden;
    }

    .form-header {
        background: #007bff;
        color: white;
        padding: 20px;
        text-align: center;
    }

    .form-header h3 {
        margin: 0;
        font-size: 24px;
    }

    .form-body {
        padding: 20px;
    }

    .form-body table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 15px;
    }

    .form-body th {
        text-align: left;
        padding: 10px;
        vertical-align: middle;
        color: #555;
        font-size: 16px;
        width: 30%;
    }

    .form-body td {
        padding: 10px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        outline: none;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        color: white;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        color: white;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .form-footer {
        text-align: center;
        margin-top: 20px;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h3>Edit Service</h3>
    </div>
    <div class="form-body">
        <form action="{{ route('services.update', $service['id']) }}" method="POST">
            @csrf
            @method('PUT')

            <table>
                <tr>
                    <th>Service Name:</th>
                    <td>
                        <!-- Displayed input field (disabled) -->
                        <input 
                            type="text" 
                            id="serviceName" 
                            name="service_display" 
                            value="{{ old('service', $service['service'] ?? '') }}" 
                            class="form-control" 
                            disabled>
                        
                        <!-- Hidden input field to include the value in the POST request -->
                        <input 
                            type="hidden" 
                            name="service" 
                            value="{{ old('service', $service['service'] ?? '') }}">
                    </td>
                </tr>

                <tr>
                    <th>Category:</th>
                    <td>
                        <!-- Displayed select field (disabled) -->
                        <select id="category" name="category_display" class="form-control" disabled>
                            @foreach ($serviceCategories as $category)
                                @if (isset($category['category']))
                                    <option value="{{ $category['category'] }}" 
                                        {{ (old('category', $service['category'] ?? '') === $category['category']) ? 'selected' : '' }}>
                                        {{ $category['category'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        <!-- Hidden input field to include the value in the POST request -->
                        <input 
                            type="hidden" 
                            name="category" 
                            value="{{ old('category', $service['category'] ?? '') }}">
                    </td>
                </tr>

                <tr>
                    <th>Cost:</th>
                    <td>
                        <input 
                            type="number" 
                            id="cost" 
                            name="cost" 
                            value="{{ old('cost', $service['cost'] ?? '') }}" 
                            class="form-control" 
                            required>
                        @error('cost')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>

                <tr>
                    <th>Status:</th>
                    <td>
                        <select id="status" name="status" class="form-control" required>
                            <option value="1" {{ old('status', $service['status'] ?? '') == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $service['status'] ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </td>
                </tr>
            </table>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('services.view') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
