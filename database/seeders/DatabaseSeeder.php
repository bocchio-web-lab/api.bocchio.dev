<?php

namespace Database\Seeders;

use Database\Seeders\Cms\CommentSeeder;
use Database\Seeders\Cms\ContentItemSeeder;
use Database\Seeders\Cms\TagSeeder;
use Database\Seeders\Identity\UserSeeder;
use Database\Seeders\Platform\ServiceSeeder;
use Database\Seeders\Platform\TenantSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * This seeder follows a structured approach, seeding each database
     * in the correct order: Identity → Platform → CMS
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->newLine();

        // Step 1: Identity Database - Users
        $this->command->info('📊 Identity Database');
        $this->call([
            UserSeeder::class,
        ]);
        $this->command->newLine();

        // Step 2: Platform Database - Services and Tenants
        $this->command->info('📊 Platform Database');
        $this->call([
            ServiceSeeder::class,
            TenantSeeder::class,
        ]);
        $this->command->newLine();

        // Step 3: CMS Database - Tags, Content, Comments
        $this->command->info('📊 CMS Database');
        $this->call([
            TagSeeder::class,
            ContentItemSeeder::class,
            CommentSeeder::class,
        ]);
        $this->command->newLine();

        // Summary
        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('Available accounts:');
        $this->command->info('  • admin@bocchio.dev (password: password)');
        $this->command->info('  • owner@bocchio.dev (password: password)');
        $this->command->info('  • editor@bocchio.dev (password: password)');
        $this->command->info('  • member@bocchio.dev (password: password)');
    }
}
