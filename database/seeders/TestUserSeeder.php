<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing users
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\User::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create System Admin
        \App\Models\User::create([
            'phone_number' => '0771111222',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'name' => 'System Admin',
            'role' => 'ADMIN',
        ]);
    }
}
