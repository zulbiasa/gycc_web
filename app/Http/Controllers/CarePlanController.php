<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Services\StorageService;

class CarePlanController extends Controller
{
    protected $database;
    protected $storage;

    public function __construct(FirebaseService $firebaseService, StorageService $storage)
    {
        $this->database = $firebaseService;
        $this->storage = $storage;
    }

    public function index(Request $request)
    {
        $searchBy = $request->input('searchBy', 'clientName');
        $selectedClientName = $request->input('clientName', '');
        $selectedCaregiverName = $request->input('caregiverName', '');
        $selectedStatus = $request->input('status', '');
        $view = $request->input('view', 'active'); // Default view is active care plans

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
            'name' => $userData['name'] ?? 'Unknown',
        ]);
    
        // Retrieve all users from Firebase
        $users = $this->database->getAllUsers();
        $carePlans = [];
        $currentTimestamp = strtotime(now());
    
        if ($users) {
            foreach ($users as $userKey => $user) {
                if (isset($user['role']) && $user['role'] === 3 && !empty($user['CarePlan'])) {
                    foreach ($user['CarePlan'] as $planId => $plan) {
                        if (is_null($plan)) {
                            continue;
                        }
    
                        // Determine if the care plan is valid based on dates and services
                        $startDate = isset($plan['start_date']) ? strtotime($plan['start_date']) : null;
                        $endDate = isset($plan['end_date']) ? strtotime($plan['end_date']) : null;
                        $totalServices = isset($plan['Services']) ? count($plan['Services']) : 0;
    
                        // Status is inactive if:
                        // 1. The date is outside the valid range
                        // 2. Total services are 0
                        $isValidDate = $startDate && $endDate && $currentTimestamp >= $startDate && $currentTimestamp <= $endDate;
                        $isValid = $isValidDate && $totalServices > 0;
    
                        // Get caregiver name from users
                        $caregiverName = 'Not Assigned';
                        if (isset($plan['caregiverID']) && isset($users[$plan['caregiverID']])) {
                            $caregiverName = $users[$plan['caregiverID']]['name'] ?? 'Unknown Caregiver';
                        }
    
                        $carePlans[] = [
                            'userId' => $userKey,
                            'planId' => $planId,
                            'clientName' => $user['name'] ?? 'Unknown',
                            'planType' => $plan['care_type'] ?? 'N/A',
                            'startDate' => $plan['start_date'] ?? 'N/A',
                            'endDate' => $plan['end_date'] ?? 'N/A',
                            'status' => $isValid ? 'Active' : 'Inactive',
                            'totalServices' => $totalServices,
                            'caregiverName' => $caregiverName,
                            'isValid' => $isValid, // Add this to sort later
                        ];
                    }
                }
            }
        }
    
        // Apply filters
        $carePlans = array_filter($carePlans, function ($plan) use ($searchBy, $selectedClientName, $selectedCaregiverName, $selectedStatus) {
            if ($searchBy === 'clientName' && stripos($plan['clientName'], $selectedClientName) === false) {
                return false;
            }
            if ($searchBy === 'caregiverName' && stripos($plan['caregiverName'], $selectedCaregiverName) === false) {
                return false;
            }
            if ($searchBy === 'status' && $selectedStatus && $plan['status'] !== $selectedStatus) {
                return false;
            }
            return true;
        });
    
        // Sort the care plans: valid (active) first, invalid (inactive) second
        usort($carePlans, function ($a, $b) {
            return $b['isValid'] <=> $a['isValid']; // Sort by isValid descending
        });
    
         // Filter care plans based on view (active or history)
         if ($view === 'history') {
                $carePlans = array_filter($carePlans, function ($plan) {
                    return $plan['status'] === 'Inactive';
                });
            } else {
                $carePlans = array_filter($carePlans, function ($plan) {
                    return $plan['status'] === 'Active';
                });
            }

        

        // Render the view with the care plans data
        return view('careplan.careplan', [
            'role' => session('role'),
            'username' => session('username'),
            'imageUrl' => session('imageUrl'),
            'carePlans' => $carePlans,
            'name' => session('name'),
            'searchBy' => $searchBy,
            'selectedClientName' => $selectedClientName,
            'selectedCaregiverName' => $selectedCaregiverName,
            'selectedStatus' => $selectedStatus,
            'view' => $view,
        ]);
    }
    
    
