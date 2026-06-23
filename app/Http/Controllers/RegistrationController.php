<?php

namespace App\Http\Controllers;

use App\Models\User; // ตาราง users เก็บผู้ลงทะเบียนเข้าอบรม
use App\Models\Course;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function showForm()
    {
        $courses = Course::all();
        return view('user.registration_form', compact('courses'));
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'Tel' => 'required|string|max:20',
            'course' => 'required|string|max:255',
            'class_date' => 'required|date',
            'pdpa_consent' => 'required|accepted',
        ]);

        try {
            User::create([
                'fullname' => trim($request->fullname),
                'email' => trim($request->email),
                'Tel' => trim($request->Tel),
                'course' => trim($request->course),
                'class_date' => $request->class_date,
                'pdpa_consent' => 1,
            ]);

            return back()->with('success', 'ลงทะเบียนสำเร็จเรียบร้อยแล้วค่ะ!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()])->withInput();
        }
    }
}
