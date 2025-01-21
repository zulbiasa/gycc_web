<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $firebase;

    public function __construct()
    {
        $databaseUrl = 'https://gycc-group12-default-rtdb.asia-southeast1.firebasedatabase.app'; // Firebase Database URL

        try {
            // Initialize Firebase
            $firebase = (new Factory)
            ->withServiceAccount([
                'type' => 'service_account',
                'project_id' => env('FIREBASE_PROJECT_ID'),
                'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
                'private_key' => str_replace('\n', "\n", env('FIREBASE_PRIVATE_KEY')),
                'client_email' => env('FIREBASE_CLIENT_EMAIL'),
                'client_id' => env('FIREBASE_CLIENT_ID'),
                'auth_uri' => env('FIREBASE_AUTH_URI'),
                'token_uri' => env('FIREBASE_TOKEN_URI'),
                'auth_provider_x509_cert_url' => env('FIREBASE_AUTH_PROVIDER_X509_CERT_URL'),
                'client_x509_cert_url' => env('FIREBASE_CLIENT_X509_CERT_URL'),
                'universe_domain'=> env('FIREBASE_UNIVERSE_DOMAIN'),
            ])
                ->withDatabaseUri($databaseUrl)
                ->createDatabase();

            $this->firebase = $firebase;
        } catch (\Exception $e) {
            echo "Error initializing Firebase: " . $e->getMessage();
            error_log("Firebase initialization error: " . $e->getMessage());
            throw new \Exception("Failed to connect to Firebase Database");
        }
    }

    // Centralized Role Mapping
    public function getRoleMapping()
    {
        return [
            1 => 'Admin',
            2 => 'Caregiver',
            3 => 'Client'
        ];
    }

    public function getUser($username, $password)
    {
        $usersRef = $this->firebase->getReference('User');
        $userSnapshot = $usersRef->getSnapshot();

        if ($userSnapshot->exists()) {
            $users = $userSnapshot->getValue();

            foreach ($users as $userId => $user) {
                if (
                    isset($user['username'], $user['password']) &&
                    $user['username'] === $username &&
                    $user['password'] === $password
                ) {
                    return [
                        'userId' => $userId,
                        'userData' => $user
                    ];
                }
            }
        }

        return null;
    }

    // Get all users
    public function getAllUsers()
    {
        $usersRef = $this->firebase->getReference('User');
        $users = $usersRef->getValue(); // Get all users data from Firebase

        return $users ?? []; // Return an empty array if no users exist
    }

    // Fetch all care logs across all users
    public function getAllCareLogs()
    {
        // Fetch all users from Firebase
        $users = $this->getAllUsers();

        $careLogs = [];

        // Loop through each user and get their care logs
        foreach ($users as $userId => $userData) {
            // Check if the 'CareLog' key exists
            if (isset($userData['CareLog'])) {
                foreach ($userData['CareLog'] as $logId => $logData) {
                    // Add the user name to each care log, handle missing 'username'
                    $careLogs[] = [
                        'userName' => $userData['username'] ?? 'Unknown', // Fallback to 'Unknown'
                        'activity' => $logData['activity'] ?? 'N/A',
                        'date' => $logData['date'] ?? 'N/A',
                        'notes' => $logData['notes'] ?? 'N/A',
                        'status' => $logData['status'] ?? 'N/A',
                    ];
                }
            }
        }

        // Sort care logs by date in descending order
        usort($careLogs, function ($a, $b) {
            $dateA = strtotime($a['date']);
            $dateB = strtotime($b['date']);
            return $dateB - $dateA; // Sort in descending order
        });

        return $careLogs ?: []; // Return care logs or an empty array if no data
    }

    public function getUserDataById($userId)
    {
        $usersRef = $this->firebase->getReference('User');
        $userSnapshot = $usersRef->getChild($userId)->getSnapshot();

        if ($userSnapshot->exists()) {
            $userData = $userSnapshot->getValue();

            // Get role mapping from the centralized method
            $roleMapping = $this->getRoleMapping();

            // Add the role name to the user data
            $userData['role_name'] = $roleMapping[$userData['role']] ?? 'Unknown';

            return $userData;
        }

        return null;
    }

    public function getAllServices()
    {
        $servicesRef = $this->firebase->getReference('Service');
        $services = $servicesRef->getValue();
        $serviceCategories = $this->getAllServiceCategories();
    
        if (is_array($services)) {
            foreach ($services as $key => &$service) {
                $service['id'] = $key; // Add the Firebase key as the ID
                $serviceCategoryID = $service['serviceCategoryID'] ?? null;
                $service['category'] = $serviceCategoryID !== null && isset($serviceCategories[$serviceCategoryID])
                    ? $serviceCategories[$serviceCategoryID]['category']
                    : 'Unknown';
            }
        }
    
        return $services;
    }
    
    public function getAllServiceCategories()
    {
        $categoriesRef = $this->firebase->getReference('ServiceCategory');
        $categories = $categoriesRef->getValue();

        // Ensure valid data structure
        if (is_array($categories)) {
            return array_filter($categories, function ($category) {
                return isset($category['category']); // Filter out entries without 'category'
            });
        }

        return [];
    }

    public function getAllHealthConditions()
    {
        $healthConditionsRef = $this->firebase->getReference('HealthConditions');
        $healthConditions = $healthConditionsRef->getValue();
    
        if (is_array($healthConditions)) {
            $filteredHealthConditions = [];
            foreach ($healthConditions as $id => $condition) {
                // Filter out entries without 'name' or meaningful data
                if (isset($condition['name']) && !empty($condition['name'])) {
                    $condition['id'] = $id; // Add the ID
                    $filteredHealthConditions[] = $condition;
                }
            }
            return $filteredHealthConditions;
        }
    
        return [];
    }
    
    
    public function getAllMedications()
    {
        $medicationsRef = $this->firebase->getReference('Medication');
        $medications = $medicationsRef->getValue();
    
        if (is_array($medications)) {
            $filteredMedications = [];
            foreach ($medications as $id => $medication) {
                // Filter out entries without 'name' or meaningful data
                if (isset($medication['name']) && !empty($medication['name'])) {
                    $medication['id'] = $id; // Add the ID
                    $filteredMedications[] = $medication;
                }
            }
            return $filteredMedications;
        }
    
        return [];
    }


    public function getDatabase()
    {
        return $this->firebase;
    }

    public function getReference($path)
    {
        return $this->firebase->getReference($path);
    }

    public function storeClientData(array $data)
    {
        try {
            // Get the last user ID from Firebase to increment
            $usersRef = $this->firebase->getReference('User');
            $users = $usersRef->getValue();
    
            // Calculate the new user ID by getting the highest current ID and adding 1
            $newUserId = ($users) ? max(array_keys($users)) + 1 : 1;
    
            // Reference to the "users" node in Firebase
            $ref = $this->firebase->getReference('User/' . $newUserId);
    
            // Store the client data under the new user ID
            $ref->set([
                'name' => $data['full_name'],
                'ic_no' => $data['ic_number'],
                'phone_no' => $data['phone_number'],
                'date_of_birth' => $data['dob'],
                'address' => $data['home_address'],
                'status' => $data['status'],
                'gender' => $data['gender'],
                //'profile_picture' => $data['profile_picture'],
            ]);
    
            // Log success
            \Log::info('Client registration successful', ['userId' => $newUserId]);
    
            return $newUserId;
        } catch (\Exception $e) {
            // Log failure
            \Log::error('Error saving to Firebase', ['error' => $e->getMessage()]);
        }
    }
    
    public function getClientsForCaretaker($userId)
{
    // Reference to the 'User' node
    $usersRef = $this->firebase->getReference('User');

    // Get all users
    $users = $usersRef->getValue();
    if (!$users) {
        return []; // Return an empty array if no users exist
    }

    // Filter users based on the caregiverID in their CarePlan
    $filteredClients = [];
    foreach ($users as $userIdKey => $userData) {
        if (isset($userData['CarePlan']) && is_array($userData['CarePlan'])) {
            // Check if any CarePlan has a matching caregiverID
            $hasMatchingCaregiver = collect($userData['CarePlan'])->contains(function ($carePlan) use ($userId) {
                return isset($carePlan['caregiverID']) && $carePlan['caregiverID'] == $userId;
            });

            if ($hasMatchingCaregiver) {
                $filteredClients[$userIdKey] = $userData;
            }
        }
    }

    return $filteredClients; // Return the filtered clients
}

    public function getCarePlansByCaregiver($caregiverId)
    {
        $userRef = $this->firebase->getReference('User');
        $serviceRef = $this->firebase->getReference('Service'); // Reference to Service node
        $users = $userRef->getValue();
        $services = $serviceRef->getValue(); // Get all service details from the Service node
    
        $filteredPlans = [];
        $today = date('Y-m-d'); // Ensure today's date is in the correct format
    
        if ($users) {
            foreach ($users as $userId => $userData) {
                if (isset($userData['CarePlan'])) {
                    $latestCarePlan = null;
    
                    foreach ($userData['CarePlan'] as $planId => $plan) {
                        // Check if caregiverID exists and matches the given caregiver ID
                        if (isset($plan['caregiverID']) && $plan['caregiverID'] == $caregiverId) {
                            // Check if the end_date is greater than or equal to today's date
                            if (isset($plan['end_date']) && strtotime($plan['end_date']) >= strtotime($today)) {
                                // If it's the first CarePlan or has a later end_date, select it
                                if (!$latestCarePlan || strtotime($plan['end_date']) > strtotime($latestCarePlan['end_date'])) {
                                    $latestCarePlan = $plan;
                                    $latestCarePlan['planId'] = $planId; // Keep track of the plan ID
                                }
                            }
                        }
                    }
    
                    // If a valid CarePlan with a valid end_date was found, process it
                    if ($latestCarePlan) {
                        $servicesDetails = [];
    
                        // Iterate through the services in the care plan
                        if (isset($latestCarePlan['Services']) && is_array($latestCarePlan['Services'])) {
                            foreach ($latestCarePlan['Services'] as $service) {
                                $serviceId = $service['serviceId'] ?? null;
                                $frequency = $service['frequency'] ?? null;
    
                                // Retrieve the service details from the 'Service' node using serviceId
                                if ($serviceId && isset($services[$serviceId])) {
                                    // Get the service name and description from the Service node
                                    $servicesDetails[] = [
                                        'serviceId' => $serviceId,
                                        'service' => $services[$serviceId]['service'] ?? 'Unknown',
                                        'description' => $services[$serviceId]['description'] ?? 'No description available',
                                        'cost' => $services[$serviceId]['cost'] ?? 'Unknown',
                                        'frequency' => $frequency,
                                    ];
                                }
                            }
                        }
    
                        $filteredPlans[] = array_merge([
                            'name' => $userData['name'] ?? 'Unknown', // Replace userId with name
                            'care_plan_id' => $latestCarePlan['planId'],
                            'clientId' => $userId,
                            'services' => $servicesDetails, // Include service details
                        ], $latestCarePlan);
                    }
                }
            }
        }
    
        return $filteredPlans;
    }
    
    public function updateClient($id, array $data)
    {
        $clientRef = $this->firebase->getReference('User/' . $id);
    
        // Check if the client exists
        if (!$clientRef->getValue()) {
            logger('Client not found in Firebase for ID: ' . $id);
            throw new \Exception('Client not found.');
        }
    
        // Update the client data
        $clientRef->update($data);
    }
    
    public function getUsersByRole() // Getting all clients
{
    // Reference to the 'User' node in Firebase
    $clientsRef = $this->firebase->getReference('User');

    // Fetch all clients from Firebase
    $clients = $clientsRef->getValue();

    if (!$clients) {
        logger('No clients found in Firebase');
        return []; // Return an empty array if no clients exist
    }

    // Get today's date
    $today = date('Y-m-d');

    // Filter clients with role = 3 and valid CarePlan
    $filteredClients = [];
    foreach ($clients as $key => $client) {
        if (
            isset($client['role']) && $client['role'] == 3 && // Role is 3
            (
                !isset($client['CarePlan']) || // No CarePlan at all
                array_reduce($client['CarePlan'], function ($carry, $carePlan) use ($today) {
                    // Check if any CarePlan entry has an 'end_date' that is greater than today's date
                    return $carry && (isset($carePlan['end_date']) && strtotime($carePlan['end_date']) <= strtotime($today));
                }, true) // Start with 'true' to ensure all care plans are checked
            )
        ) {
            $filteredClients[$key] = $client; // Add to the filtered list
        }
    }

    return $filteredClients;
}


