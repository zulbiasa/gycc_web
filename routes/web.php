<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\CarePlanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Public Route (e.g., welcome page)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::prefix('users')->group(function () {
    Route::get('/select-role', [UserController::class, 'selectRole'])->name('users.selectRole');
    Route::get('/register/{role}', [UserController::class, 'create'])->name('users.register');
    Route::post('/register', [UserController::class, 'store'])->name('users.store');
});



// Dashboard Routes (protected by middleware)
Route::middleware(['session.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Service Management Routes
    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'viewServices'])->name('services.view'); // View all services
        Route::get('/create', [ServiceController::class, 'create'])->name('services.create'); // Add new service
        Route::post('/', [ServiceController::class, 'store'])->name('services.store'); // Store new service
        Route::get('/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit'); // Edit a service
        Route::put('/{id}', [ServiceController::class, 'update'])->name('services.update'); // Update a service
    });

    // Care Plan Routes
Route::prefix('careplan')->group(function () {
    Route::get('/', [CarePlanController::class, 'index'])->name('careplan.index'); // View all care plans
    Route::get('/view/{userId}/{planId}', [CarePlanController::class, 'view'])->name('careplan.view'); // View a specific care plan
    Route::get('/edit/{userId}/{planId}', [CarePlanController::class, 'edit'])->name('careplan.edit'); // Edit specific care plan
    Route::post('/update/{userId}/{planId}', [CarePlanController::class, 'update'])->name('careplan.update'); // Update care plan
    Route::delete('/delete/{userId}/{planId}', [CarePlanController::class, 'delete'])->name('careplan.delete'); // Delete care plan
});


    
});

// Admin Routes
Route::middleware(['session.auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Caregiver Routes
Route::middleware(['session.auth'])->prefix('caregiver')->group(function () {
    Route::get('/dashboard', function () {
        return view('caregiver.dashboard');
    })->name('caregiver.dashboard');
});

// Firebase Testing Routes
Route::prefix('firebase')->group(function () {
    Route::get('/add-data-form', [FirebaseController::class, 'index'])->name('add-data-form');
    Route::post('/add-data', [FirebaseController::class, 'store'])->name('add-data');
    Route::delete('/delete-data/{id}', [FirebaseController::class, 'destroy'])->name('delete-data');
});

//CAREPLAN QUOTATION
Route::get('/careplan-quote', function () {return view('careplan-quote');})->name('careplan-quote');
Route::get('/service', [ServiceController::class, 'index'])->name('services.index');
Route::get('/fetch-services', [ServiceController::class, 'fetchServices'])->name('services.fetch');
Route::get('/quotation', function () {return view('quotation');})->name('quotation');

