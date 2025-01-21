<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Services\StorageService;
use Carbon\Carbon;

class AccountController extends Controller
{
    protected $firebaseService;
    protected $storage;

    public function __construct(FirebaseService $firebaseService, StorageService $storage)
    {
        $this->firebaseService = $firebaseService;
        $this->storage = $storage;
    }

    /**
     * View My Account
     */
    public function viewAccount()
    {
        $userId = session('user_id'); // Fetch the logged-in user's ID
        $role = session('role'); // Logged-in user's role

        if (!$userId) {
            return redirect()->route('login'); // Redirect to login if no user ID in session
        }
    
        $userData = $this->firebaseService->getUserDataById($userId); // Fetch user data from Firebase
        if (!$userData) {
            return redirect()->route('login'); // Redirect to login if user data not found
        }
    
        $imageUrl = $this->storage->getImage($userId);
    
        // Set session data
        session([
            'role' => $userData['role_name'] ?? 'Unknown',
            'username' => $userData['username'] ?? 'Guest',
            'imageUrl' => $imageUrl,
            'name' => $userData['name'] ?? 'Guest',
        ]);
    
        // Format the date of birth
        $userData['formatted_dob'] = $this->formatDateToString($userData['dob'] ?? null);
    
        $clients = [];
        if ($userData['role'] == 2) { // Caregiver role
            // Retrieve all users to determine caregiving assignments
            $users = $this->firebaseService->getAllUsers();
            $currentTimestamp = strtotime(now());
    
            foreach ($users as $key => $potentialClient) {
                if (isset($potentialClient['CarePlan'])) {
                    foreach ($potentialClient['CarePlan'] as $planId => $carePlan) {
                        if (isset($carePlan['caregiverID']) && $carePlan['caregiverID'] == $userId) {
                            // Date validation
                            $startDate = isset($carePlan['start_date']) ? strtotime($carePlan['start_date']) : null;
                            $endDate = isset($carePlan['end_date']) ? strtotime($carePlan['end_date']) : null;
                            $totalServices = isset($carePlan['Services']) ? count($carePlan['Services']) : 0;
    
                            // Determine status
                            $isValidDate = $startDate && $endDate && $currentTimestamp >= $startDate && $currentTimestamp <= $endDate;
                            $isValid = $isValidDate && $totalServices > 0;
                            $status = $isValid ? 'Active' : 'Inactive';
    
                            $clients[] = [
                                'name' => $potentialClient['name'],
                                'care_type' => $carePlan['care_type'] ?? 'N/A',
                                'start_date' => $this->formatDateToString($carePlan['start_date'] ?? null),
                                'end_date' => $this->formatDateToString($carePlan['end_date'] ?? null),
                                'status' => $status,
                                'user_id' => $key,
                                'plan_id' => $planId,
                            ];
                        }
                    }
                }
            }
        }
    
        // Sort clients: Active first, Inactive last
        usort($clients, function ($a, $b) {
            if ($a['status'] === $b['status']) {
                return 0; // If statuses are the same, maintain order
            }
            return $a['status'] === 'Active' ? -1 : 1; // 'Active' comes before 'Inactive'
        });
    
        return view('myaccount.view', [
            'user' => $userData,
            'role' => session('role'),
            'username' => session('username'),
            'imageUrl' => session('imageUrl'),
            'name' => session('name'),
            'clients' => $clients,
        ]);
    }
    
    private function formatDateToString($date)
    {
        if ($date) {
            return date('j F Y', strtotime($date)); // Format: "1 January 2025"
        }
        return 'N/A';
    }
    

    /**
     * Edit My Account
     */
    public function editAccount()
{
    $userId = session('user_id'); // Fetch the logged-in user's ID

    if (!$userId) {
        return redirect()->route('login'); // Redirect to login if no user ID in session
    }

    $userData = $this->firebaseService->getUserDataById($userId); // Fetch user data from Firebase

    if (!$userData) {
        return redirect()->route('login'); // Redirect to login if user data not found
    }

    $imageUrl = $this->storage->getImage($userId);

    // Set session data
    session([
        'role' => $userData['role_name'] ?? 'Unknown',
        'username' => $userData['username'] ?? 'Guest',
        'imageUrl' => $imageUrl,
        'name' => $userData['name'] ?? 'Guest',
    ]);

    // Add the `id` field explicitly
    $userData['id'] = $userId;

    // Format DOB for display (if required, even if not editable)
    $userData['formatted_dob'] = $this->formatDateToString($userData['dob'] ?? null);

    return view('myaccount.edit', [
        'user' => $userData,
        'role' => session('role'),
        'username' => session('username'),
        'imageUrl' => session('imageUrl'),
        'name' => session('name'),
    ]);
}

    /**
     * Update My Account
     */
    public function updateAccount(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|string',
        'phone_no' => 'required|string|max:15',
        'password' => 'nullable|string|confirmed|min:6',
        'status' => 'required|boolean',
        'home_address' => 'nullable|string|max:500',
        'contact_name' => 'nullable|string|max:255',
        'contact_ic' => 'nullable|string|max:12',
        'contact_relationship' => 'nullable|string|max:100',
        'contact_phone_no' => 'nullable|string|max:15',
    ]);

    try {
        // Get Firebase reference for the user
        $userRef = $this->firebaseService->getDatabase()->getReference("User/{$validated['user_id']}");
        
        // Retrieve existing user data
        $existingUserData = $userRef->getValue();

        if (!$existingUserData) {
            return back()->with('error', 'User not found.');
        }

        // Prepare updated user data
        $updatedUserData = [
            'phone_no' => $validated['phone_no'],
            'status' => (bool) $validated['status'],
            'home_address' => $validated['home_address'] ?? $existingUserData['home_address'],
            'password' => $validated['password'] ?? $existingUserData['password'],
            'emergency_contact' => [
                'name' => $validated['contact_name'] ?? $existingUserData['emergency_contact']['name'] ?? null,
                'ic_no' => $validated['contact_ic'] ?? $existingUserData['emergency_contact']['ic_no'] ?? null,
                'relationship' => $validated['contact_relationship'] ?? $existingUserData['emergency_contact']['relationship'] ?? null,
                'phone_no' => $validated['contact_phone_no'] ?? $existingUserData['emergency_contact']['phone_no'] ?? null,
            ],
        ];

        // Merge unchanged fields (preserve any fields not updated in the request)
        $finalUserData = array_merge($existingUserData, $updatedUserData);

        // Update the user data in Firebase
        $userRef->set($finalUserData);

        return redirect()->route('myaccount.view')->with('success', 'User updated successfully!');
    } catch (\Exception $e) {
        \Log::error('Error updating user:', ['exception' => $e->getMessage()]);
        return back()->with('error', 'Failed to update user: ' . $e->getMessage());
    }
}

    /**
     * Format date to string for display.
     */
    // private function formatDateToString($date)
    // {
    //     if ($date) {
    //         try {
    //             return Carbon::parse($date)->format('j F Y'); // Example: "1 January 2025"
    //         } catch (\Exception $e) {
    //             return 'N/A'; // Handle parsing errors
    //         }
    //     }
    //     return 'N/A';
    // }
    
}
