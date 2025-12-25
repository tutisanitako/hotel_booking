<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HotelManagementController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ReviewManagementController;
use App\Http\Controllers\HotelManager\BookingController as HotelManagerBookingController;
use App\Http\Controllers\HotelManager\DashboardController as HotelManagerDashboardController;
use App\Http\Controllers\HotelManager\HotelController as HotelManagerHotelController;

/*
Public Routes
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Hotels
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/search', [HotelController::class, 'search'])->name('hotels.search');
Route::get('/hotels/{slug}', [HotelController::class, 'show'])->name('hotels.show');

/*
Authentication Routes
*/

require __DIR__ . '/auth.php';

/*
Authenticated User Routes
*/

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/verify-phone', [ProfileController::class, 'verifyPhone'])->name('profile.verify-phone');

    // Bookings
    Route::get('/hotels/{hotelSlug}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/confirmation/{bookingNumber}', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my-bookings');
});

/*
Admin Routes
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Hotel Management
    Route::resource('hotels', HotelManagementController::class);
    Route::get('/hotels/{hotel}/rooms', [HotelManagementController::class, 'rooms'])->name('hotels.rooms');
    Route::post('/hotels/{hotel}/rooms', [HotelManagementController::class, 'storeRoom'])->name('hotels.rooms.store');
    Route::put('/hotels/{hotel}/rooms/{room}', [HotelManagementController::class, 'updateRoom'])->name('hotels.rooms.update');
    Route::delete('/hotels/{hotel}/rooms/{room}', [HotelManagementController::class, 'destroyRoom'])->name('hotels.rooms.destroy');

    // Booking Management
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [BookingManagementController::class, 'updateStatus'])->name('bookings.update-status');
    Route::patch('/bookings/{booking}/payment', [BookingManagementController::class, 'updatePaymentStatus'])->name('bookings.update-payment');
    Route::delete('/bookings/{booking}', [BookingManagementController::class, 'destroy'])->name('bookings.destroy');

    // User Management
    Route::resource('users', UserManagementController::class)->except(['create', 'store']);

    // Review Management
    Route::get('/reviews', [ReviewManagementController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [ReviewManagementController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [ReviewManagementController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [ReviewManagementController::class, 'destroy'])->name('reviews.destroy');
});

/*
Hotel Manager Routes
*/

Route::middleware(['auth', 'hotel.manager'])->prefix('hotel-manager')->name('hotel-manager.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [HotelManagerDashboardController::class, 'index'])->name('dashboard');

    // Hotels Management
    Route::get('/hotels', [HotelManagerHotelController::class, 'index'])->name('hotels.index');
    Route::get('/hotels/{hotel}/edit', [HotelManagerHotelController::class, 'edit'])->name('hotels.edit');
    Route::put('/hotels/{hotel}', [HotelManagerHotelController::class, 'update'])->name('hotels.update');

    // Room Management
    Route::get('/hotels/{hotel}/rooms', [HotelManagerHotelController::class, 'rooms'])->name('hotels.rooms');
    Route::post('/hotels/{hotel}/rooms', [HotelManagerHotelController::class, 'storeRoom'])->name('hotels.rooms.store');
    Route::put('/hotels/{hotel}/rooms/{room}', [HotelManagerHotelController::class, 'updateRoom'])->name('hotels.rooms.update');

    // Bookings Management
    Route::get('/bookings', [HotelManagerBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [HotelManagerBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [HotelManagerBookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::patch('/bookings/{booking}/payment', [HotelManagerBookingController::class, 'updatePaymentStatus'])->name('bookings.update-payment');
});
