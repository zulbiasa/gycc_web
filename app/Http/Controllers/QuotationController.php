<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class QuotationController extends Controller
{
    protected $firebase;
    protected $storage;

    public function __construct(FirebaseService $firebase, StorageService $storage)
    {
        $this->firebase = $firebase;
        $this->storage = $storage;
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'services' => 'required|array',
            'totalCost' => 'required|numeric'
        ]);

        // Get data
        $data = $request->only(['name', 'email', 'phone', 'services', 'totalCost']);

        try {
            // Fetch admins from Firebase
            $usersRef = $this->firebase->getReference('User');
            $usersSnapshot = $usersRef->getValue();

            $admins = [];
            foreach ($usersSnapshot as $key => $user) {
                if (isset($user['role']) && $user['role'] == 1) {
                    $admins[] = ['id' => $key, 'name' => $user['name']];
                }
            }

            // Check if there are any admins
            if (empty($admins)) {
                return response()->json(['error' => 'No admin available to assign the quotation.'], 400);
            }

            // Select a random admin
            $randomAdmin = $admins[array_rand($admins)];

            // Add admin and status to data
            $data['assignedAdmin'] = $randomAdmin;
            $data['status'] = 'Pending';

            // Get the quotations reference
            $quotationsRef = $this->firebase->getReference('Quotations');
            
            // Check if quotations exist by fetching the first item (this avoids ordering and helps in case of no quotations)
            $quotationsSnapshot = $quotationsRef->getSnapshot();

            // If no quotations exist, set nextKey to 1
            if (!$quotationsSnapshot->getValue()) {
                $nextKey = 1;
            } else {
                // Get the last key and calculate the next key
                $lastKey = $quotationsRef->orderByKey()->limitToLast(1)->getSnapshot();
                $lastKey = $lastKey->getValue() ? array_key_last($lastKey->getValue()) : 0;
                $nextKey = (int)$lastKey + 1;
            }


            // Push data to Firebase
            $quotationsRef->getChild($nextKey)->set($data);

            return response()->json([
                'message' => 'Quotation submitted successfully!',
                'assignedAdmin' => $randomAdmin['name']
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error in storing quotation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to submit quotation.', 'details' => $e->getMessage()], 500);
        }
    }

    

    public function index()
{
    $userId = session('user_id');

    // If the user is not logged in, redirect to login page
    if (!$userId) {
        return redirect()->route('login')->with('error', 'User not authenticated');
    }

    // Fetch the user data from Firebase using the userId
    $userData = $this->firebase->getUserDataById($userId);

    // If userData is not found, redirect to login
    if (!$userData) {
        return redirect()->route('login');
    }

    $imageUrl = $this->storage->getImage($userId);

    try {
        $quotationsRef = $this->firebase->getReference('Quotations');
        $quotations = $quotationsRef->getValue() ?? [];

        $pendingQuotations = [];
        $completedQuotations = [];
        $totalQuotations = 0;
        $successfulQuotations = 0;

        foreach ($quotations as $key => $quotation) {
            // Check if the quotation belongs to the logged-in user
            if (isset($quotation['assignedAdmin']) && $quotation['assignedAdmin'] === $userId) {
                // Only process quotations belonging to the logged-in user
                if (is_array($quotation)) {
                    if ($quotation['status'] === 'Pending') {
                        $pendingQuotations[$key] = $quotation;
                    } elseif ($quotation['status'] === 'Completed') {
                        $completedQuotations[$key] = $quotation;
                    }

                    // Calculate success rate for completed quotations
                    if (isset($quotation['negotiation_status']) && $quotation['negotiation_status'] !== 'Not specified') {
                        $totalQuotations++;
                        if ($quotation['negotiation_status'] === 'Success') {
                            $successfulQuotations++;
                        }
                    }
                }
            }
        }

        // Calculate the success rate
        $successRate = $totalQuotations > 0 ? round(($successfulQuotations / $totalQuotations) * 100, 2) : 0;

        // Get admins
        $usersRef = $this->firebase->getReference('User');
        $users = $usersRef->getValue() ?? [];
        $admins = [];
        foreach ($users as $key => $user) {
            if (isset($user['role']) && $user['role'] == 1) {
                $admins[$key] = $user;
            }
        }

        return view('quotation.management', [
            'userId' => $userId,
            'username' => $userData['username'] ?? 'Guest',
            'role' => $userData['role_name'] ?? 'Unknown',
            'name' => $userData['name'] ?? 'Unknown',
            'imageUrl' => $imageUrl,
            'pendingQuotations' => $pendingQuotations,
            'completedQuotations' => $completedQuotations,
            'admins' => $admins,
            'successRate' => $successRate,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching quotations: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Failed to fetch quotations.']);
    }
}


    public function show($id)
{

    try {
        $quotation = $this->firebase->getReference('Quotations/' . $id)->getValue();

        if (!$quotation) {
            return response()->json(['error' => 'Quotation not found.'], 404);
        }

        $adminName = null;
        if (isset($quotation['assignedAdmin'])) {
            $admin = $this->firebase->getReference('User/' . $quotation['assignedAdmin'])->getValue();
            $adminName = $admin['name'] ?? null;
        }

        return response()->json([
            'name' => $quotation['name'],
            'email' => $quotation['email'],
            'phone' => $quotation['phone'],
            'services' => $quotation['services'],
            'totalCost' => $quotation['totalCost'],
            'adminName' => $adminName, // Return adminName
            'status' => $quotation['status'],
            'negotiation_status' => $quotation['negotiation_status'] ?? null,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching quotation details: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch quotation details.{{$e}}'], 500);
    }
}

public function update(Request $request, $id)
{
    try {
        \Log::info('Updating quotation with ID: ' . $id);
        \Log::info('Request data: ' . json_encode($request->all()));

        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'services' => 'required|array',
            'totalCost' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation errors: ' . json_encode($validator->errors()->all()));
            return response()->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $quotationRef = $this->firebase->getReference('Quotations/' . $id);
        $quotation = $quotationRef->getValue();

        if (!$quotation) {
            \Log::error("Quotation with ID: $id not found.");
            return response()->json(['error' => 'Quotation not found.'], 404);
        }

        // Update the quotation data
        $quotation['name'] = $request->input('name');
        $quotation['email'] = $request->input('email');
        $quotation['phone'] = $request->input('phone');
        $quotation['services'] = $request->input('services');
        $quotation['totalCost'] = (float)$request->input('totalCost'); // Cast to float

        // Save the updated quotation
        $quotationRef->set($quotation);

        return response()->json(['message' => 'Quotation updated successfully!']);

    } catch (\Exception $e) {
        \Log::error('Error updating quotation: ' . $e->getMessage());
        \Log::error('Stack Trace: ' . $e->getTraceAsString()); // Log stack trace
        return response()->json(['error' => 'Failed to update quotation. ' . $e->getMessage()], 500);
    }
}

    public function details($id)
    {
        
        try {
            $quotation = $this->firebase->getReference('Quotations/' . $id)->getValue();

            if (!$quotation) {
                return back()->withErrors(['error' => 'Quotation not found.']);
            }

            $adminName = null;
            if (isset($quotation['assignedAdmin'])) {
                $admin = $this->firebase->getReference('User/' . $quotation['assignedAdmin'])->getValue();
                $adminName = $admin['name'] ?? null;
            }

            return view('quotation.quotation_details', compact('quotation', 'adminName'));
        } catch (\Exception $e) {
            \Log::error('Error fetching quotation details: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to fetch quotation details.']);
        }
    }

    public function updateNegotiationStatus(Request $request, $quotationId)
{
    try {
        // Fetch quotation from Firebase
        $quotation = $this->firebase->getReference('Quotations/' . $quotationId)->getValue();
        
        // Check if quotation exists
        if ($quotation) {
            // Update the negotiation_status field
            $quotation['negotiation_status'] = $request->input('negotiation_status');
            $quotation['status'] = "Completed";
            
            // Push the updated data back to Firebase
            $this->firebase->getReference('Quotations/' . $quotationId)->set($quotation);
            
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Quotation not found']);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
}

    
}
