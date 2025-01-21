<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firebase CRUD</title>
</head>
<body>
    <h1>Firebase CRUD Operations</h1>

    <!-- Success and Error Messages -->
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <!-- Add Data Form -->
    <form action="{{ route('add-data') }}" method="POST">
        @csrf
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <button type="submit">Add Data</button>
    </form>

    <h2>Existing Users</h2>

    <!-- Display Data Table -->
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($users))
                @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ $user['name'] ?? 'N/A' }}</td>
                        <td>{{ $user['email'] ?? 'N/A' }}</td>
                        <td>
                            <!-- Delete Data Form -->
                            <form action="{{ route('delete-data', $key) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" style="text-align: center;">No data available</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
