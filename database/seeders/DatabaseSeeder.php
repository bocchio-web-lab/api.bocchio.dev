<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Platform\ServiceSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed platform services first
        $this->call([
            ServiceSeeder::class,
        ]);

        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->command->info('Database seeded successfully!');
    }
}
