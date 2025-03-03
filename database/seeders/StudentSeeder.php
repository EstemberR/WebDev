<?php

namespace Database\Seeders;

use App\Models\Student\Students;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Create 50 students
        for ($i = 1; $i <= 50; $i++) {
            $studentId = '2024-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            
            Students::create([
                'student_id' => $studentId,
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => '09' . $faker->numberBetween(100000000, 999999999),
                'address' => $faker->address,
                'date_of_birth' => $faker->date('Y-m-d', '2005-12-31'),
                'status' => 'Active',
            ]);
        }
    }
}