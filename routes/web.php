<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\CarePlanController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ViewClientController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RegisterCarePlanController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CareLogController;
use App\Http\Controllers\ViewCarePlanController;
use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\File;
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
    Route::get('/aboutus', [DashboardController::class, 'aboutUs'])->name('aboutus');
    Route::get('/recent-activities', [DashboardController::class, 'index'])->name('recentActivities.filter');

    // User Management Routes
    Route::prefix('users')->group(function () {
        Route::get('/create', [UserController::class, 'create'])->name('users.create'); // User registration form
        Route::post('/store', [UserController::class, 'store'])->name('users.store');  // Save user data
        Route::get('/view', [UserController::class, 'view'])->name('users.view');      // View registered users
        Route::put('/users/update', [UserController::class, 'update'])->name('users.update');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::get('/users/{id}/view', [UserController::class, 'viewUser'])->name('users.viewUser');
        Route::post('/your-upload-endpoint', [UserController::class, 'uploadProfilePhoto']);
    });


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
        Route::get('/{userId}/{planId}/edit-caregiver', [CarePlanController::class, 'editCaregiver'])->name('careplan.editCaregiver'); // Edit caregiver for a specific care plan
        Route::post('/{userId}/{planId}/edit-caregiver', [CarePlanController::class, 'editCaregiver']); // Save changes to caregiver
        Route::put('/{userId}/{planId}/status', [CarePlanController::class, 'updateStatus'])->name('careplan.updateStatus');
        Route::delete('/{userId}/{planId}', [CarePlanController::class, 'delete'])->name('careplan.delete');

    });

     /// My Account Routes
     Route::prefix('myaccount')->group(function () {
        Route::get('/', [AccountController::class, 'viewAccount'])->name('myaccount.view');
        Route::get('/edit', [AccountController::class, 'editAccount'])->name('myaccount.edit');
        Route::put('/update', [AccountController::class, 'updateAccount'])->name('myaccount.update');
    });
    


    
});


Route::post('/upload-cropped-image', [UserController::class, 'uploadCroppedImage'])->name('upload.cropped.image');

// Firebase Testing Routes
Route::prefix('firebase')->group(function () {
    Route::get('/add-data-form', [FirebaseController::class, 'index'])->name('add-data-form');
    Route::post('/add-data', [FirebaseController::class, 'store'])->name('add-data');
    Route::delete('/delete-data/{id}', [FirebaseController::class, 'destroy'])->name('delete-data');
});

//================================CAREPLAN QUOTATION===========================================

Route::get('/service', [ServiceController::class, 'index'])->name('services.index');
Route::get('/fetch-services', [ServiceController::class, 'fetchServices'])->name('services.fetch');
Route::post('/submit-quotation', [QuotationController::class, 'store'])->name('quotations.store');

Route::prefix('quotation')->group(function () {
    // Views for Quotation Management
    Route::get('/view', function () {
        return view('quotation/quotation');
    })->name('quotation');

    Route::get('/careplan-quote', function () {
        return view('quotation/careplan-quote');
    })->name('careplan-quote');

    // Quotation Management Routes
    Route::prefix('management')->group(function () {
        Route::get('/', [QuotationController::class, 'index'])->name('quotations.index');
        Route::get('/{id}', [QuotationController::class, 'show'])->name('quotations.show'); // Details view
        Route::put('/{id}', [QuotationController::class, 'update'])->name('quotations.update'); // Update quotation
        Route::delete('/{id}', [QuotationController::class, 'destroy'])->name('quotations.destroy'); // Delete quotation
        Route::patch('/{id}/complete', [QuotationController::class, 'complete'])->name('quotations.complete'); // Mark as complete
        Route::get('/details/{id}', [QuotationController::class, 'details'])->name('quotations.details'); // Detailed view
        Route::get('/print/{id}', [QuotationController::class, 'print'])->name('quotations.print'); // Generate PDF
        Route::get('/search', [QuotationController::class, 'search'])->name('quotations.search');
        Route::put('{quotationId}/update-negotiation-status', [QuotationController::class, 'updateNegotiationStatus']);

});

    // Thank You Page
    Route::get('/thankyou', function () {
        return view('thankyou');
    })->name('thankyou');
});

//=============================================================================================

//Client register route
Route::get('/register', [RegisterController::class, 'index'])
    ->name('register')
    ->middleware('session.auth');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/add-data-form', [FirebaseController::class, 'index'])->name('add-data-form');
Route::post('/add-data', [FirebaseController::class, 'store'])->name('add-data');
Route::delete('/delete-data/{id}', [FirebaseController::class, 'destroy'])->name('delete-data');


Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); // Redirect to the login page after logout
})->name('logout');

Route::get('/viewClient', [ViewClientController::class, 'index'])
    ->name('viewClient')
    ->middleware('session.auth');
Route::get('/search-clients', [ViewClientController::class, 'searchClients'])->name('searchClients')->middleware('session.auth');;

Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');

Route::get('/registerCarePlan', [RegisterCarePlanController::class, 'index'])
    ->name('registerCarePlan')
    ->middleware('session.auth');
Route::post('/register-care-plan', [RegisterCarePlanController::class, 'store']);

Route::get('/viewCarePlan', [ViewCarePlanController::class, 'index'])
    ->name('viewCarePlan')
    ->middleware('session.auth');

Route::get('/careLog', [CareLogController::class, 'index'])
    ->name('careLog')
    ->middleware('session.auth');

//Json File
Route::get('/poscodes', function () {
    $path = resource_path('data/poscodes.json');
    return response()->json(json_decode(File::get($path)), 200);
});