<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\RentalController;

// Home (untuk semua pengunjung, termasuk guest)
Route::get('/', function () {
    return view('home'); // Halaman home
})->name('home');

// Route Login member
Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route Login Staff
Route::get('/staff/login', [LoginController::class, 'showStaffLoginForm'])->middleware('guest')->name('staff.login');
Route::post('/staff/login', [LoginController::class, 'staffLogin'])->name('staff.login.submit');




Route::middleware(['auth', 'role:0'])->group(function () {
    // Route katalog
    Route::get('/staff/catalog', [CatalogController::class, 'index'])
        ->name('catalog.index');

    // Menambah buku baru
    Route::post('/staff/catalog', [CatalogController::class, 'store'])
        ->name('catalog.store');

    // Mengupdate buku
    Route::put('/staff/catalog', [CatalogController::class, 'update'])
        ->name('catalog.update');

    // Menghapus buku
    Route::delete('/staff/catalog/{id}', [CatalogController::class, 'delete'])
        ->name('catalog.delete');

    Route::get('/staff/rental', [RentalController::class, 'index'])->name('rental.index');
    Route::post('/staff/rental/store', [RentalController::class, 'store'])->name('rental.store');
    Route::post('/staff/rental/return/{id}', [RentalController::class, 'return'])->name('rental.return');
});
