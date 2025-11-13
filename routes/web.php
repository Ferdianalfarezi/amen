<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DrawingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Semua route aplikasi didefinisikan di sini.
| Akses root diarahkan langsung ke halaman login.
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

Route::get('/preview/{path}', [DrawingController::class, 'previewDocument'])
    ->where('path', '.*')
    ->name('preview.document');

/*
|--------------------------------------------------------------------------
| Protected Routes (Auth + Check User Status)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.user.status'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Drawings Resource CRUD
    |--------------------------------------------------------------------------
    */
    Route::resource('drawings', DrawingController::class);
    
    /*
    |--------------------------------------------------------------------------
    | Lazy Loading Routes (GET) - Tab AJAX
    |--------------------------------------------------------------------------
    */
    Route::prefix('drawings/{drawing}')->group(function () {
        Route::get('/files2d', [DrawingController::class, 'files2d'])->name('drawings.files2d');
        Route::get('/files3d', [DrawingController::class, 'files3d'])->name('drawings.files3d');
        Route::get('/sample-parts', [DrawingController::class, 'sampleParts'])->name('drawings.sampleParts');
        Route::get('/qualities', [DrawingController::class, 'qualities'])->name('drawings.qualities');
        Route::get('/setup-procedures', [DrawingController::class, 'setupProcedures'])->name('drawings.setupProcedures');
        Route::get('/quotes', [DrawingController::class, 'quotes'])->name('drawings.quotes');
        Route::get('/work-instructions', [DrawingController::class, 'workInstructions'])->name('drawings.workInstructions');
    });
    
    Route::get('/preview-ppt/{id}', [DrawingController::class, 'previewPpt'])->name('preview.ppt');
    
    /*
    |--------------------------------------------------------------------------
    | Upload Routes (POST)
    |--------------------------------------------------------------------------
    */
    Route::prefix('drawings/{drawing}')->group(function () {
        Route::post('/upload-files2d', [DrawingController::class, 'uploadFile2D'])->name('drawings.uploadFile2D');
        Route::post('/upload-files3d', [DrawingController::class, 'uploadFile3D'])->name('drawings.uploadFile3D');
        Route::post('/upload-sample-parts', [DrawingController::class, 'uploadSamplePart'])->name('drawings.uploadSamplePart');
        Route::post('/upload-qualities', [DrawingController::class, 'uploadQuality'])->name('drawings.uploadQuality');
        Route::post('/upload-setup-procedures', [DrawingController::class, 'uploadSetupProcedure'])->name('drawings.uploadSetupProcedure');
        Route::post('/upload-quotes', [DrawingController::class, 'uploadQuote'])->name('drawings.uploadQuote');
        Route::post('/upload-work-instructions', [DrawingController::class, 'uploadWorkInstruction'])->name('drawings.uploadWorkInstruction');
    });

    /*
    |--------------------------------------------------------------------------
    | Delete Routes (DELETE)
    |--------------------------------------------------------------------------
    */
    Route::delete('/files2d/{id}', [DrawingController::class, 'deleteFile2D'])->name('drawings.deleteFile2D');
    Route::delete('/files3d/{id}', [DrawingController::class, 'deleteFile3D'])->name('drawings.deleteFile3D');
    Route::delete('/sample-parts/{id}', [DrawingController::class, 'deleteSamplePart'])->name('drawings.deleteSamplePart');
    Route::delete('/qualities/{id}', [DrawingController::class, 'deleteQuality'])->name('drawings.deleteQuality');
    Route::delete('/setup-procedures/{id}', [DrawingController::class, 'deleteSetupProcedure'])->name('drawings.deleteSetupProcedure');
    Route::delete('/quotes/{id}', [DrawingController::class, 'deleteQuote'])->name('drawings.deleteQuote');
    Route::delete('/work-instructions/{id}', [DrawingController::class, 'deleteWorkInstruction'])->name('drawings.deleteWorkInstruction');

    /*
    |--------------------------------------------------------------------------
    | Deprecated Routes (Akan dihapus nanti)
    |--------------------------------------------------------------------------
    */
    Route::delete('/fotos/{id}', [DrawingController::class, 'deleteFoto'])->name('fotos.destroy');
    Route::delete('/videos/{id}', [DrawingController::class, 'deleteVideo'])->name('videos.destroy');
    Route::delete('/dokumens/{id}', [DrawingController::class, 'deleteDokumen'])->name('dokumens.destroy');

    /*
    |--------------------------------------------------------------------------
    | User Management (Hanya yang punya permission)
    |--------------------------------------------------------------------------
    */
    // Permission routes - Bisa diakses oleh user dengan permission manage_permissions
    Route::middleware('permission:users,manage_permissions')->group(function () {
        Route::get('users/{user}/permissions', [UserController::class, 'getPermissions'])->name('users.permissions.get');
        Route::post('users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
    });

    // User CRUD - Hanya Superadmin atau yang punya permission users
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze / Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';