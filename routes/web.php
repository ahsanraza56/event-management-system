<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SeatSelectionController;
use App\Http\Controllers\AdminAnalyticsController;

// Public routes
Route::get('/', function () {
    return redirect()->route('events.index');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public event routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Seat selection routes
Route::get('/events/{event}/seats', [SeatSelectionController::class, 'show'])->name('events.seat-selection');
Route::get('/events/{event}/seats/available', [SeatSelectionController::class, 'getAvailableSeats'])->name('events.seats.available');
Route::post('/events/{event}/seats/reserve', [SeatSelectionController::class, 'reserveSeats'])->name('events.seats.reserve');
Route::post('/events/{event}/seats/release', [SeatSelectionController::class, 'releaseSeats'])->name('events.seats.release');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User booking routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/events/{event}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Admin routes
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        // Admin events
        Route::get('/events', [EventController::class, 'adminIndex'])->name('events.index');
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::post('/events/{event}/generate-seats', [EventController::class, 'generateSeats'])->name('events.generate-seats');
        
        // Admin bookings
        Route::get('/bookings', [BookingController::class, 'adminIndex'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'adminShow'])->name('bookings.show');
        Route::patch('/bookings/{booking}', [BookingController::class, 'adminUpdate'])->name('bookings.update');
        
        // Admin analytics
        Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
    });
});
