<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $student = new Student;
            $student->name = fake()->name;
            $student->email = fake()->safeEmail;
            $student->mobile = 12345678 . $i;
            $student->date_of_birth = fake()->date;
            $student->password = Hash::make($student->name);
            $student->gender = "male";
            $student->city = fake()->city;
            $student->hobbies = "TEst";
            $student->profile_image = "5945914729034.jpg";
            $student->user_id = 1;
            $student->save();
        }
    }
}
