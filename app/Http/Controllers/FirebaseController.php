<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    private $database;

    public function __construct()
{
    $firebase = (new Factory)
        ->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')))
        ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));  // Ensure you are using the correct database URL

    $this->database = $firebase->createDatabase();
}


    // Show the form with existing users from Firebase
    public function index()
    {
        $data = $this->database->getReference('users')->getValue();
        return view('add-data', ['users' => $data]);
    }

    // Store new user in Firebase
    public function store(Request $request)
    {
        $this->database->getReference('users')->push([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return redirect()->route('add-data-form')->with('success', 'Data added successfully');
    }

    // Delete a user from Firebase
    public function destroy($id)
    {
        $this->database->getReference('users/' . $id)->remove();
        return redirect()->route('add-data-form')->with('success', 'Data deleted successfully');
    }
}
