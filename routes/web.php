<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;

// ==========================================
// เส้นทางสำหรับผู้ใช้งานทั่วไป (General Users)
// ==========================================

Route::middleware('guest')->group(function () {
    // เข้าสู่ระบบ (User Login)
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('user.login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('user.login.submit');

    // สมัครสมาชิก (User Register)
    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('user.register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('user.register.submit');
});

// หน้าลงทะเบียนและออกจากระบบที่ต้องการการตรวจสอบสิทธิ์
Route::middleware('auth.user')->group(function () {
    // แบบฟอร์มลงทะเบียนเข้าร่วมอบรม
    Route::get('/', [RegistrationController::class, 'showForm'])->name('registration.form');
    Route::post('/', [RegistrationController::class, 'submitForm'])->name('registration.submit');

    // ออกจากระบบ
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
});


// ==========================================
// เส้นทางสำหรับผู้ดูแลระบบ (Admin)
// ==========================================

Route::prefix('admin')->group(function () {
    
    Route::middleware('guest')->group(function () {
        // เข้าสู่ระบบผู้ดูแล (Admin Login)
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

        // กู้คืนรหัสผ่านแอดมิน (Admin Password Forget)
        Route::get('/forget', [AdminAuthController::class, 'showForget'])->name('admin.forget');
        Route::post('/forget', [AdminAuthController::class, 'forget'])->name('admin.forget.submit');

        // ยืนยันรหัส PIN (Admin PIN Verification)
        Route::get('/verify-pin', [AdminAuthController::class, 'showVerifyPin'])->name('admin.verify_pin');
        Route::post('/verify-pin', [AdminAuthController::class, 'verifyPin'])->name('admin.verify_pin.submit');

        // ตั้งรหัสผ่านแอดมินใหม่ (Admin Password Reset)
        Route::get('/reset', [AdminAuthController::class, 'showReset'])->name('admin.reset');
        Route::post('/reset', [AdminAuthController::class, 'reset'])->name('admin.reset.submit');
    });

    // แดชบอร์ดแอดมินและฟังก์ชันการจัดการ
    Route::middleware('auth.admin')->group(function () {
        // หน้าหลัก Dashboard รายชื่อผู้ลงทะเบียน
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // จัดการหลักสูตร
        Route::post('/courses', [AdminDashboardController::class, 'storeCourse'])->name('admin.course.store');
        Route::delete('/courses/{id}', [AdminDashboardController::class, 'deleteCourse'])->name('admin.course.delete');


        // ลบข้อมูลผู้สมัครเข้าอบรม
        Route::get('/delete/{id}', [AdminDashboardController::class, 'delete'])->name('admin.delete');

        // ออกจากระบบ
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
