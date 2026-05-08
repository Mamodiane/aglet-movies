<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'jointheteam@aglet.co.za'
            ],
            [
                'name' => 'jointheteam',
                'password' => Hash::make('@TeamAglet')
            ]
        );
    }
}