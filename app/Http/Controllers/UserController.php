<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Hash;
use App\Services\StorageService;
use Carbon\Carbon;

class UserController extends Controller
{
    protected $firebaseService;
    protected $storage;

    public function __construct(FirebaseService $firebaseService, StorageService $storage)
    {
        $this->firebaseService = $firebaseService;
        $this->storage = $storage;

    }

    // Show the user list
    public function view(Request $request)
    {
        $userId = session('user_id');
    
        if (!$userId) {
            return redirect()->route('login');
        }
    
        $userData = $this->firebaseService->getUserDataById($userId);
    
        if (!$userData) {
            return redirect()->route('login');
        }

        $imageUrl = $this->storage->getImage($userId);
    
        // Set session values for role and username
        session([
            'role' => $userData['role_name'] ?? 'Unknown',
            'username' => $userData['username'] ?? 'Guest',
            'imageUrl' => $imageUrl,
            'name' => $userData['name'] ?? 'Unknown',
        ]);
    
        try {
            // Fetch all users
            $users = $this->firebaseService->getAllUsers();

            // Filter users by search term
            $searchTerm = $request->query('search', null);
            if ($searchTerm) {
                $users = array_filter($users, function ($user) use ($searchTerm) {
                    return stripos($user['name'], $searchTerm) !== false;
                });
            }
    
             // Format users
            $filteredUsers = [];
            foreach ($users as $key => $user) {
                if (isset($user['name']) && !empty($user['name'])) {
                    $user['id'] = $key; // Firebase key as ID
                    $user['role_name'] = $this->firebaseService->getRoleMapping()[$user['role']] ?? 'Unknown';
                    $user['status'] = isset($user['status']) && $user['status'] == 1 ? true : false;
    
                    $filteredUsers[] = $user;
                }
            }

            // Sort users alphabetically by name
            // usort($filteredUsers, function ($a, $b) {
            //     return strcasecmp($a['name'], $b['name']); // Case-insensitive comparison
            // });

            // Sort users: Active users appear first, then sorted alphabetically within each group
            usort($filteredUsers, function ($a, $b) {
                // Sort by status (active first, inactive last)
                if ($a['status'] !== $b['status']) {
                    return $b['status'] - $a['status']; // Active (true) comes before Inactive (false)
                }

                // If the status is the same, sort alphabetically by name
                return strcasecmp($a['name'], $b['name']); // Case-insensitive comparison
            });
    
            // Apply filters
            $searchBy = $request->input('searchBy', 'name'); // Default to 'name' if not set
            $selectedName = $request->input('name', '');
            $selectedRole = $request->input('role', '');
            $selectedStatus = $request->input('status', '');
        
            if ($searchBy === 'name' && $selectedName) {
                $filteredUsers = array_filter($filteredUsers, function ($user) use ($selectedName) {
                    return stripos($user['name'], $selectedName) !== false;
                });
            }
    
            if ($searchBy === 'role' && $selectedRole) {
                $filteredUsers = array_filter($filteredUsers, function ($user) use ($selectedRole) {
                    return strcasecmp($user['role_name'], $selectedRole) === 0;
                });
            }
    
            if ($searchBy === 'status' && $selectedStatus !== null) {
                $filteredUsers = array_filter($filteredUsers, function ($user) use ($selectedStatus) {
                    return $selectedStatus == 1 ? $user['status'] : !$user['status'];
                });
            }
    
            return view('users.view', [
                'users' => $filteredUsers,
                'role' => session('role'),
                'username' => session('username'),
                'imageUrl' => session('imageUrl'),
                'name' => session('name'),
                'selectedName' => $selectedName,
                'selectedRole' => $selectedRole,
                'selectedStatus' => $selectedStatus,
                'searchBy' => $searchBy,
            ]);
    
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch users: ' . $e->getMessage());
        }
    }
   
    // Show the registration form
    public function create()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $userData = $this->firebaseService->getUserDataById($userId);
        if (!$userData) {
            return redirect()->route('login');
        }

