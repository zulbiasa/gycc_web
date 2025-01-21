<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService; // Make sure to import the FirebaseService
use Illuminate\Http\Request;
use App\Services\StorageService; //get Profile Picture

class DashboardController extends Controller
{
    protected $firebaseService;

    // Inject FirebaseService into the constructor
    public function __construct(FirebaseService $firebaseService, StorageService $bucket)
    {
        $this->firebaseService = $firebaseService;
        $this->bucket = $bucket;
    }

    public function index()
    {
        $userId = session('user_id');
        $role = session('role');
        $username = session('username');
        
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

        // Fetch all users from Firebase using FirebaseService
        $users = $this->firebaseService->getAllUsers(); 

        // Dump the users data to check if it’s being retrieved correctly
        //dd($users);

        // Filter users by role == 2 (e.g., caregivers)
        $totalUsersWithRole2 = collect($users)->filter(function($user) {
            return isset($user['role']) && $user['role'] == 2; // Role 2 for caregivers
        })->count(); // Count the filtered users

        // Fetch services data
        $services = $this->firebaseService->getAllServices(); // Adjust according to your data structure

        // Count the number of active services (status == true)
        $activeServices = collect($services)->filter(function($service) {
            return isset($service['status']) && $service['status'] == true; // Check if status is true
        })->count();

        // Fetch all care logs for all users
        $allCareLogs = $this->firebaseService->getAllCareLogs();

        // Count the number of incomplete tasks across all users
        $pendingTasks = collect($allCareLogs)->flatten()->filter(function($careLog) {
            return isset($careLog['status']) && $careLog['status'] === 'incomplete'; // Check if the task status is incomplete
        })->count();

        $imageUrl = $this->bucket->getImage($userId);

        // Pass the data to the view, including $careLogs
        return view('dashboard.dashboard', [
            'userId' => $userId, 
            'username' => $userData['username'] ?? 'Guest',
            'role' => $userData['role_name'] ?? 'Unknown',
            'imageUrl' => $imageUrl,
            'totalUsersWithRole2' => $totalUsersWithRole2, // Pass total count of users with role == 2
            'activeServices' => $activeServices, // Pass the active services count
            'pendingTasks' => $pendingTasks, // Pass pending tasks count
            'careLogs' => $allCareLogs, // Pass care logs to the view
        ]);
    }

}

