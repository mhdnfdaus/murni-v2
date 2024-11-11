<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fit = Faculty::where('name', 'Faculty of Information Technology')->first();
        $fmd = Faculty::where('name', 'Faculty of Mechanical and Development')->first();

        Course::create(['id' => (string) Str::uuid(), 'faculties_id' => $fit->id, 'name' => 'Diploma in Database Management System and Web Application']);
        Course::create(['id' => (string) Str::uuid(), 'faculties_id' => $fit->id, 'name' => 'Diploma in Network System']);
        Course::create(['id' => (string) Str::uuid(), 'faculties_id' => $fmd->id, 'name' => 'Diploma in Industrial Machine']);
        Course::create(['id' => (string) Str::uuid(), 'faculties_id' => $fmd->id, 'name' => 'Diploma in Welding']);
    }
}
