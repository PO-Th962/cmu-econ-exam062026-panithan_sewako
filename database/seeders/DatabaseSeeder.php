<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // เพิ่มหรืออัปเดตบัญชีผู้ดูแลระบบ (Admin)
        DB::table('admins')->updateOrInsert(
            ['username' => 'admin'],
            [
                'password' => Hash::make('password'),
                'email' => 'admin@gmail.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // เพิ่มหลักสูตรเริ่มต้น 3 หลักสูตร
        $courses = [
            'การวิเคราะห์ข้อมูลด้วย Excel',
            'การเขียนโปรแกรมด้วย Python',
            'การสร้าง Dashboard ด้วย Power BI'
        ];

        foreach ($courses as $course) {
            DB::table('courses')->updateOrInsert(
                ['name' => $course],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
