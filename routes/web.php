<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::redirect('/reviews', '/#reviews');
Route::post('/reviews', [CustomerController::class, 'storeReview'])->name('reviews.store');
Route::get('/hotels/{hotel}/booking', [CustomerController::class, 'booking'])->name('hotels.booking');
Route::post('/hotels/{hotel}/booking', [CustomerController::class, 'storeBooking'])->name('hotels.booking.store');
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.store');
Route::get('/admin/register', [AuthController::class, 'showRegister'])->name('admin.register');
Route::post('/admin/register', [AuthController::class, 'register'])->name('admin.register.store');

Route::middleware('auth')->group(function (): void {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings.index');
    Route::get('/admin/reviews', [AdminController::class, 'reviews'])->name('admin.reviews.index');
    Route::patch('/admin/reviews/{review}/approve', [AdminController::class, 'approveReview'])->name('admin.reviews.approve');
    Route::post('/admin/hotels', [AdminController::class, 'storeHotel'])->name('admin.hotels.store');
    Route::get('/admin/hotels/{hotel}/edit', [AdminController::class, 'editHotel'])->name('admin.hotels.edit');
    Route::put('/admin/hotels/{hotel}', [AdminController::class, 'updateHotel'])->name('admin.hotels.update');
    Route::delete('/admin/hotels/{hotel}', [AdminController::class, 'destroyHotel'])->name('admin.hotels.destroy');
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
});
