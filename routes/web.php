<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/quarterly/{year?}', [DashboardController::class, 'quarterly'])->name('quarterly');

Route::get('/coming-soon', function (\Illuminate\Http\Request $request) {
    return view('dashboard.coming_soon', ['title' => $request->query('title', 'Coming Soon')]);
})->name('coming_soon');

Route::middleware('auth')->group(function () {

    Route::middleware('role:admin,superadmin')->group(function () {
        Route::post('/upload-excel', [AdminController::class, 'uploadExcel'])->name('upload.excel');
    });

    Route::middleware('role:superadmin')->group(function () {
        Route::get('/superadmin/add-admin', [AdminController::class, 'createAdmin'])->name('superadmin.add-admin');
        Route::post('/superadmin/add-admin', [AdminController::class, 'storeAdmin'])->name('superadmin.store-admin');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
