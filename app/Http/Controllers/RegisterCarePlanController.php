<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService; // Make sure to import the FirebaseService
use App\Services\StorageService;
use Illuminate\Http\Request;

class RegisterCarePlanController extends Controller
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
        $imageUrl = $this->storage->getImage($userId);
    
        // Fetch all users from Firebase using FirebaseService
        $users = $this->firebaseService->getAllUsers(); 
        $clients = $this->firebaseService->getUsersByRole();
        $services = $this->firebaseService->getAllServices();
        // Pass the data to the view
        return view('caregiver.registerCarePlan', [
            'userId' => $userId, 
            'username' => $userData['username'] ?? 'Guest',
            'role' => $userData['role_name'] ?? 'Unknown',
            'name' => $userData['name'] ?? 'Unknown',
            'imageUrl' => $imageUrl,
            'services' => $services,
        ],compact('clients'));
    }

    public function store(Request $request)
{
    try {
        // Get the data from the request
        $caregiverId = $request->input('caregiver_id');
        $clientId = $request->input('client_id');
        $careType = $request->input('care_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $services = $request->input('services'); // Assuming an array of services
        $totalCost = $request->input('total_cost');

        date_default_timezone_set('Asia/Kuala_Lumpur');
        $currentDateTime = date('Y-m-d H:i:s'); 
        $database = $this->firebaseService->getDatabase();

        // Validate client existence
        $clientRef = $database->getReference('User/' . $clientId);
        if (!$clientRef->getValue()) {
            return response()->json(['error' => 'Client does not exist.'], 400);
        }

        // Save care plan
        $carePlanRef = $clientRef->getChild('CarePlan');
        $existingCarePlans = $carePlanRef->getValue();

        // Calculate the next index for the new care plan
        $nextIndex = empty($existingCarePlans) ? 1 : max(array_keys($existingCarePlans)) + 1;

        // Reference to the new care plan node
        $newCarePlanRef = $carePlanRef->getChild($nextIndex);
        $newCarePlanRef->set([
            'care_type' => $careType,
            'caregiverID' => $caregiverId,
            'cost' => $totalCost,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'Services' => $services,
            'current_timestamp' => $currentDateTime,
        ]);

        // Save assignment in the assignCaregiver structure
        $assignCaregiverRef = $database->getReference('assignCaregiver/' . $caregiverId);

        // Get existing clients assigned to this caregiver
        $existingClients = $assignCaregiverRef->getValue() ?: [];

        // If the client is not already assigned, add them
        if (!$assignCaregiverRef->getChild($clientId)->getValue()) {
            // Create the node if it doesn't exist and set its priority to 'Low'
            $assignCaregiverRef->getChild($clientId)->set('', ['.priority' => 'Low']);
        }
        

        return response()->json([
            'message' => 'Care plan and caregiver assignment saved successfully.',
            'care_plan_id' => $carePlanRef->getKey(),
            'reload' => true,
        ]);
    } catch (\Exception $e) {
        // Return error response if something goes wrong
        return response()->json(['error' => 'Failed to create care plan: ' . $e->getMessage()], 500);
    }
}



}

