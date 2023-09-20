<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard2', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard2');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/admin/add-user', [AdminController::class, 'store'])->name('store');
    Route::get('/admin/manage-user', [AdminController::class, 'getUserDetails'])->name('manage.user');
    Route::post('/admin/user-update', [AdminController::class, 'userUpdate'])->name('user.update');
    Route::get('admin/attendance-record', [AdminController::class, 'attendanceRecord'])->name('attendance.record');
});

Route::middleware(['role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('dashboard');
});

Route::get('/404', function () {
    return view('errors.404');
})->name('error.404');

require __DIR__ . '/auth.php';
