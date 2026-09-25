<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderOpportunityController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/profile/student/edit', [ProfileController::class, 'editStudent'])->name('profile.student.edit');
    Route::put('/profile/student', [ProfileController::class, 'updateStudent'])->name('profile.student.update');
    
    Route::get('/profile/provider/edit', [ProfileController::class, 'editProvider'])->name('profile.provider.edit');
    Route::put('/profile/provider', [ProfileController::class, 'updateProvider'])->name('profile.provider.update');
    
    // Notifications
    Route::post('/notifications/{notification}/read', [DashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [DashboardController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
});

// Public opportunities
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/opportunities', [OpportunityController::class, 'index'])->name('opportunities.index');
Route::get('/opportunities/{opportunity}', [OpportunityController::class, 'show'])->name('opportunities.show');
Route::get('/opportunities/search/api', [OpportunityController::class, 'search'])->name('opportunities.search');

// Student-only routes
Route::middleware(['auth', 'student'])->group(function () {
    Route::post('/opportunities/{opportunity}/save', [OpportunityController::class, 'toggleSave'])->name('opportunities.save');
    
    Route::get('/applications/create/{opportunity}', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/opportunities/{opportunity}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    
    Route::get('/applications', [ApplicationController::class, 'myApplications'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/withdraw', [ApplicationController::class, 'withdraw'])->name('applications.withdraw');
});

// Provider-only routes
Route::middleware(['auth', 'provider'])->group(function () {
    Route::prefix('provider/opportunities')->name('provider.opportunities.')->group(function () {
        Route::get('/', [ProviderOpportunityController::class, 'index'])->name('index');
        Route::get('/create', [ProviderOpportunityController::class, 'create'])->name('create');
        Route::post('/', [ProviderOpportunityController::class, 'store'])->name('store');
        
        Route::get('/{opportunity}', [ProviderOpportunityController::class, 'show'])->name('show');
        Route::get('/{opportunity}/edit', [ProviderOpportunityController::class, 'edit'])->name('edit');
        Route::put('/{opportunity}', [ProviderOpportunityController::class, 'update'])->name('update');
        
        Route::post('/{opportunity}/close', [ProviderOpportunityController::class, 'close'])->name('close');
        Route::delete('/{opportunity}', [ProviderOpportunityController::class, 'destroy'])->name('destroy');
        
        Route::post('/{opportunity}/update-application-status', [ProviderOpportunityController::class, 'updateApplicationStatus'])->name('update-application-status');
    });
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Admin features to be added
});
