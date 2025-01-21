<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;


class ServiceController extends Controller
{
    protected $firebaseService;

    // Inject FirebaseService into the constructor
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    // Display all services with categories
    public function viewServices(Request $request)
    {
        $userId = session('user_id');
    
        if (!$userId) {
            return redirect()->route('login');
        }
    
        $userData = $this->firebaseService->getUserDataById($userId);
    
        if (!$userData) {
            return redirect()->route('login');
        }
    
        session([
            'role' => $userData['role_name'] ?? 'Unknown',
            'username' => $userData['username'] ?? 'Guest',
        ]);
    
        try {
            $services = $this->firebaseService->getAllServices();
            $serviceCategories = $this->firebaseService->getAllServiceCategories();
    
            $filteredServices = [];
    
            // Process services
            foreach ($services as $key => $service) {
                // Ensure the service has a valid ID and is complete
                if (!isset($key) || empty($service['service'])) {
                    continue;
                }
    
                $service['id'] = $key;
                $service['service'] = $service['service'] ?? 'Unknown Service';
                $service['description'] = $service['description'] ?? 'No description available';
                $service['cost'] = $service['cost'] ?? 0;
                $service['location'] = $service['location'] ?? 'Unknown Location';
                $service['status'] = isset($service['status']) && $service['status'] == 1 ? true : false;
    
                $categoryID = $service['serviceCategoryID'] ?? null;
                $service['category'] = $categoryID !== null && isset($serviceCategories[$categoryID])
                    ? $serviceCategories[$categoryID]['category']
                    : 'No Category';
    
                $filteredServices[] = $service;
            }
    
            // Normalize categories
            $normalizedCategories = [];
            if (is_array($serviceCategories)) {
                foreach ($serviceCategories as $category) {
                    if (isset($category['category'])) {
                        $normalizedCategories[] = $category;
                    }
                }
            }
    
            // Apply filters
            $searchBy = $request->query('searchBy', null);
            $selectedService = $request->query('service', null);
            $selectedCategory = $request->query('category', null);
            $selectedStatus = $request->query('status', null);
    
            if ($searchBy === 'service' && $selectedService) {
                $filteredServices = array_filter($filteredServices, function ($service) use ($selectedService) {
                    return strcasecmp($service['service'], $selectedService) === 0;
                });
            }
    
            if ($searchBy === 'category' && $selectedCategory) {
                $filteredServices = array_filter($filteredServices, function ($service) use ($selectedCategory) {
                    return strcasecmp($service['category'], $selectedCategory) === 0;
                });
            }
    
            if ($searchBy === 'status' && $selectedStatus !== null) {
                $filteredServices = array_filter($filteredServices, function ($service) use ($selectedStatus) {
                    return $selectedStatus == 1 ? $service['status'] : !$service['status'];
                });
            }
    
            return view('services.viewServices', [
                'services' => $filteredServices,
                'serviceCategories' => $normalizedCategories,
                'role' => session('role'),
                'username' => session('username'),
                'selectedService' => $selectedService,
                'selectedCategory' => $selectedCategory,
                'selectedStatus' => $selectedStatus,
                'searchBy' => $searchBy,
            ]);
    
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch services: ' . $e->getMessage());
        }
    }
    

    public function edit($id)
        {
            $userId = session('user_id');

            if (!$userId) {
                return redirect()->route('login');
            }

            try {
                $services = $this->firebaseService->getAllServices();
                $serviceCategories = $this->firebaseService->getAllServiceCategories();
                $service = $services[$id] ?? null;

                if (!$service) {
                    return redirect()->route('services.view')->with('error', 'Service not found.');
                }

                // Normalize service categories to avoid null errors
                $normalizedCategories = [];
                foreach ($serviceCategories as $key => $category) {
                    if (isset($category['category'])) {
                        $normalizedCategories[$key] = $category;
                    }
                }

                $service['id'] = $id;
                $service['category'] = $service['serviceCategoryID'] ?? 'Unknown';

                return view('services.edit', [
                    'service' => $service,
                    'serviceCategories' => $normalizedCategories,
                    'role' => session('role'),
                    'username' => session('username'),
                ]);
                
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to fetch service details: ' . $e->getMessage());
            }

        }


    public function update(Request $request, $id)
        {
            $validated = $request->validate([
                'service' => 'required|string|max:255',
                'category' => 'required|string',
                'cost' => 'required|numeric|min:0',
                'status' => 'required|boolean',
            ]);

            try {
                $this->firebaseService->getDatabase()
                    ->getReference("Service/{$id}")
                    ->update($validated);

                return redirect()->route('services.view')->with('success', 'Service updated successfully.');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to update service: ' . $e->getMessage());
            }
        }

        public function store(Request $request)
        {
            // Validate input data
            $validated = $request->validate([
                'service' => 'required|string|max:255',
                'category' => 'required|string',
                'description' => 'required|string',
                'cost' => 'required|numeric|min:0',
                'location' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);
        
            try {
                // Retrieve all services to determine the next auto-increment ID
                $services = $this->firebaseService->getAllServices();
                $nextId = 1; // Default ID for the first service
        
                if ($services) {
                    $existingIds = array_keys($services);
                    $numericIds = array_map('intval', $existingIds); // Ensure keys are integers
                    $nextId = max($numericIds) + 1; // Determine the next ID
                }
        
                // Retrieve all categories to match the ServiceCategoryID
                $serviceCategories = $this->firebaseService->getAllServiceCategories();
                $categoryId = null;
        
                foreach ($serviceCategories as $key => $category) {
                    if (strcasecmp($category['category'], $validated['category']) === 0) {
                        $categoryId = $key; // Match found, use the ServiceCategoryID
                        break;
                    }
                }
        
                if (!$categoryId) {
                    return back()->with('error', 'Invalid category selected.');
                }
        
                // Prepare the data to save
                $newService = [
                    'service' => $validated['service'],
                    'description' => $validated['description'],
                    'cost' => $validated['cost'],
                    'location' => $validated['location'],
                    'status' => $validated['status'],
                    'serviceCategoryID' => $categoryId, // Save the matched ServiceCategoryID
                ];
        
                // Save the new service to Firebase
                $this->firebaseService->getDatabase()
                    ->getReference("Service/{$nextId}")
                    ->set($newService);
        
                return redirect()->route('services.view')->with('success', 'Service added successfully.');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to add service: ' . $e->getMessage());
            }
        }
        

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
        
            $role = $userData['role_name'] ?? 'Unknown';
            $username = $userData['username'] ?? 'Guest';
        
            session([
                'role' => $role,
                'username' => $username,
            ]);
        
            $serviceCategories = $this->firebaseService->getAllServiceCategories();
        
            return view('services.create', compact('serviceCategories', 'role', 'username'));
        }
        

        /**
     * Fetch services from Firebase and return as JSON.
     */
    public function fetchServices()
    {
        $services = $this->firebaseService->getAllServices();
    
        // Filter active services
        $filteredServices = array_filter($services, function ($service) {
            return isset($service['status']) && $service['status'];
        });
    
        return response()->json($filteredServices);
    }
    
        /**
         * Render the services selection page.
         */
        public function index()
        {
            return view('service');
        }
}