public function getCareLog($userId)
{
    // Fetch users data from Firebase (User -> 20 -> Reminder -> medicineRemind)
    $usersRef = $this->firebase->getReference('User');
    $users = $usersRef->getValue(); // Fetch all users from Firebase

    $usersWithReminders = []; // Result array for filtered users with reminders

    if ($users) {
        foreach ($users as $userKey => $userData) {
            // Check if the user has a caregiverID matching the given $userId
            if (
                isset($userData['CarePlan']) &&
                collect($userData['CarePlan'])->contains(function ($carePlan) use ($userId) {
                    return isset($carePlan['caregiverID']) && $carePlan['caregiverID'] == $userId;
                })
            ) {
                // Check if 'Reminder' and 'medicineRemind' exist
                if (isset($userData['Reminder']['medicineRemind']) && !empty($userData['Reminder']['medicineRemind'])) {
                    // Sort reminders by actionDate in descending order
                    $sortedReminders = collect($userData['Reminder']['medicineRemind'])->sortByDesc(function ($careLog) {
                        return \Carbon\Carbon::parse($careLog['date']);
                    })->toArray();

                    // Add the user details and their sorted reminders
                    $usersWithReminders[$userKey] = [
                        'userDetails' => $userData,
                        'reminders' => $sortedReminders,
                    ];
                }
            }
        }
    }
  

    // Return the filtered users with sorted reminders
    return $usersWithReminders;
}

}