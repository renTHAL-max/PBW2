<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::resource('pelanggan', PelangganController::class);

Route::get('/kendaraan', function () {
    return view('kendaraan.index');
})->name('kendaraan.index');

Route::get('/maintenance-records', function () {
    return view('maintenance.index');
})->name('maintenance.index');

Route::get('/vehicle-categories', function () {
    return view('vehicle.categories');
})->name('vehicle.categories');

Route::get('/transaksi-rental', function () {
    return view('transaksi.index');
})->name('transaksi.index');

Route::get('/laporan', function () {
    return view('laporan.index');
})->name('laporan.index');

Route::get('/role-permission', function () {
    return view('role.index');
})->name('role.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';