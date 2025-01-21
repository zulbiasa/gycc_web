<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $firebase;

    public function __construct()
    {
        $serviceAccountPath = storage_path('firebase/firebase_credentials.json'); // Path to your service account JSON file
        $databaseUrl = 'https://gycc-group12-default-rtdb.asia-southeast1.firebasedatabase.app'; // Firebase Database URL

        try {
            // Initialize Firebase
            $firebase = (new Factory)
                ->withServiceAccount($serviceAccountPath)
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
    

    // public function getAllServiceCategories()
    // {
    //     $categoriesRef = $this->firebase->getReference('ServiceCategory');
    //     $categories = $categoriesRef->getValue();

    //     return $categories ?? []; // Return an empty array if no categories exist
    // }

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

    public function getDatabase()
    {
        return $this->firebase;
    }

    public function getReference($path)
    {
        return $this->firebase->getReference($path);
    }
}
