<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Messaging
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // Sell wall
    Route::get('/sell', [ListingController::class, 'index'])->name('sell.index');
    Route::get('/sell/create', [ListingController::class, 'create'])->name('sell.create');
    Route::post('/sell', [ListingController::class, 'store'])->name('sell.store');
    Route::patch('/sell/{listing}/sold', [ListingController::class, 'markSold'])->name('sell.sold');
    Route::delete('/sell/{listing}', [ListingController::class, 'destroy'])->name('sell.destroy');

    // Newsfeed
    Route::get('/feed', [PostController::class, 'index'])->name('feed.index');
    Route::post('/feed', [PostController::class, 'store'])->name('feed.store');
    Route::delete('/feed/{post}', [PostController::class, 'destroy'])->name('feed.destroy');
    Route::patch('/feed/{post}/status', [PostController::class, 'updateStatus'])->name('feed.status');
    Route::get('/feed/{post}/requirement', [PostController::class, 'requirement'])->name('feed.requirement');
    Route::post('/feed/{post}/vote', [VoteController::class, 'store'])->name('feed.vote');

    // Applications
    Route::get('/feed/{post}/apply', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/feed/{post}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/feed/{post}/applicants', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}/file', [ApplicationController::class, 'file'])->name('applications.file');

    // Comments
    Route::post('/feed/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Member profiles
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');

    // Account settings
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::patch('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
});

require __DIR__.'/auth.php';