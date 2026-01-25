<?php

namespace Database\Seeders\Identity;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the identity database with users.
     */
    public function run(): void
    {
        $this->command->info('Seeding users...');

        // Create admin/owner users
        $admin = User::updateOrCreate(
            ['email' => 'admin@bocchio.dev'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $owner = User::updateOrCreate(
            ['email' => 'owner@bocchio.dev'],
            [
                'name' => 'Content Owner',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create editor users
        $editor = User::updateOrCreate(
            ['email' => 'editor@bocchio.dev'],
            [
                'name' => 'Content Editor',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create regular member user
        $member = User::updateOrCreate(
            ['email' => 'member@bocchio.dev'],
            [
                'name' => 'Member User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("✓ Created 4 users");
        $this->command->info("  - admin@bocchio.dev (password: password)");
        $this->command->info("  - owner@bocchio.dev (password: password)");
        $this->command->info("  - editor@bocchio.dev (password: password)");
        $this->command->info("  - member@bocchio.dev (password: password)");
    }
}
