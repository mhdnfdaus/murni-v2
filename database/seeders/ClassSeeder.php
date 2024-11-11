<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $dba = Course::where('name', 'Diploma in Database Management System and Web Application')->first();
        $net = Course::where('name', 'Diploma in Network System')->first();
        $ind = Course::where('name', 'Diploma in Industrial Machine')->first();
        $wel = Course::where('name', 'Diploma in Welding')->first();

        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $dba->id, 'name' => '1 SVM IPD']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $dba->id, 'name' => '2 SVM IPD']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $dba->id, 'name' => '1 DVM IPD']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $dba->id, 'name' => '2 DVM IPD']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $net->id, 'name' => '1 SVM ISK']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $net->id, 'name' => '2 SVM ISK']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $net->id, 'name' => '1 DVM ISK']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $net->id, 'name' => '2 DVM ISK']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $ind->id, 'name' => '1 SVM MPI 1']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $ind->id, 'name' => '1 SVM MPI 2']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $ind->id, 'name' => '2 SVM MPI 1']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $ind->id, 'name' => '2 SVM MPI 2']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $ind->id, 'name' => '1 DVM MPI 1']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $ind->id, 'name' => '1 DVM MPI 2']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $ind->id, 'name' => '2 DVM MPI 1']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $ind->id, 'name' => '2 DVM MPI 2']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $wel->id, 'name' => '1 SVM MTK 1']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $wel->id, 'name' => '1 SVM MTK 2']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $wel->id, 'name' => '2 SVM MTK 1']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $wel->id, 'name' => '2 SVM MTK 2']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $wel->id, 'name' => '1 DVM MTK 1']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $wel->id, 'name' => '1 DVM MTK 2']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $wel->id, 'name' => '2 DVM MTK 1']);
        Classes::create(['id' => (string) Str::uuid(), 'course_id' => $wel->id, 'name' => '2 DVM MTK 2']);
    }
}
