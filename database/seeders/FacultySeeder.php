<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faculty::create(['id' => (string) Str::uuid(), 'name' => 'Faculty of Information Technology']);
        Faculty::create(['id' => (string) Str::uuid(), 'name' => 'Faculty of Mechanical and Development']);
    }


}
