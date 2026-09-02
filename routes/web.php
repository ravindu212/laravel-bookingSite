<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('/hotels/{hotel}/booking', [CustomerController::class, 'booking'])->name('hotels.booking');
Route::post('/hotels/{hotel}/booking', [CustomerController::class, 'storeBooking'])->name('hotels.booking.store');
Route::view('/admin/login', 'admin.login')->name('admin.login');
Route::get('/admin', [AdminController::class, 'dashboard'])->name('dashboard');
Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings.index');
Route::post('/admin/hotels', [AdminController::class, 'storeHotel'])->name('admin.hotels.store');
Route::get('/admin/hotels/{hotel}/edit', [AdminController::class, 'editHotel'])->name('admin.hotels.edit');
Route::put('/admin/hotels/{hotel}', [AdminController::class, 'updateHotel'])->name('admin.hotels.update');
Route::delete('/admin/hotels/{hotel}', [AdminController::class, 'destroyHotel'])->name('admin.hotels.destroy');
