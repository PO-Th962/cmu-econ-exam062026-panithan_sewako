<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;



Route::middleware('guest')->group(function () {

    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('user.login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('user.login.submit');


    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('user.register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('user.register.submit');
});

// หน้าลงทะเบียนและออกจากระบบที่ต้องการการตรวจสอบสิทธิ์
Route::middleware('auth.user')->group(function () {
    // แบบฟอร์มลงทะเบียนเข้าร่วมอบรม
    Route::get('/', [RegistrationController::class, 'showForm'])->name('registration.form');
    Route::post('/', [RegistrationController::class, 'submitForm'])->name('registration.submit');


    Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
});



Route::prefix('admin')->group(function () {

    Route::middleware('guest')->group(function () {

        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');


        Route::get('/forget', [AdminAuthController::class, 'showForget'])->name('admin.forget');
        Route::post('/forget', [AdminAuthController::class, 'forget'])->name('admin.forget.submit');


        Route::get('/verify-pin', [AdminAuthController::class, 'showVerifyPin'])->name('admin.verify_pin');
        Route::post('/verify-pin', [AdminAuthController::class, 'verifyPin'])->name('admin.verify_pin.submit');


        Route::get('/reset', [AdminAuthController::class, 'showReset'])->name('admin.reset');
        Route::post('/reset', [AdminAuthController::class, 'reset'])->name('admin.reset.submit');
    });


    Route::middleware('auth.admin')->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');


        Route::post('/courses', [AdminDashboardController::class, 'storeCourse'])->name('admin.course.store');
        Route::delete('/courses/{id}', [AdminDashboardController::class, 'deleteCourse'])->name('admin.course.delete');



        Route::get('/delete/{id}', [AdminDashboardController::class, 'delete'])->name('admin.delete');


        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
