<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminResetPinMail;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in') === true) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['error' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!'])->withInput();
    }

    public function showForget()
    {
        return view('admin.forget');
    }

    public function forget(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin) {
            $pin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiry = now()->addMinutes(15);

            $admin->update([
                'reset_token' => $pin,
                'token_expiry' => $expiry,
            ]);

            // แสดง PIN ออกทาง Terminal ของ Docker เพื่อความสะดวกในการทดสอบ
            \Illuminate\Support\Facades\Log::channel('stderr')->info("\n==================================\nADMIN RESET PIN สำหรับ {$admin->email} คือ: {$pin}\n==================================\n");

            try {
                Mail::to($admin->email)->send(new AdminResetPinMail($pin));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage());
                // ไม่ต้อง return back() เพื่อให้สามารถนำ PIN จาก Docker terminal ไปทดสอบต่อได้
            }

            return redirect()->route('admin.verify_pin')->with([
                'success' => 'ระบบได้ส่งรหัส PIN 6 หลักไปยังอีเมลของคุณแล้ว',
                'verify_email' => $admin->email
            ]);
        }

        return back()->withErrors(['error' => 'ไม่พบที่อยู่อีเมลนี้ในระบบผู้ดูแลระบบ!'])->withInput();
    }

    public function showVerifyPin(Request $request)
    {
        $email = session('verify_email') ?? $request->old('email');
        return view('admin.verify_pin', ['email' => $email]);
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'pin' => 'required|string|size:6',
        ]);

        $admin = Admin::where('email', $request->email)
            ->where('reset_token', $request->pin)
            ->where('token_expiry', '>', now())
            ->first();

        if ($admin) {
            session(['reset_admin_email' => $admin->email]);
            return redirect()->route('admin.reset')->with('success', 'รหัส PIN ถูกต้อง กรุณาตั้งรหัสผ่านใหม่');
        }

        return back()->withErrors(['error' => 'รหัส PIN ไม่ถูกต้อง หรือหมดอายุไปแล้ว (เกิน 15 นาที)'])->withInput();
    }

    public function showReset(Request $request)
    {
        $email = session('reset_admin_email');

        if (empty($email)) {
            return redirect()->route('admin.forget')->withErrors(['error' => 'ไม่ได้รับอนุญาตให้เข้าถึงหน้านี้ กรุณายืนยัน PIN ก่อน']);
        }

        return view('admin.reset');
    }

    public function reset(Request $request)
    {
        $email = session('reset_admin_email');

        if (empty($email)) {
            return redirect()->route('admin.forget')->withErrors(['error' => 'ไม่ได้รับอนุญาตให้เข้าถึงหน้านี้ กรุณายืนยัน PIN ก่อน']);
        }

        $request->validate([
            'new_password' => 'required|string|min:4',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return redirect()->route('admin.forget')->withErrors(['error' => 'เกิดข้อผิดพลาด ไม่พบข้อมูลบัญชี']);
        }

        $admin->update([
            'password' => Hash::make($request->new_password),
            'reset_token' => null,
            'token_expiry' => null,
        ]);

        session()->forget('reset_admin_email');

        return redirect()->route('admin.login')->with('success', 'เปลี่ยนรหัสผ่านใหม่สำเร็จแล้วค่ะ! ล็อกอินใหม่ได้เลย');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        return redirect()->route('admin.login')->with('success', 'ออกจากระบบแอดมินเรียบร้อยแล้วค่ะ');
    }
}
