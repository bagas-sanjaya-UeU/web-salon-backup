<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'created_at' => now(),
            ],
            [
                'name' => 'User',
                'password' => bcrypt('user123'),
                'email' => 'user@gmail.com',
                'role' => 'user',
                'created_at' => now(),
            ]
        ];
        
        \DB::table('users')->insert($user);
    }
}
