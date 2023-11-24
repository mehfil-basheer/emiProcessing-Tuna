<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'developer',
                'email' => 'developer@tuna.com',
                'password' => Hash::make('Test@Tuna123#'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        foreach ($users as $user) {
            $existingRecord = DB::table('users')
                ->where('email', $user['email'])
                ->first();
            if (!$existingRecord) {
                DB::table('users')->insert($user);
            }
        }
    }
}
