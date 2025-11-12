<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            "name" => "Manager 1",
            "email" => "manager1@gmail.com",
            "password" => bcrypt("password"),
            "email_verified_at" => now(),
            "role" => "MANAGER"
        ]);

        User::create([
            "name" => "Employee 1",
            "email" => "employee1@gmail.com",
            "password" => bcrypt("password"),
            "email_verified_at" => now(),
            "role" => "EMPLOYEE"
        ]);


        User::create([
            "name" => "Employee 2",
            "email" => "employee2@gmail.com",
            "password" => bcrypt("password"),
            "email_verified_at" => now(),
            "role" => "EMPLOYEE"
        ]);

    }
}
