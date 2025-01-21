@extends('layouts.app')

@section('title', 'Add New Service')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .form-container {
        max-width: 800px;
        margin: 40px auto;
        background: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        overflow: hidden;
    }

    .form-header {
        background: #28a745;
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

    .btn-success {
        background-color: #28a745;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        color: white;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-success:hover {
        background-color: #218838;
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
</style>



<div class="form-container">
    <div class="form-header">
        <h3>Add New Service</h3>
    </div>
    <div class="form-body">
    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: '{{ $errors->first() }}',
                confirmButtonText: 'OK'
            }).then(() => {
                // Focus on the first invalid field
                const errorFieldName = '{{ array_key_first($errors->toArray()) }}';
                const errorField = document.querySelector(`[name="${errorFieldName}"]`);
                if (errorField) {
                    errorField.focus();
                }
            });
        });
    </script>
@endif


        <form action="{{ route('services.store') }}" method="POST">
            @csrf
            <table>
                <tr>
                    <th>Service Name:</th>
                    <td>
                        <input type="text" name="service" id="service" class="form-control" required>

                    </td>
                </tr>
                <tr>
                    <th>Category:</th>
                    <td>
                        <select name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach ($serviceCategories as $category)
                                <option value="{{ $category['category'] }}">{{ $category['category'] }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </td>
                </tr>
                <tr>
                    <th>Cost:</th>
                    <td>
                        <input type="number" name="cost" class="form-control" required>
                    </td>
                </tr>
                <tr>
                    <th>Location:</th>
                    <td>
                        <input type="text" name="location" class="form-control" required>
                    </td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>
                        <select name="status" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="form-footer">
                <button type="submit" class="btn-success">Add Service</button>
                <a href="{{ route('services.view') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
