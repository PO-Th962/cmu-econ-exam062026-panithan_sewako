<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function showLogin()
    {
        if (session('user_logged_in') === true) {
            return redirect()->route('registration.form');
        }
        return view('user.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = UserAccount::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'user_logged_in' => true,
                'user_name' => $user->username,
            ]);
            return redirect()->route('registration.form');
        }

        return back()->withErrors(['error' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!'])->withInput();
    }

    public function showRegister()
    {
        return view('user.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:4',
            'confirm_password' => 'required|string|same:password',
        ]);

        $exists = UserAccount::where('username', $request->username)
            ->orWhere('email', $request->email)
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Username หรือ Email นี้ถูกใช้ไปแล้ว!'])->withInput();
        }

        UserAccount::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Auto-login หลังสมัครสมาชิกสำเร็จ ไม่ต้องให้ user login ซ้ำ
        session([
            'user_logged_in' => true,
            'user_name' => $request->username,
        ]);

        return redirect()->route('registration.form')->with('success', 'สมัครสมาชิกสำเร็จ! กรุณากรอกข้อมูลลงทะเบียนเข้าอบรมค่ะ');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['user_logged_in', 'user_name']);
        return redirect()->route('user.login')->with('success', 'ออกจากระบบเรียบร้อยแล้วค่ะ');
    }
}
