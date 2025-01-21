@extends('layouts.app')

@section('title', 'Register User')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h4>Register as {{ $role }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" required>
                    @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                @if ($role === 'Client')
                <div class="form-group mt-3">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" class="form-control">{{ old('address') }}</textarea>
                </div>
                @elseif ($role === 'Staff')
                <div class="form-group mt-3">
                    <label for="department">Department</label>
                    <input type="text" id="department" name="department" class="form-control" value="{{ old('department') }}">
                </div>
                @endif

                <button type="submit" class="btn btn-success w-100 mt-4">Register</button>
            </form>
        </div>
    </div>
</div>
@endsection
