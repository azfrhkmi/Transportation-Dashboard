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
Route::get('/export/quarterly/{year}', [DashboardController::class, 'downloadReport'])->name('export.quarterly');

Route::get('/coming-soon', function (\Illuminate\Http\Request $request) {
    return view('dashboard.coming_soon', ['title' => $request->query('title', 'Coming Soon')]);
})->name('coming_soon');

Route::middleware('auth')->group(function () {

    Route::middleware('role:admin,superadmin')->group(function () {
        Route::post('/upload-excel', [AdminController::class, 'uploadExcel'])->name('upload.excel');
        Route::delete('/delete-data', [AdminController::class, 'deleteData'])->name('delete.data');
    });

    Route::middleware('role:superadmin')->group(function () {
        Route::get('/superadmin/manage-admins', [AdminController::class, 'manageAdmins'])->name('superadmin.add-admin');
        Route::post('/superadmin/manage-admins', [AdminController::class, 'storeAdmin'])->name('superadmin.store-admin');
        Route::delete('/superadmin/manage-admins/{id}', [AdminController::class, 'deleteAdmin'])->name('superadmin.delete-admin');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
