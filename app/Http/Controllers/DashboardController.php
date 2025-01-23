<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService; // Make sure to import the FirebaseService
use Illuminate\Http\Request;
use App\Services\StorageService; //get Profile Picture
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
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
        $name = session('name');
        
        // If the user is not logged in, redirect to login page
        if (!$userId) {
            return redirect()->route('login');
        }
        
        // Fetch the user data from Firebase using the userId
        $userData = $this->firebaseService->getUserDataById($userId);
        if (!$userData) {
            return redirect()->route('login');
        }

        // Fetch user statistics and services data
        $users = $this->firebaseService->getAllUsers(); 
        $services = $this->firebaseService->getAllServices(); // Adjust according to your data structure
        $allCareLogs = $this->firebaseService->getAllCareLogs();
        
      
        // Extract the role from the user data
        $role = $userData['role'];
        $username = $userData['username'];  

        $totalUsers = is_array($users) ? count($users) : 0;

        $totalActiveUsers = collect($users)->filter(function ($user) {
            return isset($user['status']) && $user['status'] === true;
        })->count();

        $totalUsersWithRole3 = collect($users)->filter(function($user) {
            return isset($user['role']) && $user['role'] == 3; // Role 2 for caregivers
        })->count(); // Count the filtered users

        // Filter users by role == 2 (e.g., caregivers)
        $totalUsersWithRole2 = collect($users)->filter(function($user) {
            return isset($user['role']) && $user['role'] == 2; // Role 2 for caregivers
        })->count(); // Count the filtered users

        // Filter users by role == 2 (e.g., caregivers)
        $totalUsersWithRole1 = collect($users)->filter(function($user) {
            return isset($user['role']) && $user['role'] == 1; // Role 2 for caregivers
        })->count(); // Count the filtered users
       
        // Count the number of active services (status == true)
        $activeServices = collect($services)->filter(function($service) {
            return isset($service['status']) && $service['status'] == true; // Check if status is true
        })->count();
        
        // Count the number of incomplete tasks across all users
        $pendingTasks = collect($allCareLogs)->flatten()->filter(function($careLog) {
            return isset($careLog['status']) && $careLog['status'] === 'incomplete'; // Check if the task status is incomplete
        })->count();

        $totalUsersWithRoleForCaregiver = collect($users)->filter(function ($user) use ($userId) {
            // Check if the role is 3 and if CarePlan exists with matching caregiverID
            return isset($user['role']) && $user['role'] == 3 
                && isset($user['CarePlan']) 
                && collect($user['CarePlan'])->contains(function ($carePlan) use ($userId) {
                    return isset($carePlan['caregiverID']) && $carePlan['caregiverID'] == $userId;
                });
        })->count(); // Count the filtered users

        $totalPendingReminders = collect($users)->filter(function ($user) use ($userId) {
            // Check if the user has a matching caregiverID in CarePlan
            return isset($user['role']) && $user['role'] == 3 
                && isset($user['CarePlan']) 
                && collect($user['CarePlan'])->contains(function ($carePlan) use ($userId) {
                    return isset($carePlan['caregiverID']) && $carePlan['caregiverID'] == $userId;
                });
        })->sum(function ($user) {
            // Sum the count of pending statuses in Reminder -> medicineRemind
            if (isset($user['Reminder']['medicineRemind'])) {
                return collect($user['Reminder']['medicineRemind'])->filter(function ($reminder) {
                    return isset($reminder['status']) && $reminder['status'] === 'pending';
                })->count();
            }
            return 0; // No pending reminders for this user
        });

        $totalMissedReminders = collect($users)->filter(function ($user) use ($userId) {
            // Check if the user has a matching caregiverID in CarePlan
            return isset($user['role']) && $user['role'] == 3 
                && isset($user['CarePlan']) 
                && collect($user['CarePlan'])->contains(function ($carePlan) use ($userId) {
                    return isset($carePlan['caregiverID']) && $carePlan['caregiverID'] == $userId;
                });
        })->sum(function ($user) {
            // Sum the count of pending statuses in Reminder -> medicineRemind
            if (isset($user['Reminder']['medicineRemind'])) {
                return collect($user['Reminder']['medicineRemind'])->filter(function ($reminder) {
                    return isset($reminder['status']) && $reminder['status'] === 'missed';
                })->count();
            }
            return 0; // No pending reminders for this user
        });

        $imageUrl = $this->bucket->getImage($userId);

      

        $recentActivities = $this->getRecentActivities();
      
        $usersWithCareLogs = $this->firebaseService->getCareLog($userId);
        // Pass the data to the view, including $careLogs
        return view('dashboard.dashboard', [
            'userId' => $userId, 
            'username' => $userData['username'] ?? 'Guest',
            'role' => $userData['role_name'] ?? 'Unknown',
            'name' => $userData['name'] ?? 'Unknown',
            'imageUrl' => $imageUrl,
            'totalUsers' => $totalUsers, // Pass total user count
            'totalActiveUsers' => $totalActiveUsers, // Pass total active user 
            'totalUsersWithRole3' => $totalUsersWithRole3, // Pass total count of users with role == 3
            'totalUsersWithRole2' => $totalUsersWithRole2, // Pass total count of users with role == 2
            'totalUsersWithRole1' => $totalUsersWithRole1, // Pass total count of users with role == 1
            'activeServices' => $activeServices, // Pass the active services count
            'pendingTasks' => $pendingTasks, // Pass pending tasks count
            'careLogs' => $allCareLogs, // Pass care logs to the view
            'recentActivities' => $recentActivities, // Pass recent activities to the view
            'totalUsersWithRoleForCaregiver' => $totalUsersWithRoleForCaregiver,
            'totalPendingReminders' => $totalPendingReminders,
            'totalMissedReminders' => $totalMissedReminders,
            'users' => $this->firebaseService->getAllUsers(), // Fetch all users
            'usersCareLog' => $usersWithCareLogs,

        ]);
    }

    /**
     * Prepare recent activities (care logs + care plans).
     */
    private function getRecentActivities()
    {
        $users = $this->firebaseService->getAllUsers(); // Fetch all users with nested CarePlan and Reminder
        $recentActivities = [];
    
        // Loop through each user
        if ($users) {
            foreach ($users as $userId => $user) {
                $clientName = $user['name'] ?? 'Unknown Client';
    
                // Process CarePlans
                if (isset($user['CarePlan']) && is_array($user['CarePlan'])) {
                    foreach ($user['CarePlan'] as $carePlanId => $carePlan) {
                        if (!is_array($carePlan)) continue;

                        $caregiverId = $carePlan['caregiverID'] ?? null;
                        $caregiverName = isset($users[$caregiverId]) ? $users[$caregiverId]['name'] : 'Unknown Caregiver';

                        // Skip if caregiver is unknown
                        if ($caregiverName === 'Unknown Caregiver') {
                            continue;
                        }

                        $recentActivities[] = [
                            'activity' => "Care plan created for $clientName by $caregiverName",
                            'date' => $carePlan['current_timestamp'] ?? now(),
                            'type' => 'Care Plan',
                            'status' => 'complete',
                        ];
                    }
                }
    
             // Process Medicine Reminders
       // Process Medicine Reminders
// // Process Medicine Reminders
// if (isset($user['Reminder']['medicineRemind']) && is_array($user['Reminder']['medicineRemind'])) {
//     foreach ($user['Reminder']['medicineRemind'] as $reminderId => $reminder) {
//         $status = $reminder['status'] ?? 'pending'; // Default to "pending" if no status is given
//         $medicineName = $reminder['medicineName'] ?? 'Unknown Medicine';

//         // Use `date` and `time` fields for the activity columns
//         $formattedDate = isset($reminder['date']) ? Carbon::parse($reminder['date'])->format('j F Y') : 'N/A';
//         $formattedTime = isset($reminder['actiontime']) ? Carbon::parse($reminder['time'])->format('h:i A') : 'N/A';

//         // Create the activity string
//         $activity = "Reminder for $clientName to take Medicine: $medicineName (Date: $formattedDate | Time: $formattedTime)";
//         $type = 'Medicine Reminder';
//         $statusLower = strtolower($status);

//         // Always add a new row for any status change (no duplicate check)
//         $recentActivities[] = [
//             'activity' => $activity, // Keep the current activity design
//             'date' => $formattedDate, // Use `date`
//             'time' => $formattedTime, // Use `time`
//             'type' => $type,
//             'status' => $statusLower,
//         ];
//     }
// }
    
                // // Process User Creation (e.g., by an Admin)
                // if (isset($user['created_by']) && $user['created_by']) {
                //     $adminId = $user['created_by'];
                //     $adminName = isset($users[$adminId]) ? $users[$adminId]['name'] : 'Unknown Admin';
                //     $createdAt = $user['created_at'] ?? now();
    
                //     $recentActivities[] = [
                //         'activity' => "User account created for $clientName by $adminName",
                //         'date' => $createdAt,
                //         'type' => 'User Creation',
                //         'status' => 'complete',
                //     ];
                // }
    
                // // Example: Process other updates like Emergency Contacts
                // if (isset($user['emergency_contact']) && is_array($user['emergency_contact'])) {
                //     $contactName = $user['emergency_contact']['name'] ?? 'Unknown Contact';
                //     $recentActivities[] = [
                //         'activity' => "Emergency contact updated for $clientName (Contact: $contactName)",
                //         'date' => now(),
                //         'type' => 'Emergency Contact',
                //         'status' => 'updated',
                //     ];
                // }
            }
            
        }
    
        // Sort activities by date
        // usort($recentActivities, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        // Sort activities by date in descending order
        usort($recentActivities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        // Use Laravel's paginator for custom pagination
        $perPage = 7; // Items per page
        $currentPage = request('page', 1); // Get current page from request
        $currentActivities = array_slice($recentActivities, ($currentPage - 1) * $perPage, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $currentActivities,
            count($recentActivities),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }


    public function aboutUs()
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $userData = $this->firebaseService->getUserDataById($userId);

        if (!$userData) {
            return redirect()->route('login');
        }

        $imageUrl = $this->bucket->getImage($userId);

        session([
            'role' => $userData['role_name'] ?? 'Unknown',
            'username' => $userData['username'] ?? 'Guest',
            'name' => $userData['name'] ?? 'Guest',
            'imageUrl' => $imageUrl,
        ]);

        return view('aboutus', [
            'role' => session('role'),
            'username' => session('username'),
            'name' => session('name'),
            'imageUrl' => $imageUrl,
        ]);
    }
    

}

