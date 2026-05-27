<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('mock/user.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("User mock file not found.");
            return;
        }

        $users = json_decode(File::get($jsonPath), true);

        foreach ($users as $user) {
            User::create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => Hash::make($user['password']),
            ]);
        }
    }
}
