<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminDashboardController extends Controller
{

    public function index(Request $request)
    {
        $date = $request->input('date');

        $query = User::orderBy('id', 'desc');
        if ($date) {
            $query->whereDate('class_date', $date);
        }
        $users = $query->get();

        $total_users = $date ? User::whereDate('class_date', $date)->count() : User::count();

        $course_summary_query = User::select('course', DB::raw('count(*) as total'))
            ->groupBy('course');
        if ($date) {
            $course_summary_query->whereDate('class_date', $date);
        }
        $course_summary = $course_summary_query->get();

        $all_courses = Course::orderBy('id', 'desc')->get();

        return view('admin.dashboard', [
            'users' => $users,
            'total_users' => $total_users,
            'course_summary' => $course_summary,
            'all_courses' => $all_courses,
        ]);
    }


    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('admin.dashboard')->with('success', 'ลบข้อมูลผู้ลงทะเบียนสำเร็จแล้วค่ะ');
        }
        return redirect()->route('admin.dashboard')->withErrors(['error' => 'ไม่พบข้อมูลที่ต้องการลบ']);
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:courses,name',
        ]);

        Course::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'เพิ่มหลักสูตรใหม่สำเร็จแล้วค่ะ');
    }

    public function deleteCourse($id)
    {
        try {
            $course = Course::find($id);
            if ($course) {
                $course->delete();
                return redirect()->route('admin.dashboard')->with('success', 'ลบหลักสูตร ' . $course->name . ' สำเร็จแล้วค่ะ');
            }
            return redirect()->route('admin.dashboard')->withErrors(['error' => 'ไม่พบข้อมูลหลักสูตรที่ต้องการลบ (ID: ' . $id . ')']);
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->withErrors(['error' => 'เกิดข้อผิดพลาดในการลบ: ' . $e->getMessage()]);
        }
    }
}