/**
 * Fetch the caregiver's name by their ID.
 *
 * @param int $caregiverID
 * @param array $allUsers
 * @return string
 */
    private function getAssignedCaregiverNameById($caregiverID, $allUsers)
    {
        return $allUsers[$caregiverID]['name'] ?? 'Unknown Caregiver';
    }

    public function editCaregiver($userId, $planId, Request $request)
    {
        if (!session('user_id')) {
            return redirect()->route('login');
        }
    
        $adminId = session('user_id');
        $imageUrl = $this->storage->getImage($adminId);
        $adminData = $this->database->getUserDataById($adminId);
    
        session([
            'role' => $adminData['role_name'] ?? 'Unknown',
            'username' => $adminData['username'] ?? 'Guest',
            'imageUrl' => $imageUrl,
            'name' => $adminData['name'] ?? 'Unknown',
        ]);
    
        // Retrieve the client and care plan details
        $user = $this->database->getUserDataById($userId);
        if (!$user || !isset($user['CarePlan'][$planId])) {
            return redirect()->route('careplan.index')->with('error', 'Care plan not found.');
        }
    
        $plan = $user['CarePlan'][$planId];
        $currentDate = strtotime(now());
        $startDate = isset($plan['start_date']) ? strtotime($plan['start_date']) : null;
        $endDate = isset($plan['end_date']) ? strtotime($plan['end_date']) : null;
        $totalServices = isset($plan['Services']) ? count($plan['Services']) : 0;
    
        // Format the dates
        $formattedStartDate = $startDate ? $this->formatDateToString($plan['start_date']) : 'N/A';
        $formattedEndDate = $endDate ? $this->formatDateToString($plan['end_date']) : 'N/A';
    
        // Determine the status of the care plan
        $isValidDate = $startDate && $endDate && $startDate <= $currentDate && $currentDate <= $endDate;
        $isValid = $isValidDate && $totalServices > 0;
    
        $carePlan = [
            'userId' => $userId,
            'planId' => $planId,
            'clientName' => $user['name'] ?? 'Unknown',
            'planType' => $plan['care_type'] ?? 'N/A',
            'startDate' => $formattedStartDate, // Use formatted start date
            'endDate' => $formattedEndDate, // Use formatted end date
            'status' => $isValid ? 'Active' : 'Inactive',
            'totalServices' => $totalServices,
            'caregiverID' => $plan['caregiverID'] ?? null,
            'caregiverName' => isset($plan['caregiverID']) ? $this->getCaregiverNameById($plan['caregiverID']) : 'Not Assigned',
        ];
    
        // Get all users and filter only caregivers (role = 2)
        $allUsers = $this->database->getAllUsers();
        $caregivers = array_filter($allUsers, function ($user) {
            return isset($user['role']) && $user['role'] == 2;
        });
    
        // Calculate costs
        $services = [];
        $totalCost = 0;
        if (isset($plan['Services'])) {
            foreach ($plan['Services'] as $serviceData) {
                $serviceId = $serviceData['serviceId'] ?? null;
                $service = $this->database->getReference("Service/{$serviceId}")->getValue();
    
                if ($service) {
                    $costPerSession = $service['cost'] ?? 0;
                    $sessions = $serviceData['session'] ?? 0;
                    $frequency = $serviceData['frequency'] ?? 'unknown';
    
                    $serviceCost = $costPerSession * $sessions;
                    $totalCost += $serviceCost;
    
                    $services[] = [
                        'service' => $service['service'] ?? 'Unknown',
                        'description' => $service['description'] ?? 'N/A',
                        'frequency' => $frequency,
                        'session' => $sessions,
                        'cost' => $costPerSession,
                    ];
                }
            }
        }
    
        $discount = 0; // Add logic for discount if applicable
        $grandTotal = $totalCost - $discount;
        $paymentStatus = $plan['payment_status'] ?? 'Pending';
    
        // Handle POST request to update caregiver
        if ($request->isMethod('post')) {
            $newCaregiverId = $request->input('caregiver_id');
    
            // Update caregiver assignment in Firebase
            $this->database->getReference("User/{$userId}/CarePlan/{$planId}/caregiverID")->set($newCaregiverId);
    
            return redirect()->route('careplan.index')->with('success', 'Caregiver updated successfully.');
        }
    
        // Return the edit-caregiver view
        return view('careplan.edit-caregiver', [
            'clientName' => $user['name'] ?? 'Unknown',
            'carePlan' => $carePlan,
            'caregivers' => $caregivers,
            'role' => session('role'),
            'username' => session('username'),
            'imageUrl' => $imageUrl,
            'name' => session('name'),
            'services' => $services,
            'totalCost' => $totalCost,
            'discount' => $discount,
            'grandTotal' => $grandTotal,
            'paymentStatus' => $paymentStatus,
        ]);
    }
    
    /**
     * Helper function to format dates as strings.
     */
    private function formatDateToString($date)
    {
        if ($date) {
            return date('j F Y', strtotime($date)); // Example format: "1 January 2025"
        }
        return 'N/A';
    }
    
    
    /**
     * Helper method to get caregiver name by ID
     */
    private function getCaregiverNameById($caregiverId)
    {
        $users = $this->database->getAllUsers();
        return $users[$caregiverId]['name'] ?? 'Unknown Caregiver';
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


    public function delete($userId, $planId)
    {
        // Delete a specific CarePlan
        $this->database->getReference("User/{$userId}/CarePlan/{$planId}")->remove();

        return redirect()->route('careplan.index')->with('success', 'Care Plan deleted successfully.');
    }
}