        $imageUrl = $this->storage->getImage($userId);

        $role = $userData['role_name'] ?? 'Unknown';
        $username = $userData['username'] ?? 'Guest';
        $name = $userData['name'] ?? 'Guest';

        session([
            'role' => $role,
            'username' => $username,
            'imageUrl' => $imageUrl,
            "name" => $userData['name'] ?? 'Unknown',
        ]);

        // Retrieve dropdown data for health conditions and medications
        $healthConditions = $this->firebaseService->getAllHealthConditions();
        $medications = $this->firebaseService->getAllMedications();

     //   dd($healthConditions, $medications);
        return view('users.create', compact('role', 'username', 'name', 'imageUrl', 'healthConditions', 'medications'));
    }

    // Store user in Firebase
    public function store(Request $request)
    {
        // Log the incoming request for debugging
        \Log::info('Incoming Request Data:', $request->all());

        // Validate the incoming data
        $validated = $request->validate([
            // Common fields
            'name' => 'required|string|max:255',
            'ic_no' => 'required|string|max:12',
            'phone_no' => 'required|string|max:15',
            'dob' => 'required|date',
            'poscode'=> 'required|string|max:5',
            'city'=> 'required|string|max:20',
            'state'=> 'required|string|max:20',
            'address' => 'required|string|max:500',
            'status' => 'required|boolean',
            'gender' => 'required|string|in:Male,Female',
            'username' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|string|in:Admin,Caregiver,Client',

            // Authorized contact person (optional for Admin/Caregiver)
            'contact_name' => 'nullable|string|max:255',
            'contact_ic' => 'nullable|string|max:12',
            'contact_relationship' => 'nullable|string|max:100',
            'contact_phone_no' => 'nullable|string|max:15',

            // Medical & Health Information (only for Client)
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'allergic' => 'nullable|string|in:yes,no',
            'food_allergy' => 'nullable|string|max:255',
            'medicine_allergy' => 'nullable|string|max:255',
            'health_conditions' => 'nullable|string', // JSON string

            'medications' => 'nullable|array',
            'medications.*.id' => 'nullable|string',
            'medications.*.custom_name' => 'required_if:medications.*.id,custom|string|max:255',
            'medications.*.custom_desc' => 'required_if:medications.*.id,custom|string|max:500',
            'medications.*.dosage_info' => 'required|string|max:255',
            'medications.*.total_pills' => 'required|integer|min:1',
            'medications.*.pill_intake' => 'required_with:medications|integer|min:1',
            'medications.*.frequency' => 'required|integer|min:1|max:4',
            'medications.*.start_date' => 'required|date',
            'medications.*.times' => 'required|array',
            'medications.*.times.*' => 'required|date_format:H:i',

            'physical_condition' => 'nullable|string|in:Good,Weak,Bedridden',
            'basic_needs' => 'nullable|string|in:None,Wheelchair,Hearing Aid,Walking Stick,Walker',
        ]);

        // Check for unique username, name, and IC number in Firebase
        $existingUsers = $this->firebaseService->getAllUsers();
        foreach ($existingUsers as $user) {
            if (isset($user['username']) && $user['username'] === $validated['username']) {
                return back()->withErrors(['username' => 'The username has already been taken.'])->withInput();
            }
            if (isset($user['name']) && $user['name'] === $validated['name']) {
                return back()->withErrors(['name' => 'The User already has an account.'])->withInput();
            }
            if (isset($user['ic_no']) && $user['ic_no'] === $validated['ic_no']) {
                return back()->withErrors(['ic_no' => 'The User already has an account.'])->withInput();
            }
        }

        try {
            // Determine the next user ID
            $users = $this->firebaseService->getAllUsers();
            $nextId = $users ? count($users) + 1 : 1;
            if ($users) {
                $existingIds = array_keys($users);
                $numericIds = array_map('intval', $existingIds);
                $nextId = max($numericIds) + 1;
            }

            // Map role to an integer for consistency
            $roleMapping = [
                'Admin' => 1,
                'Caregiver' => 2,
                'Client' => 3,
            ];

            // Prepare common user data
            $dob = Carbon::parse($validated['dob'])->format('Y-m-d');
            $newUser = [
                'userID' => $nextId,
                'name' => $validated['name'],
                'ic_no' => $validated['ic_no'],
                'phone_no' => $validated['phone_no'],
                'dob' => $dob,
                'home_address' => $validated['address'].', '.$validated['poscode'].' '.$validated['city'].', '.$validated['state'].', Malaysia',
                'status' => (bool) $validated['status'],
                'gender' => $validated['gender'],
                'username' => $validated['username'],
                'password' => $validated['password'],
                'role' => $roleMapping[$validated['role']],
            ];

            // Add authorized contact info for Admin, Caregiver, or Client
            $newUser['emergency_contact'] = [
                'name' => $validated['contact_name'] ?? null,
                'ic_no' => $validated['contact_ic'] ?? null,
                'relationship' => $validated['contact_relationship'] ?? null,
                'phone_no' => $validated['contact_phone_no'] ?? null,
            ];

            // Add medical info for Client role
            if ($validated['role'] === 'Client') {
                $newUser['medical_info'] = [
                    'blood_type' => $validated['blood_type'] ?? null,
                    'weight' => $validated['weight'] ?? null,
                    'height' => $validated['height'] ?? null,
                    'allergic' => $validated['allergic'] === 'yes' ? [
                        'food' => $validated['food_allergy'] ?? null,
                        'medicine' => $validated['medicine_allergy'] ?? null,
                    ] : null,
                    'physical_condition' => $validated['physical_condition'] ?? null,
                    'basic_needs' => $validated['basic_needs'] ?? null,
                    'health_conditions' => json_decode($validated['health_conditions'], true) ?? [],
                    'medications' => [],
                ];

                // Process medications
                foreach ($validated['medications'] as $medication) {
                    $startDate = Carbon::parse($medication['start_date']);
                    $totalPills = $medication['total_pills'];
                    $frequency = $medication['frequency'];
                    $pillIntake = $medication['pill_intake'];
                    $daysAvailable = (int) ($totalPills / ($frequency * $pillIntake));
                    $endDate = $startDate->copy()->addDays(intdiv($totalPills, $frequency));

                    $newMedication = isset($medication['custom_name'])
                        ? [
                            'name' => $medication['custom_name'],
                            'description' => $medication['custom_desc'],
                        ]
                        : $this->firebaseService->getMedicationById($medication['id']);

                    if (!$newMedication) {
                        throw new \Exception("Medication ID {$medication['id']} not found.");
                    }

                    $newMedication += [
                        'dosage_info' => $medication['dosage_info'],
                        'total_pills' => $totalPills,
                        'pill_intake' => $pillIntake,
                        'frequency' => $frequency,
                        'times' => $medication['times'],
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate->toDateString(),
                    ];

                    $newUser['medical_info']['medications'][] = $newMedication;
                }
            }

            // Log the prepared user data
            \Log::info('Prepared User Data:', $newUser);

            // Save the user in Firebase
            $this->firebaseService->getDatabase()->getReference("User/{$nextId}")->set($newUser);

            return redirect()->route('users.view')->with('success', 'User registered successfully!');
        } catch (\Exception $e) {
            \Log::error('Error saving user:', ['exception' => $e->getMessage()]);
            return back()->with('error', 'Failed to register user: ' . $e->getMessage());
        }
    }


    // View user details
    public function viewUser($id)
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $userData = $this->firebaseService->getUserDataById($userId);

        if (!$userData) {
            return redirect()->route('login');
        }

        $imageUrl = $this->storage->getImage($userId);

        session([
            'role' => $userData['role_name'] ?? 'Unknown',
            'username' => $userData['username'] ?? 'Guest',
            'imageUrl' => $imageUrl,
            'name' => $userData['name'] ?? 'Guest',
        ]);

        try {
            // Fetch user details by ID
            $users = $this->firebaseService->getAllUsers();
          //  dd($users);
            $user = $users[$id] ?? null;

            if (!$user) {
                return redirect()->route('users.view')->with('error', 'User not found.');
            }
            
            // Assign the ID to the user array
            $user['id'] = $id;      
            // Format DOB for display
            $user['formatted_dob'] = $this->formatDateToString($user['dob']);
            // Map the role name for the user
            $user['role_name'] = $this->firebaseService->getRoleMapping()[$user['role']] ?? 'Unknown';
            //dd($users);
            $clients = [];
            if ($user['role'] == 2) { // Caregiver role
                    $currentTimestamp = strtotime(now());

                    foreach ($users as $key => $potentialClient) {
                    if (isset($potentialClient['CarePlan'])) {
                        foreach ($potentialClient['CarePlan'] as $planId => $carePlan) {
                            if (isset($carePlan['caregiverID']) && $carePlan['caregiverID'] == $id) {
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
                                    'status' => $isValid ? 'Active' : 'Inactive',
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

            // Retrieve dropdown data for health conditions and medications
            $healthConditions = $this->firebaseService->getAllHealthConditions();
            $medications = $this->firebaseService->getAllMedications();

            // Format start_date for each medication
            if (isset($user['medical_info']['medications']) && is_array($user['medical_info']['medications'])) {
                foreach ($user['medical_info']['medications'] as &$medication) {
                    if (isset($medication['start_date'])) {
                        $medication['formatted_start_date'] = $this->formatDateToString($medication['start_date']);
                    }
                }
            }
            // Format start_date for each medication
            if (isset($user['medical_info']['medications']) && is_array($user['medical_info']['medications'])) {
                foreach ($user['medical_info']['medications'] as &$medication) {
                    if (isset($medication['end_date'])) {
                        $medication['formatted_end_date'] = $this->formatDateToString($medication['end_date']);
                    }
                }
            }
           

            return view('users.viewUser', [
                'user' => $user,
                'role' => session('role'),
                'username' => session('username'),
                'imageUrl' => session('imageUrl'),
                'name' => session('name'),
                'healthConditions' => $healthConditions,
                'medications' => $medications,
                'clients' => $clients,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch user details: ' . $e->getMessage());
        }
    }

        private function formatDateToString($date)
        {
            if ($date) {
                return date('j F Y', strtotime($date)); // Format: "1 January 2025"
            }
            return 'N/A';
        }


    // Show the user edit form
    public function edit($id)
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $userData = $this->firebaseService->getUserDataById($userId);

        if (!$userData) {
            return redirect()->route('login');
        }

        $imageUrl = $this->storage->getImage($userId);

        session([
            'role' => $userData['role_name'] ?? 'Unknown',
            'username' => $userData['username'] ?? 'Guest',
            'imageUrl' => $imageUrl,
            'name' => $userData['name'] ?? 'Guest',
        ]);

        try {
            // Fetch user details by ID
            $users = $this->firebaseService->getAllUsers();
            $user = $users[$id] ?? null;

            if (!$user) {
                return redirect()->route('users.view')->with('error', 'User not found.');
            }

            // Assign the ID to the user array
            $user['id'] = $id;

            // Format DOB for display
            $user['formatted_dob'] = $this->formatDateToString($user['dob']);

            // Map the role name for the user
            $user['role_name'] = $this->firebaseService->getRoleMapping()[$user['role']] ?? 'Unknown';

            // Retrieve health conditions for this user
            $healthConditions = $user['medical_info']['health_conditions'] ?? [];
            $mappedHealthConditions = [];
            foreach ($healthConditions as $condition) {
                $mappedHealthConditions[] = [
                    'id' => $condition['id'] ?? null,
                    'name' => $condition['name'] ?? null,
                    'desc' => $condition['desc'] ?? null,
                ];
            }

            // Retrieve all available health conditions for the dropdown
            $allConditions = $this->firebaseService->getAllHealthConditions();

            // Retrieve medications for dropdown and existing user data
            $medications = $this->firebaseService->getAllMedications();

            return view('users.edit', [
                'user' => $user,
                'role' => session('role'),
                'username' => session('username'),
                'imageUrl' => session('imageUrl'),
                'name' => session('name'),
                'healthConditions' => $mappedHealthConditions, // User's existing conditions
                'allConditions' => $allConditions, // For the dropdown
                'medications' => $medications, // Existing and dropdown data
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch user details: ' . $e->getMessage());
        }
    }
    
    // Update user information
    public function update(Request $request)
    {
         // Validate the request data
         $validated = $request->validate([
            'user_id' => 'required|string',
            'name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:15',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|string|in:Admin,Caregiver,Client',
            'status' => 'required|boolean',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'allergic' => 'nullable|string|in:yes,no',
            'food_allergy' => 'nullable|string|max:255',
            'medicine_allergy' => 'nullable|string|max:255',
            'physical_condition' => 'nullable|string|in:Good,Weak,Bedridden',
            'basic_needs' => 'nullable|string|in:None,Wheelchair,Hearing Aid,Walking Stick,Walker',
            'health_conditions' => 'nullable|string', // JSON string
            'medications' => 'nullable|array', // Medications are optional
            'medications.*.id' => 'nullable|integer', 
            'medications.*.custom_name' => 'required_if:medications.*.id,custom|string|max:255',
            'medications.*.custom_desc' => 'required_if:medications.*.id,custom|string|max:500',
            'medications.*.dosage_info' => 'required|string|max:255',
            'medications.*.total_pills' => 'required|integer|min:1',
            'medications.*.pill_intake' => 'required_with:medications|integer|min:1', 
            'medications.*.frequency' => 'required|integer|min:1|max:4',
            'medications.*.start_date' => 'required|date',
            'medications.*.end_date' => 'required|date', // Add end_date validation
            'medications.*.times' => 'required|array',
            'medications.*.times.*' => 'required|date_format:H:i',
            'contact_name' => 'nullable|string|max:255',
            'contact_ic' => 'nullable|string|max:12',
            'contact_relationship' => 'nullable|string|max:100',
            'contact_phone_no' => 'nullable|string|max:15',
        ]);
    
        try {
            // Fetch the user reference from Firebase
            $userRef = $this->firebaseService->getDatabase()->getReference("User/{$validated['user_id']}");
    
            // Retrieve the existing user data
            $existingUserData = $userRef->getValue();
    
            // Map the role to an integer
            $roleMapping = [
                'Admin' => 1,
                'Caregiver' => 2,
                'Client' => 3,
            ];

            // Process health conditions
            $healthConditions = json_decode($validated['health_conditions'], true) ?? [];
            $mappedHealthConditions = array_map(function ($condition) {
                return [
                    'id' => $condition['id'] ?? null,
                    'name' => $condition['name'] ?? null,
                    'desc' => str_replace('Remove', '', $condition['desc'] ?? ''),
                ];
            }, $healthConditions);

             // Process medications
            $updatedMedications = [];
            if (!empty($validated['medications'])) {
                foreach ($validated['medications'] as $index => $medication) {
                    $updatedMedications[] = [
                        'id' => (int) $medication['id'], // Ensure ID is stored as integer
                        'name' => $medication['name'] ?? '',
                        'use' => $medication['use'] ?? '',
                        'dosage_info' => $medication['dosage_info'] ?? '',
                        'total_pills' => (int) $medication['total_pills'], // Convert to integer
                        'pill_intake' => $medication['pill_intake'],
                        'frequency' => (int) $medication['frequency'], // Convert to integer
                        'start_date' => $medication['start_date'] ?? '',
                        'end_date' => $medication['end_date'] ?? '', // Include end_date
                        'times' => $medication['times'] ?? [],
                    ];
                }
            }

            // Prepare updated user data
            $updatedUserData = [
                'name' => $validated['name'],
                'password' => bcrypt($validated['password']), // Hash the password
                'phone_no' => $validated['phone_no'],
                'role' => $roleMapping[$validated['role']],
                'status' => (bool) $validated['status'],
                'emergency_contact' => [
                    'name' => $validated['contact_name'] ?? null,
                    'ic_no' => $validated['contact_ic'] ?? null,
                    'relationship' => $validated['contact_relationship'] ?? null,
                    'phone_no' => $validated['contact_phone_no'] ?? null,
                ],
            ];
    
            // Add medical info only if role is Client
            if ($validated['role'] === 'Client') {
                $updatedUserData['medical_info'] = [
                    'blood_type' => $validated['blood_type'] ?? null,
                    'weight' => $validated['weight'] ?? null,
                    'height' => $validated['height'] ?? null,
                    'allergic' => $validated['allergic'] === 'yes' ? [
                        'food' => $validated['food_allergy'] ?? null,
                        'medicine' => $validated['medicine_allergy'] ?? null,
                    ] : null,
                    'physical_condition' => $validated['physical_condition'] ?? null,
                    'basic_needs' => $validated['basic_needs'] ?? null,
                    'healthConditions' => $mappedHealthConditions,
                    'medications' => $updatedMedications, // Overwrite medications array
                ];
            }
    
            // Log the prepared user data for debugging
            \Log::info('Prepared User Data for Update:', $updatedUserData);
    
              // Handle allergies
            if ($validated['allergic'] === 'yes') {
                $updatedUserData['medical_info']['allergic'] = [
                    'food' => $validated['food_allergy'] ?? null,
                    'medicine' => $validated['medicine_allergy'] ?? null,
                ];
            } else {
                // Remove allergic data only if "No" is saved
                $updatedUserData['medical_info']['allergic'] = null;
            }

            // Update the user data in Firebase
            $userRef->update($updatedUserData);

            
    
            return redirect()->route('users.view')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating user:', ['exception' => $e->getMessage()]);
            return back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }


/**
 * Avoid duplication in health conditions.
 *
 * @param array $existingConditions
 * @param array $newConditions
 * @return array
 */
private function avoidHealthConditionDuplication(array $existingConditions, array $newConditions): array
{
    $existingIds = array_column($existingConditions, 'id');
    foreach ($newConditions as $newCondition) {
        if (!in_array($newCondition['id'], $existingIds)) {
            $existingConditions[] = $newCondition;
        }
    }
    return $existingConditions;
}

/**
 * Avoid duplication in medications.
 *
 * @param array $existingMedications
 * @param array $newMedications
 * @return array
 */
private function avoidMedicationDuplication(array $existingMedications, array $newMedications)
{
    $finalMedications = $existingMedications;

    foreach ($newMedications as $newMed) {
        $duplicate = false;

        foreach ($finalMedications as &$existingMed) {
            if (
                (isset($newMed['id']) && $existingMed['id'] === $newMed['id']) ||
                (isset($newMed['custom_name']) && $existingMed['custom_name'] === $newMed['custom_name'])
            ) {
                // Update all fields, including the end_date
                $existingMed = $newMed;
                $duplicate = true;
                break;
            }
        }

        if (!$duplicate) {
            $finalMedications[] = $newMed;
        }
    }

    return $finalMedications;
}

    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userID = auth()->id(); // or use another method to get user ID
        $imageFile = $request->file('profile_photo');

        $filePath = "User/{$userID}/profile_image." . $imageFile->getClientOriginalExtension();

        // Upload the image to Firebase Storage
        $this->bucket->upload(file_get_contents($imageFile), [
            'name' => $filePath,
            'metadata' => [
                'contentType' => $imageFile->getMimeType(),
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Image uploaded successfully!']);
    }

    public function uploadCroppedImage(Request $request)
    {
        $request->validate([
            'cropped_image' => 'required|file|mimes:jpeg,png|max:5120',
        ]);

        $userID = auth()->id();
        $file = $request->file('cropped_image');

        // Save the cropped image
        $this->storage->setImage($userID, $file);

        return response()->json([
            'success' => true,
        ]);
    }


}
