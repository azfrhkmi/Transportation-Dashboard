<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\MaritimeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Aviation Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/quarterly/{year?}', [DashboardController::class, 'quarterly'])->name('quarterly');
Route::get('/export/quarterly/{year}', [DashboardController::class, 'downloadReport'])->name('export.quarterly');

// Land Routes
Route::get('/land', [LandController::class, 'index'])->name('land.index');
Route::get('/land/licenses', [LandController::class, 'licenses'])->name('land.licenses');
Route::get('/land/rail', [LandController::class, 'rail'])->name('land.rail');

// Maritime Routes
Route::get('/maritime', [MaritimeController::class, 'report'])->name('maritime.report');
Route::get('/maritime/quarterly', [MaritimeController::class, 'index'])->name('maritime.index');

Route::get('/coming-soon', function (\Illuminate\Http\Request $request) {
    return view('dashboard.coming_soon', ['title' => $request->query('title', 'Coming Soon')]);
})->name('coming_soon');

Route::middleware('auth')->group(function () {

    Route::middleware('role:admin,superadmin')->group(function () {
        Route::post('/upload-excel', [AdminController::class, 'uploadExcel'])->name('upload.excel');
        Route::delete('/delete-data', [AdminController::class, 'deleteData'])->name('delete.data');

        Route::post('/upload-land', [AdminController::class, 'uploadLandExcel'])->name('upload.land');
        Route::delete('/delete-land', [AdminController::class, 'deleteLandData'])->name('delete.land');

        Route::post('/upload-rail', [AdminController::class, 'uploadRailExcel'])->name('upload.rail');
        Route::delete('/delete-rail', [AdminController::class, 'deleteRailData'])->name('delete.rail');

        Route::post('/upload-maritime', [AdminController::class, 'uploadMaritimeExcel'])->name('upload.maritime');
        Route::delete('/delete-maritime', [AdminController::class, 'deleteMaritimeData'])->name('delete.maritime');
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
