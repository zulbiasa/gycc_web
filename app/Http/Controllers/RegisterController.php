<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService; // Make sure to import the FirebaseService
use App\Services\StorageService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $firebaseService;

    // Inject FirebaseService into the constructor
    public function __construct(FirebaseService $firebaseService, StorageService $storage)
    {
        $this->firebaseService = $firebaseService;
        $this->storage = $storage;
    }

    public function index()
    {
        $userId = session('user_id');
        $role = session('role');
        $username = session('username');
        $name = session('name');
        
        // If the user is not logged in, redirect to login page
        if (!$userId) {
            return redirect()->route('login');
        }
        
        // Fetch the user data from Firebase using the userId
        $userData = $this->firebaseService->getUserDataById($userId);
    
        // If userData is not found, redirect to login
        if (!$userData) {
            return redirect()->route('login');
        }
    
        // Extract the role from the user data
        $role = $userData['role'];
        $username = $userData['username'];  
        $imageUrl = $this->storage->getImage($userId);
    
        // Fetch all users from Firebase using FirebaseService
        $users = $this->firebaseService->getAllUsers(); 
    
        // Dump the users data to check if it’s being retrieved correctly
        //dd($users);

        // Filter users by role == 2 (e.g., caregivers)
        $totalUsersWithRole2 = collect($users)->filter(function($user) {
            return isset($user['role']) && $user['role'] == 2; // Role 2 for caregivers
        })->count(); // Count the filtered users
    
        // Example: fetching totalClients dynamically
        $totalClients = 10; // Replace this with actual logic to get client count
    
        // Pass the data to the view
        return view('caregiver.registerClient', [
            'userId' => $userId, 
            'imageUrl' => $imageUrl,
            'username' => $userData['username'] ?? 'Guest',
            'role' => $userData['role_name'] ?? 'Unknown',
            'name' => $userData['name'] ?? 'Unknown',
            'totalUsersWithRole2' => $totalUsersWithRole2, // Pass total count of users with role == 2
            'totalClients' => $totalClients, 
        ]);
    }

    public function store(Request $request)
{
    try {
        // Log the start of the method
        \Log::info('Client registration process started.');

        // Validate incoming request data
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'ic_number' => 'required|numeric',
            'phone_number' => 'required|numeric',
            'dob' => 'required|date',
            'home_address' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'gender' => 'required|in:male,female',
            //'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        \Log::info('Validation passed successfully.');

        // Prepare data for Firebase
        $clientData = [
            'full_name' => $validatedData['full_name'],
            'ic_number' => $validatedData['ic_number'],
            'phone_number' => $validatedData['phone_number'],
            'dob' => $validatedData['dob'],
            'home_address' => $validatedData['home_address'],
            'status' => $validatedData['status'],
            'gender' => $validatedData['gender'],
            //'profile_picture' => $imagePath, // store the image path if any
        ];

        // Log data before storing
        \Log::info('Client data prepared for Firebase:', $clientData);

        // Save data to Firebase using the FirebaseService
        $userId = $this->firebaseService->storeClientData($clientData);

        // Log after storing data
        \Log::info('Data successfully saved to Firebase with User ID: ' . $userId);

        // Redirect or respond as needed
        return redirect()->route('register')->with('success', 'Client registration successful!');
    } catch (\Exception $e) {
        // Log any error that occurs
        \Log::error('Error during client registration: ' . $e->getMessage());
        
        // Optionally return an error message to the user
        return redirect()->route('register')->with('error', 'Client registration failed. Please try again.');
    }
}

}

