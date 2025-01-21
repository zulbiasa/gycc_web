<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Hash;
use App\Services\StorageService;

class UserController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService, StorageService $storage)
    {
        $this->firebaseService = $firebaseService;
        $this->storage = $storage;

        // Middleware to set session variables
        $this->middleware(function ($request, $next) {
            $userId = session('user_id');
            $imageUrl = $this->storage->getImage($userId);

            if (!$userId) {
                return redirect()->route('login');
            }

            $userData = $this->firebaseService->getUserDataById($userId);

            if (!$userData) {
                return redirect()->route('login');
            }

            session([
                'role' => $userData['role_name'] ?? 'Unknown',
                'username' => $userData['username'] ?? 'Guest',
                'imageUrl' => $imageUrl,
            ]);

            return $next($request);
        });
    }

    // Show role selection page
   public function selectRole()
{
    $userId = session('user_id');
    $role = session('role', 'Guest'); // Default to 'Guest' if undefined
    $username = session('username', 'Unknown');

    return view('users.selectRole', [
        'role' => session('role'),
            'username' => session('username'),
            'imageUrl'=> session('imageUrl'),
    ]);
}


    // Show role-specific registration form
    public function create($role)
    {
        $validRoles = ['Admin', 'Client', 'Staff']; // List of valid roles
        if (!in_array($role, $validRoles)) {
            return redirect()->route('users.selectRole')->with('error', 'Invalid role selected.');
        }
    
        return view('users.register', compact('role')); // Pass the selected role to the view
    }
    
    // Handle registration form submission
    public function store(Request $request)
{
    // Ensure the user is logged in
    $userId = session('user_id');
    if (!$userId) {
        return redirect()->route('login');
    }

    // Get the current user session data
    $userData = $this->firebaseService->getUserDataById($userId);

    if (!$userData) {
        return redirect()->route('login');
    }

    session([
        'role' => $userData['role_name'] ?? 'Unknown',
        'username' => $userData['username'] ?? 'Guest',
        'imageUrl' => $imageUrl,
    ]);

    // Validate input data
    $validated = $request->validate([
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:Admin,Client,Staff',
        'name' => 'required|string|max:255',
    ]);

    try {
        // Retrieve all users from Firebase
        $users = $this->firebaseService->getDatabase()
            ->getReference('User')
            ->getValue() ?? [];

        // Check for duplicate username or email
        foreach ($users as $user) {
            if (strcasecmp($user['username'], $validated['username']) === 0) {
                return back()->withErrors(['username' => 'The username is already taken.'])->withInput();
            }
            if (strcasecmp($user['email'], $validated['email']) === 0) {
                return back()->withErrors(['email' => 'The email is already taken.'])->withInput();
            }
        }

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Save the new user to Firebase
        $this->firebaseService->getDatabase()
            ->getReference('User')
            ->push($validated);

        return redirect()->route('dashboard')->with('success', 'User registered successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to register user: ' . $e->getMessage());
    }
}


}
