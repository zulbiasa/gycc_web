<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService; // Make sure to import the FirebaseService
use App\Services\StorageService;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class ViewCarePlanController extends Controller
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
        $name = $userData['name']; 
    
        // Fetch all users from Firebase using FirebaseService
        $users = $this->firebaseService->getAllUsers(); 
        $carePlans = $this->firebaseService->getCarePlansByCaregiver($userId);
        $imageUrl = $this->storage->getImage($userId);


        // Pass the data to the view
        return view('caregiver.viewCarePlan', [
            'userId' => $userId, 
            'imageUrl' => $imageUrl,
            'username' => $userData['username'] ?? 'Guest',
            'role' => $userData['role_name'] ?? 'Unknown',
            'name' => $userData['name'] ?? 'Unknown',
            'carePlans' => $carePlans,
        ]);
    }



}

