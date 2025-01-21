<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService; // Make sure to import the FirebaseService
use App\Services\StorageService;
use Illuminate\Http\Request;

class ClientController extends Controller
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
            // Handle the case where the user ID is not in the session
            return redirect()->route('login')->with('error', 'User not authenticated');
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
        
        // ---- getting all the view client ----
        $clients = $this->firebaseService->getClientsForCaretaker($userId);

        // Pass the data to the view
        return view('caregiver.viewClient', [
            'userId' => $userId, 
            'imageUrl' => $imageUrl,
            'username' => $userData['username'] ?? 'Guest',
            'role' => $userData['role_name'] ?? 'Unknown',
            'name' => $userData['name'] ?? 'Unknown',
            'totalUsersWithRole2' => $totalUsersWithRole2, // Pass total count of users with role == 2
            'totalClients' => $totalClients,  
        ], compact('clients'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'ic_no' => 'required|string|max:255',
                'phone_no' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);
    
            // Log the validated data to ensure everything is as expected
            \Log::info('Validated data:', $validated);
    
            // Perform the update on Firebase
            $this->firebaseService->updateClient($id, [
                'name' => $validated['name'],
                'ic_no' => $validated['ic_no'],
                'phone_no' => $validated['phone_no'],
                'status' => $validated['status'],
            ]);
    
            // Return a success message
            return response()->json(['message' => 'Client updated successfully']);
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error updating client:', ['error' => $e->getMessage()]);
    
            // Return a generic error message
            return response()->json(['message' => 'Failed to update client'], 500);
        }
    }
    
    
}