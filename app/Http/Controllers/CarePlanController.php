<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Services\StorageService;

class CarePlanController extends Controller
{
    protected $database;

    public function __construct(FirebaseService $firebaseService, StorageService $storage)
    {
        $this->database = $firebaseService;
        $this->storage = $storage;
    }

    public function index(Request $request)
{
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->route('login');
    }

    // Retrieve user-specific information
    $imageUrl = $this->storage->getImage($userId);
    $userData = $this->database->getUserDataById($userId);

    // Store session details
    session([
        'role' => $userData['role_name'] ?? 'Unknown',
        'username' => $userData['username'] ?? 'Guest',
        'imageUrl' => $imageUrl,
    ]);

    // Retrieve all users from Firebase
    $users = $this->database->getAllUsers();
    $carePlans = [];

    // Filter care plans for users with role 3 (Client)
    if ($users) {
        foreach ($users as $userKey => $user) {
            if (isset($user['role']) && $user['role'] === 3 && !empty($user['CarePlan'])) {
                foreach ($user['CarePlan'] as $planId => $plan) {
                    // Ensure the plan is not null
                    if (is_null($plan)) {
                        continue;
                    }

                    $carePlans[] = [
                        'userId' => $userKey,
                        'planId' => $planId,
                        'clientName' => $user['name'] ?? 'Unknown',
                        'planType' => $plan['care_type'] ?? 'N/A',
                        'startDate' => $plan['start_date'] ?? 'N/A',
                        'endDate' => $plan['end_date'] ?? 'N/A',
                        'status' => isset($user['status']) ? ($user['status'] ? 'Active' : 'Inactive') : 'Unknown',
                        'totalServices' => isset($plan['Services']) ? count($plan['Services']) : 0,
                        'caregiverName' => $this->getAssignedCaregiverName($user, $users)
                    ];
                }
            }
        }
    }

    // Render the view with the care plans data
    return view('careplan.careplan', [
        'role' => session('role'),
        'username' => session('username'),
        'imageUrl' => session('imageUrl'),
        'carePlans' => $carePlans,
    ]);
}

/**
 * Get the assigned caregiver's name for a client.
 *
 * @param array $user
 * @param array $allUsers
 * @return string
 */
private function getAssignedCaregiverName($user, $allUsers)
{
    if (isset($user['assignCaregiver']) && is_array($user['assignCaregiver'])) {
        foreach ($user['assignCaregiver'] as $assignment) {
            if (!is_null($assignment) && isset($assignment['caretakerID'])) {
                $caretakerID = $assignment['caretakerID'];
                // Fetch caregiver details from the list of users
                if (isset($allUsers[$caretakerID])) {
                    return $allUsers[$caretakerID]['name'] ?? 'Unknown Caregiver';
                }
            }
        }
    }
    return 'Not Assigned';
}


public function edit($userId, $planId)
{
    $user = $this->database->getUserDataById($userId);

    if (!$user) {
        return redirect()->route('careplan.index')->with('error', 'User not found.');
    }

    $plan = $user['CarePlan'][$planId] ?? null;

    if (!$plan) {
        return redirect()->route('careplan.index')->with('error', 'Care plan not found.');
    }

    $carePlan = [
        'userId' => $userId,
        'planId' => $planId,
        'clientName' => $user['name'] ?? 'Unknown',
        'planType' => $plan['care_type'] ?? '',
        'startDate' => $plan['start_date'] ?? '',
        'endDate' => $plan['end_date'] ?? '',
        'status' => $user['status'] ?? 'Inactive',
        'totalServices' => isset($plan['Services']) ? count($plan['Services']) : 0,
        'caregiverName' => $this->getAssignedCaregiverName($user, $this->database->getAllUsers()),
    ];

    return view('careplan.edit', [
        'role' => session('role'),
        'username' => session('username'),
        'imageUrl' => session('imageUrl'),
        'carePlan' => $carePlan,
    ]);
}

public function update(Request $request, $id, $userId)
{
    $this->database->updateCarePlan($userId, $id, [
        'care_type' => $request->input('planType'),
        'start_date' => $request->input('startDate'),
        'end_date' => $request->input('endDate'),
        'status' => $request->input('status') == 1,
    ]);

    return redirect()->route('careplan.index')->with('success', 'Care plan updated successfully.');
}


    public function delete($userId, $planId)
    {
        // Delete a specific CarePlan
        $this->database->getReference("User/{$userId}/CarePlan/{$planId}")->remove();

        return redirect()->route('careplan.index')->with('success', 'Care Plan deleted successfully.');
    }
}
