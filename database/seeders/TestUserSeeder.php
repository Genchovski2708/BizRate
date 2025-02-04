<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Metadata; // Import Metadata model

class TestUserSeeder extends Seeder
{
    public function run()
    {

        // Now, create the test user with the generated metadata_id
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), // Password should be hashed
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
