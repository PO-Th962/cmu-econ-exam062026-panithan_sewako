<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        DB::table('admins')->updateOrInsert(
            ['username' => 'admin'],
            [
                'password' => Hash::make('password'),
                'email' => 'admin@gmail.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );


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
