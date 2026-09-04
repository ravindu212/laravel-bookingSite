<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('/reviews', fn () => redirect('/#reviews'));
Route::post('/reviews', [CustomerController::class, 'storeReview'])->name('reviews.store');
Route::get('/hotels/{hotel}/booking', [CustomerController::class, 'booking'])->name('hotels.booking');
Route::post('/hotels/{hotel}/booking', [CustomerController::class, 'storeBooking'])->name('hotels.booking.store');

Route::prefix('admin')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('admin.register');
    Route::post('/register', [AuthController::class, 'register'])->name('admin.register.store');

    Route::middleware('auth')->group(function (): void {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings.index');
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews.index');
        Route::patch('/reviews/{review}/approve', [AdminController::class, 'approveReview'])->name('admin.reviews.approve');
        Route::post('/hotels', [AdminController::class, 'storeHotel'])->name('admin.hotels.store');
        Route::get('/hotels/{hotel}/inventories', [AdminController::class, 'hotelInventories'])->name('admin.hotels.inventories');
        Route::post('/hotels/{hotel}/inventories', [AdminController::class, 'storeHotelInventory'])->name('admin.hotels.inventories.store');
        Route::post('/hotels/{hotel}/inventories/import', [AdminController::class, 'importHotelInventories'])->name('admin.hotels.inventories.import');
        Route::get('/hotels/{hotel}/inventories/export', [AdminController::class, 'exportHotelInventories'])->name('admin.hotels.inventories.export');
        Route::get('/hotels/{hotel}/inventories/{inventory}/edit', [AdminController::class, 'editHotelInventory'])->name('admin.hotels.inventories.edit');
        Route::put('/hotels/{hotel}/inventories/{inventory}', [AdminController::class, 'updateHotelInventory'])->name('admin.hotels.inventories.update');
        Route::delete('/hotels/{hotel}/inventories/{inventory}', [AdminController::class, 'destroyHotelInventory'])->name('admin.hotels.inventories.destroy');
        Route::get('/hotels/{hotel}/edit', [AdminController::class, 'editHotel'])->name('admin.hotels.edit');
        Route::put('/hotels/{hotel}', [AdminController::class, 'updateHotel'])->name('admin.hotels.update');
        Route::delete('/hotels/{hotel}', [AdminController::class, 'destroyHotel'])->name('admin.hotels.destroy');
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    });
});
