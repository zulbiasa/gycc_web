<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request, FirebaseService $firebaseService)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
    
        // Get user using FirebaseService
        $user = $firebaseService->getUser($request->username, $request->password);
    
        if ($user) {
            $userData = $user['userData'];
    
            // Store user_id and role in the session
            session(['user_id' => $user['userId']]);
            session(['role' => $userData['role']]);
    
            return redirect()->route('dashboard');
        }
    
        // If no user is found, return error
        return back()->withErrors(['username' => 'Invalid credentials']);
    }
    
    public function dashboard(FirebaseService $firebaseService)
    {
        $userId = session('user_id');
        $userData = $firebaseService->getUserDataById($userId);

        if ($userData) {
            return view('dashboard', ['user' => $userData]);
        }

        return redirect()->route('login')->withErrors(['message' => 'User data not found']);
    }

    
}